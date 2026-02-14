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
        Schema::table('users', function (Blueprint $table) {
            // 添加 phpwind 用户ID（保留原ID）
            $table->integer('uid')->unsigned()->unique()->nullable()->after('id');
            
            // 用户状态
            $table->smallInteger('status')->default(0)->after('remember_token');
            
            // 用户组信息
            $table->integer('groupid')->default(0)->after('status');
            $table->integer('memberid')->default(0)->after('groupid');
            $table->string('groups')->nullable()->after('memberid');
            
            // 注册信息
            $table->integer('regdate')->unsigned()->nullable()->after('groups');
            
            // 头像
            $table->string('avatar')->nullable()->after('regdate');
            
            // 真实姓名
            $table->string('realname', 50)->nullable()->after('avatar');
            
            // 统计信息
            $table->integer('threads_count')->default(0)->after('realname');
            $table->integer('posts_count')->default(0)->after('threads_count');
            $table->integer('likes_count')->default(0)->after('posts_count');
            $table->integer('fans_count')->default(0)->after('likes_count');
            $table->integer('follows_count')->default(0)->after('fans_count');
            
            // 最后活动
            $table->timestamp('last_active_at')->nullable()->after('follows_count');
            $table->string('last_login_ip', 20)->nullable()->after('last_active_at');
            
            // 2FA 字段（如果需要）
            $table->text('two_factor_secret')->nullable()->after('last_login_ip');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
            
            // 索引
            $table->index('uid');
            $table->index('groupid');
            $table->index('memberid');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 删除所有添加的字段
            $table->dropColumn([
                'uid',
                'status',
                'groupid',
                'memberid',
                'groups',
                'regdate',
                'avatar',
                'realname',
                'threads_count',
                'posts_count',
                'likes_count',
                'fans_count',
                'follows_count',
                'last_active_at',
                'last_login_ip',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
            ]);
        });
    }
};