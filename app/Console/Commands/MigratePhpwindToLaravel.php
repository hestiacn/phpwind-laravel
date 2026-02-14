<?php
// app/Console/Commands/MigratePhpwindToLaravel.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Forum;
use App\Models\Thread;
use App\Models\Post;
use App\Models\Attachment;

class MigratePhpwindToLaravel extends Command
{
    protected $signature = 'migrate:phpwind 
                            {--connection=mysql_old : 老数据库连接}
                            {--chunk=100 : 每批处理数量}
                            {--type=all : 迁移类型(user,forum,thread,post,attachment,all)}
                            {--limit=0 : 限制迁移数量}
                            {--truncate : 清空现有数据}';

    protected $description = '从 phpwind 迁移数据到 Laravel';

    protected $oldDb;
    protected $totalImported = 0;

    public function handle()
    {
        $this->info('🚀 开始迁移 phpwind 数据到 Laravel');
        $this->newLine();

        // 连接老数据库
        try {
            $this->oldDb = DB::connection($this->option('connection'));
            $this->oldDb->getPdo();
            $this->info('✅ 成功连接到老数据库');
        } catch (\Exception $e) {
            $this->error('❌ 连接老数据库失败：' . $e->getMessage());
            $this->warn('请在 config/database.php 中配置 mysql_old 连接');
            return 1;
        }

        // 清空数据
        if ($this->option('truncate')) {
            if ($this->confirm('确定要清空现有数据吗？')) {
                $this->call('db:wipe');
                $this->call('migrate');
            }
        }

        $type = $this->option('type');
        
        // 开始迁移
        $startTime = microtime(true);

        switch ($type) {
            case 'user':
                $this->migrateUsers();
                break;
            case 'forum':
                $this->migrateForums();
                break;
            case 'thread':
                $this->migrateThreads();
                break;
            case 'post':
                $this->migratePosts();
                break;
            case 'attachment':
                $this->migrateAttachments();
                break;
            case 'all':
                $this->migrateAll();
                break;
            default:
                $this->error('❌ 不支持的迁移类型：' . $type);
                return 1;
        }

        $time = round(microtime(true) - $startTime, 2);
        $this->newLine();
        $this->info("🎉 迁移完成！共迁移 {$this->totalImported} 条数据，耗时 {$time} 秒");
        
        return 0;
    }

    protected function migrateAll()
    {
        $this->migrateUsers();
        $this->migrateForums();
        $this->migrateThreads();
        $this->migratePosts();
        $this->migrateAttachments();
    }

    protected function migrateUsers()
    {
        $this->info('📦 开始迁移用户数据...');
        
        $query = $this->oldDb->table('pw_user')
            ->leftJoin('pw_user_data', 'pw_user.uid', '=', 'pw_user_data.uid')
            ->leftJoin('pw_windid_user_info', 'pw_user.uid', '=', 'pw_windid_user_info.uid')
            ->select([
                'pw_user.*',
                'pw_user_data.lastvisit',
                'pw_user_data.postnum',
                'pw_windid_user_info.icon as avatar'
            ])
            ->orderBy('pw_user.uid');

        if ($limit = $this->option('limit')) {
            $query->limit($limit);
        }

        $total = $query->count();
        $this->info("共发现 {$total} 个用户需要迁移");
        
        if ($total == 0) {
            $this->warn('没有找到用户数据');
            return;
        }

        $bar = $this->output->createProgressBar($total);
        
        $query->chunk($this->option('chunk'), function($users) use ($bar) {
            foreach ($users as $oldUser) {
                try {
                    // 生成头像 URL
                    $avatarUrl = $this->generateAvatarUrl($oldUser->uid, $oldUser->avatar);
                    
                    $user = User::updateOrCreate(
                        ['uid' => $oldUser->uid],
                        [
                            'name' => $oldUser->username,
                            'email' => $oldUser->email,
                            'password' => $oldUser->password, // 注意：需要处理加密方式
                            'status' => $oldUser->status ?? 0,
                            'groupid' => $oldUser->groupid ?? 0,
                            'memberid' => $oldUser->memberid ?? 0,
                            'regdate' => $oldUser->regdate,
                            'avatar' => $avatarUrl,
                            'threads_count' => 0,
                            'posts_count' => $oldUser->postnum ?? 0,
                            'last_active_at' => $oldUser->lastvisit ? date('Y-m-d H:i:s', $oldUser->lastvisit) : null,
                            'created_at' => $oldUser->regdate ? date('Y-m-d H:i:s', $oldUser->regdate) : null,
                        ]
                    );
                    
                    $this->totalImported++;
                } catch (\Exception $e) {
                    $this->error("用户 {$oldUser->uid} 迁移失败：" . $e->getMessage());
                }
                $bar->advance();
            }
        });
        
        $bar->finish();
        $this->newLine();
        $this->info('✅ 用户迁移完成');
    }

