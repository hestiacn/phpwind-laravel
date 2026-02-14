<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('threads', function (Blueprint $table) {
            // 主键 - 对应 pw_bbs_threads.tid
            $table->id('tid');
            
            // 版块ID
            $table->integer('fid')->index();
            
            // 主题类型
            $table->integer('topic_type')->default(0);
            
            // 标题和内容
            $table->string('subject')->index();
            $table->longText('content')->nullable();
            
            // 帖子状态
            $table->tinyInteger('status')->default(1)->index();
            $table->tinyInteger('digest')->default(0)->index();
            $table->tinyInteger('topped')->default(0)->index();
            $table->tinyInteger('disabled')->default(0)->index();
            $table->tinyInteger('ischeck')->default(1)->index();
            
            // 统计信息
            $table->integer('replies')->default(0);
            $table->integer('hits')->default(0);
            $table->integer('likes_count')->default(0);
            
            // 作者信息
            $table->integer('created_userid')->index();
            $table->string('created_username');
            $table->integer('created_time')->unsigned();
            
            // 最后回复信息
            $table->integer('lastpost_time')->unsigned()->index()->nullable();
            $table->integer('lastpost_userid')->nullable();
            $table->string('lastpost_username')->nullable();
            
            // 扩展字段
            $table->string('highlight', 64)->nullable();
            $table->string('inspect', 30)->nullable();
            $table->integer('overtime')->unsigned()->default(0);
            $table->string('special', 20)->default('0');
            $table->integer('tpcstatus')->unsigned()->default(0);
            $table->tinyInteger('ifupload')->default(0);
            $table->tinyInteger('reply_notice')->default(1);
            $table->integer('reply_topped')->unsigned()->default(0);
            $table->integer('thread_status')->unsigned()->default(0);
            
            // Laravel 时间戳
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('threads');
    }
};