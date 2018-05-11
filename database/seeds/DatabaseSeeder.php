<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        set_time_limit(0);
        ini_set('memory_limit','2048M');

        //生成100万用户数据
        factory(App\Models\User::class, 1000000)->create();
    }
}
