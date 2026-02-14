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
        Schema::create('forums', function (Blueprint $table) {
            // 主键 - 对应 pw_bbs_forum.fid
            $table->id('fid');
            
            // 版块关系
            $table->integer('parentid')->default(0)->index();
            $table->string('type', 20)->default('forum'); // category, forum, sub
            
            // 基本信息
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->integer('vieworder')->default(0);
            
            // 显示设置
            $table->string('icon')->nullable();
            $table->string('logo')->nullable();
            $table->string('password')->nullable();
            $table->string('style')->nullable();
            
            // 权限设置 (JSON 格式存储)
            $table->json('allow_visit')->nullable();
            $table->json('allow_read')->nullable();
            $table->json('allow_post')->nullable();
            $table->json('allow_reply')->nullable();
            
            // 统计信息
            $table->integer('threads_count')->default(0);
            $table->integer('posts_count')->default(0);
            
            // 时间戳
            $table->integer('created_time')->unsigned()->nullable();
            $table->integer('created_userid')->nullable();
            $table->string('created_username')->nullable();
            
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forums');
    }
};