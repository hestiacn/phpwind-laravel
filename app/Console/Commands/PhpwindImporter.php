<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Forum;
use App\Models\Thread;
use App\Models\Post;
use App\Models\Attachment;

class PhpwindImporter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:phpwind 
                            {--connection=mysql_old : 老数据库连接名}
                            {--chunk=100 : 每批导入数量}
                            {--limit=0 : 限制导入条数(0为全部)}
                            {--type=all : 导入类型(user,forum,thread,post,attachment,all)}
                            {--truncate : 是否清空现有数据}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '从 phpwind 导入数据到 Laravel';

    /**
     * 老数据库连接
     */
    protected $oldDb;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 设置老数据库连接
        $this->oldDb = DB::connection($this->option('connection'));
        
        // 测试连接
        try {
            $this->oldDb->getPdo();
            $this->info('✅ 成功连接到老数据库');
        } catch (\Exception $e) {
            $this->error('❌ 连接老数据库失败：' . $e->getMessage());
            $this->info('请在 config/database.php 中配置 mysql_old 连接');
            return 1;
        }

        // 是否清空
        if ($this->option('truncate')) {
            if ($this->confirm('确定要清空现有数据吗？')) {
                $this->call('db:wipe');
                $this->call('migrate');
            }
        }

        $type = $this->option('type');
        
        // 根据类型导入
        switch ($type) {
            case 'user':
                $this->importUsers();
                break;
            case 'forum':
                $this->importForums();
                break;
            case 'thread':
                $this->importThreads();
                break;
            case 'post':
                $this->importPosts();
                break;
            case 'attachment':
                $this->importAttachments();
                break;
            case 'all':
                $this->importAll();
                break;
            default:
                $this->error('不支持的导入类型：' . $type);
                return 1;
        }

        $this->info('🎉 导入完成！');
        return 0;
    }

    /**
     * 导入所有数据
     */
    protected function importAll()
    {
        $this->importUsers();
        $this->importForums();
        $this->importThreads();
        $this->importPosts();
        $this->importAttachments();
    }

    /**
     * 导入用户数据
     */
    protected function importUsers()
    {
        $this->info('开始导入用户数据...');
        
        $query = $this->oldDb->table('user')->orderBy('uid');
        
        if ($limit = $this->option('limit')) {
            $query->limit($limit);
        }
        
        $total = $query->count();
        $this->info("共发现 {$total} 个用户");
        
        $bar = $this->output->createProgressBar($total);
        
        $query->chunk($this->option('chunk'), function($users) use ($bar) {
            foreach ($users as $oldUser) {
                try {
                    User::updateOrCreate(
                        ['uid' => $oldUser->uid],
                        [
                            'name' => $oldUser->username,
                            'email' => $oldUser->email,
                            'password' => $oldUser->password, // 需要处理加密方式
                            'status' => $oldUser->status ?? 0,
                            'groupid' => $oldUser->groupid ?? 0,
                            'memberid' => $oldUser->memberid ?? 0,
                            'regdate' => $oldUser->regdate,
                            'realname' => $oldUser->realname ?? '',
                            'created_at' => $oldUser->regdate ? date('Y-m-d H:i:s', $oldUser->regdate) : null,
                        ]
                    );
                } catch (\Exception $e) {
                    $this->error("用户 {$oldUser->uid} 导入失败：" . $e->getMessage());
                }
                $bar->advance();
            }
        });
        
        $bar->finish();
        $this->newLine();
        $this->info('✅ 用户导入完成');
    }

    /**
     * 导入版块数据
     */
    protected function importForums()
    {
        $this->info('开始导入版块数据...');
        
        $forums = $this->oldDb->table('bbs_forum')->orderBy('fid')->get();
        
        $this->info("共发现 {$forums->count()} 个版块");
        $bar = $this->output->createProgressBar($forums->count());
        
        foreach ($forums as $oldForum) {
            try {
                Forum::updateOrCreate(
                    ['fid' => $oldForum->fid],
                    [
                        'parentid' => $oldForum->parentid ?? 0,
                        'type' => $oldForum->type ?? 'forum',
                        'name' => $oldForum->name,
                        'description' => $oldForum->descrip ?? '',
                        'vieworder' => $oldForum->vieworder ?? 0,
                        'icon' => $oldForum->icon ?? '',
                        'logo' => $oldForum->logo ?? '',
                        'password' => $oldForum->password ?? '',
                        'style' => $oldForum->style ?? '',
                        'created_time' => $oldForum->created_time ?? time(),
                    ]
                );
            } catch (\Exception $e) {
                $this->error("版块 {$oldForum->fid} 导入失败：" . $e->getMessage());
            }
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('✅ 版块导入完成');
    }

    /**
     * 导入帖子数据
     */
    protected function importThreads()
    {
        $this->info('开始导入帖子数据...');
        
        $query = $this->oldDb->table('bbs_threads')
            ->leftJoin('bbs_threads_content', 'bbs_threads.tid', '=', 'bbs_threads_content.tid')
            ->orderBy('bbs_threads.tid');
        
        if ($limit = $this->option('limit')) {
            $query->limit($limit);
        }
        
        $total = $query->count();
        $this->info("共发现 {$total} 个帖子");
        
        $bar = $this->output->createProgressBar($total);
        
        $query->chunk($this->option('chunk'), function($threads) use ($bar) {
            foreach ($threads as $oldThread) {
                try {
                    Thread::updateOrCreate(
                        ['tid' => $oldThread->tid],
                        [
                            'fid' => $oldThread->fid,
                            'topic_type' => $oldThread->topic_type ?? 0,
                            'subject' => $oldThread->subject,
                            'content' => $oldThread->content ?? '',
                            'status' => $oldThread->disabled ? 0 : 1,
                            'digest' => $oldThread->digest ?? 0,
                            'topped' => $oldThread->topped ?? 0,
                            'replies' => $oldThread->replies ?? 0,
                            'hits' => $oldThread->hits ?? 0,
                            'likes_count' => $oldThread->like_count ?? 0,
                            'created_userid' => $oldThread->created_userid,
                            'created_username' => $oldThread->created_username ?? '',
                            'created_time' => $oldThread->created_time,
                            'lastpost_time' => $oldThread->lastpost_time ?? 0,
                            'lastpost_userid' => $oldThread->lastpost_userid ?? 0,
                            'lastpost_username' => $oldThread->lastpost_username ?? '',
                        ]
                    );
                } catch (\Exception $e) {
                    $this->error("帖子 {$oldThread->tid} 导入失败：" . $e->getMessage());
                }
                $bar->advance();
            }
        });
        
        $bar->finish();
        $this->newLine();
        $this->info('✅ 帖子导入完成');
    }

    /**
     * 导入回复数据
     */
    protected function importPosts()
    {
        $this->info('开始导入回复数据...');
        
        $query = $this->oldDb->table('bbs_posts')->orderBy('pid');
        
        if ($limit = $this->option('limit')) {
            $query->limit($limit);
        }
        
        $total = $query->count();
        $this->info("共发现 {$total} 个回复");
        
        $bar = $this->output->createProgressBar($total);
        
        $query->chunk($this->option('chunk'), function($posts) use ($bar) {
            foreach ($posts as $oldPost) {
                try {
                    Post::updateOrCreate(
                        ['pid' => $oldPost->pid],
                        [
                            'tid' => $oldPost->tid,
                            'fid' => $oldPost->fid,
                            'content' => $oldPost->content,
                            'status' => $oldPost->disabled ? 0 : 1,
                            'created_userid' => $oldPost->created_userid,
                            'created_username' => $oldPost->created_username ?? '',
                            'created_time' => $oldPost->created_time,
                        ]
                    );
                } catch (\Exception $e) {
                    $this->error("回复 {$oldPost->pid} 导入失败：" . $e->getMessage());
                }
                $bar->advance();
            }
        });
        
        $bar->finish();
        $this->newLine();
        $this->info('✅ 回复导入完成');
    }

    /**
     * 导入附件数据
     */
    protected function importAttachments()
    {
        $this->info('开始导入附件数据...');
        
        // 导入主附件表
        $query = $this->oldDb->table('attachs')->orderBy('aid');
        
        if ($limit = $this->option('limit')) {
            $query->limit($limit);
        }
        
        $total = $query->count();
        $this->info("共发现 {$total} 个附件记录");
        
        $bar = $this->output->createProgressBar($total);
        
        $query->chunk($this->option('chunk'), function($attachments) use ($bar) {
            foreach ($attachments as $oldAttach) {
                try {
                    Attachment::updateOrCreate(
                        ['aid' => $oldAttach->aid],
                        [
                            'name' => $oldAttach->name,
                            'type' => $oldAttach->type,
                            'size' => $oldAttach->size,
                            'path' => $oldAttach->path,
                            'ifthumb' => $oldAttach->ifthumb ?? 0,
                            'created_userid' => $oldAttach->created_userid,
                            'created_time' => $oldAttach->created_time,
                            'app' => $oldAttach->app ?? '',
                            'app_id' => $oldAttach->app_id ?? 0,
                        ]
                    );
                } catch (\Exception $e) {
                    $this->error("附件 {$oldAttach->aid} 导入失败：" . $e->getMessage());
                }
                $bar->advance();
            }
        });
        
        $bar->finish();
        $this->newLine();
        $this->info('✅ 附件导入完成');
        
        // 导入帖子附件关系
        $this->info('开始导入帖子附件关系...');
        
        $query = $this->oldDb->table('attachs_thread')->orderBy('aid');
        
        if ($limit = $this->option('limit')) {
            $query->limit($limit);
        }
        
        $total = $query->count();
        $bar = $this->output->createProgressBar($total);
        
        $query->chunk($this->option('chunk'), function($relations) use ($bar) {
            foreach ($relations as $rel) {
                try {
                    DB::table('attachment_thread')->updateOrInsert(
                        ['aid' => $rel->aid],
                        [
                            'tid' => $rel->tid,
                            'pid' => $rel->pid,
                            'name' => $rel->name,
                            'path' => $rel->path,
                            'created_userid' => $rel->created_userid,
                            'created_time' => $rel->created_time,
                        ]
                    );
                } catch (\Exception $e) {
                    $this->error("附件关系 {$rel->aid} 导入失败：" . $e->getMessage());
                }
                $bar->advance();
            }
        });
        
        $bar->finish();
        $this->newLine();
        $this->info('✅ 附件关系导入完成');
    }
}