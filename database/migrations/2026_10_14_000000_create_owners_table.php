<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwnersTable extends Migration
{
    /**
     * 业主认证表
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('owners')) {
            return;
        }
        Schema::create('owners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('openid', 64)->unique()->comment('微信用户openid（云托管注入）');
            $table->string('name', 20)->comment('业主姓名');
            $table->string('phone', 11)->comment('手机号');
            $table->string('house_no', 50)->index()->comment('楼栋房号');
            $table->char('id_tail', 4)->comment('身份证后4位');
            $table->string('member_no', 20)->nullable()->unique()->comment('业主编号');
            $table->tinyInteger('status')->default(1)->comment('状态：1已认证 0待审核');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('owners');
    }
}
