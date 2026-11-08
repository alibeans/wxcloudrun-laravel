<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 计数器表（云托管连通性测试）
     */
    public function up()
    {
        if (Schema::hasTable('counters')) {
            return;
        }
        Schema::create('counters', function (Blueprint $table) {
            $table->id();
            $table->integer('count')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('counters');
    }
};
