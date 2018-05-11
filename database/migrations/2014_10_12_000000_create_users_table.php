<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('level')->default(0)->comment('0 默认, 2表示代理商');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('非0推荐人ID');
            $table->unsignedDecimal('monthly_income', 15, 2)->default(0)->comment('月收益');
            $table->unsignedDecimal('total_income', 15, 2)->default(0)->comment('总收入');
            $table->timestamps();
            $table->index(['level']);
            $table->index(['parent_id']);

        });
        Schema::create('agent_incomes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedMediumInteger('date');
            $table->unsignedMediumInteger('agent_id')->default(0)->comment('当时对应上级代理商');
            $table->unsignedTinyInteger('ratio')->default(0)->comment('当时对应系统分配比例');
            $table->unsignedDecimal('monthly_income', 15, 2)->default(0)->comment('当时的月收益');
            $table->unsignedDecimal('income', 15, 2)->default(0)->comment('代理商获得的收益');
            $table->timestamps();
            $table->index(['user_id', 'date']);
            $table->index(['agent_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('agent_incomes');
    }
}
