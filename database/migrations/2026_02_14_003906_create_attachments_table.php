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
        // 主附件表 - 对应 pw_attachs
        Schema::create('attachments', function (Blueprint $table) {
            // 主键 - 对应 pw_attachs.aid
            $table->id('aid');
            
            // 文件信息
            $table->string('name', 80);           // 文件名
            $table->string('type', 15);           // 文件类型
            $table->integer('size')->unsigned();   // 文件大小
            $table->string('path', 80);            // 存储路径
            $table->tinyInteger('ifthumb')->default(0); // 是否有缩略图
            
            // 上传者信息
            $table->integer('created_userid')->index();
            $table->integer('created_time')->unsigned();
            
            // 关联信息
            $table->string('app', 15)->default('');     // 来自应用类型
            $table->integer('app_id')->unsigned()->default(0); // 来自应用模块id
            $table->string('descrip', 255)->nullable(); // 文件描述
            
            $table->timestamps(); // created_at, updated_at
        });

        // 帖子附件关系表 - 对应 pw_attachs_thread
        Schema::create('attachment_thread', function (Blueprint $table) {
            $table->integer('aid')->primary();     // 附件id
            $table->integer('tid')->index();       // 所属帖子id
            $table->integer('pid')->default(0);    // 所属回复id
            $table->integer('fid')->default(0)->index(); // 所属版块id
            
            // 文件信息
            $table->string('name', 80);            // 文件名
            $table->string('type', 15)->nullable(); // 文件类型
            $table->integer('size')->unsigned()->default(0); // 文件大小
            $table->string('path', 80);             // 存储路径
            $table->integer('hits')->unsigned()->default(0); // 下载数
            $table->smallInteger('width')->unsigned()->default(0); // 图片宽度
            $table->smallInteger('height')->unsigned()->default(0); // 图片高度
            $table->tinyInteger('ifthumb')->default(0); // 是否有缩略图
            
            // 售密相关
            $table->tinyInteger('special')->default(0); // 是否售密
            $table->integer('cost')->unsigned()->default(0); // 售密价格
            $table->smallInteger('ctype')->unsigned()->default(0); // 积分类型
            
            // 上传者信息
            $table->integer('created_userid')->index();
            $table->integer('created_time')->unsigned();
            $table->string('descrip', 255)->nullable(); // 文件描述
        });

        // 附件购买记录表 - 对应 pw_attachs_thread_buy
        Schema::create('attachment_buy', function (Blueprint $table) {
            $table->id();
            $table->integer('aid')->index();
            $table->integer('created_userid')->index();
            $table->integer('created_time')->unsigned();
            $table->integer('cost')->unsigned()->default(0);
            $table->smallInteger('ctype')->unsigned()->default(0);
        });

        // 附件下载记录表 - 对应 pw_attachs_thread_download
        Schema::create('attachment_download', function (Blueprint $table) {
            $table->id();
            $table->integer('aid')->index();
            $table->integer('created_userid')->index();
            $table->integer('created_time')->unsigned();
            $table->integer('cost')->unsigned()->default(0);
            $table->smallInteger('ctype')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('attachment_thread');
        Schema::dropIfExists('attachment_buy');
        Schema::dropIfExists('attachment_download');
    }
};