    /**
     * 生成头像URL
     */
    protected function generateAvatarUrl($uid, $icon)
    {
        // 如果有自定义头像
        if (!empty($icon)) {
            $path = implode('/', str_split(str_pad(substr($uid, 0, 5), 9, '0', STR_PAD_LEFT), 3));
            return "https://bbs.peiyin.com/windid/attachment/avatar/{$path}/{$uid}_small.jpg";
        }
        
        // 返回默认头像
        return '/images/default-avatar.png';
    }

    protected function migrateForums()
    {
        $this->info('📦 开始迁移版块数据...');
        
        $forums = $this->oldDb->table('pw_bbs_forum')
            ->orderBy('fid')
            ->get();

        $total = $forums->count();
        $this->info("共发现 {$total} 个版块");
        
        if ($total == 0) {
            $this->warn('没有找到版块数据');
            return;
        }

        $bar = $this->output->createProgressBar($total);
        
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
                        'threads_count' => $oldForum->threads ?? 0,
                        'posts_count' => $oldForum->posts ?? 0,
                    ]
                );
                
                $this->totalImported++;
            } catch (\Exception $e) {
                $this->error("版块 {$oldForum->fid} 迁移失败：" . $e->getMessage());
            }
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('✅ 版块迁移完成');
    }

    protected function migrateThreads()
    {
        $this->info('📦 开始迁移帖子数据...');
        
        $query = $this->oldDb->table('pw_bbs_threads')
            ->leftJoin('pw_bbs_threads_content', 'pw_bbs_threads.tid', '=', 'pw_bbs_threads_content.tid')
            ->select([
                'pw_bbs_threads.*',
                'pw_bbs_threads_content.content'
            ])
            ->orderBy('pw_bbs_threads.tid');

        if ($limit = $this->option('limit')) {
            $query->limit($limit);
        }

        $total = $query->count();
        $this->info("共发现 {$total} 个帖子");
        
        if ($total == 0) {
            $this->warn('没有找到帖子数据');
            return;
        }

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
                    
                    $this->totalImported++;
                } catch (\Exception $e) {
                    $this->error("帖子 {$oldThread->tid} 迁移失败：" . $e->getMessage());
                }
                $bar->advance();
            }
        });
        
        $bar->finish();
        $this->newLine();
        $this->info('✅ 帖子迁移完成');
    }

    protected function migratePosts()
    {
        $this->info('📦 开始迁移回复数据...');
        
        $query = $this->oldDb->table('pw_bbs_posts')
            ->orderBy('pid');

        if ($limit = $this->option('limit')) {
            $query->limit($limit);
        }

        $total = $query->count();
        $this->info("共发现 {$total} 个回复");
        
        if ($total == 0) {
            $this->warn('没有找到回复数据');
            return;
        }

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
                    
                    $this->totalImported++;
                } catch (\Exception $e) {
                    $this->error("回复 {$oldPost->pid} 迁移失败：" . $e->getMessage());
                }
                $bar->advance();
            }
        });
        
        $bar->finish();
        $this->newLine();
        $this->info('✅ 回复迁移完成');
    }

    protected function migrateAttachments()
    {
        $this->info('📦 开始迁移附件数据...');
        
        // 迁移主附件表
        $query = $this->oldDb->table('pw_attachs')
            ->orderBy('aid');

        if ($limit = $this->option('limit')) {
            $query->limit($limit);
        }

        $total = $query->count();
        $this->info("共发现 {$total} 个附件记录");
        
        if ($total > 0) {
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
                        
                        $this->totalImported++;
                    } catch (\Exception $e) {
                        $this->error("附件 {$oldAttach->aid} 迁移失败：" . $e->getMessage());
                    }
                    $bar->advance();
                }
            });
            
            $bar->finish();
            $this->newLine();
        }

        // 迁移帖子附件关系
        $this->info('📦 开始迁移帖子附件关系...');
        
        $query = $this->oldDb->table('pw_attachs_thread')
            ->orderBy('aid');

        if ($limit = $this->option('limit')) {
            $query->limit($limit);
        }

        $total = $query->count();
        $this->info("共发现 {$total} 个帖子附件关系");
        
        if ($total > 0) {
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
                        
                        $this->totalImported++;
                    } catch (\Exception $e) {
                        $this->error("附件关系 {$rel->aid} 迁移失败：" . $e->getMessage());
                    }
                    $bar->advance();
                }
            });
            
            $bar->finish();
            $this->newLine();
        }
        
        $this->info('✅ 附件迁移完成');
    }
}