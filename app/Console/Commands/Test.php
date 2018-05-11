<?php

namespace App\Console\Commands;

use App\Models\AgentIncome;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test agent incomes ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(0);
        ini_set('memory_limit','2048M');
        //每个月计算一次 代理商获取下级代理商所有收益的20%
        //找到我上级代理商, 前提是我自己是代理
        //再从最顶层开始逐一计算。

        $startTime = time();

        $this->info("开始计算, 开始时间". date('Y-m-d H:i:s', $startTime));

        $ratio = 20;

        //找到所有的代理商 ， 降序排列 (ID 自动增长)
        $allD = User::where('level', 2)->orderBy('id', ' desc')->get(['id', 'level', 'parent_id', 'monthly_income', 'total_income']);


        $bar = $this->output->createProgressBar(count($allD));


        foreach ($allD as $item=>$value) {


            //找不到对应的推荐人，就退出循环（不进行分配）。
            $refereeUser = $value->referee;
            if (!$refereeUser) {
                unset($allD[$item]);
                continue;
            }

            //上级代理商
            $refereeD = '';

            //循环上级有人就继续循环
            while ($refereeUser) {
                //当前用户上级就是代理商就退出循环
                if ($refereeUser->level == 2) {
                    $refereeD = $refereeUser;
                    break;
                }
                //找不到对应的推荐人，就退出循环
                if (!$refereeUser->referee) {
                    break;
                }
                if ($refereeUser->referee->level == 2) {
                    $refereeD = $refereeUser->referee;
                    //找到对应的上级代理商后退出循环
                    break;
                } else {
                    $refereeUser = $refereeUser->referee;
                };
            }
            //未找到对应的上级代理商，就退出循环（不进行分配）。
            if (!$refereeD) {
                unset($allD[$item]);
                continue;
            }

            //$refereeD 上级代理商 ， $value 自己， 找到自己的每月的累计收益（代理商，直推） * 系统设置的分配比例 发放给上级代理商
            $income = $value->monthly_income * $ratio / 100;

            DB::beginTransaction();
            try {


                $refereeD->increment('total_income', $income);
                AgentIncome::create([
                    'user_id' => $value->id,
                    'date' => date('Ym'),
                    'agent_id' => $refereeD->id,
                    'ratio' => $ratio,
                    'monthly_income' => $value->monthly_income,
                    'income' => $income
                ]);
                DB::commit();
            }catch (\Exception $exception) {
                DB::rollBack();
                $this->error( "用户{$refereeUser->id} : '计算错误");
            }

            $bar->advance();
        }

        $bar->finish();

        $this->info("结束计算, 结束时间". date('Y-m-d H:i:s'));

    }
}
