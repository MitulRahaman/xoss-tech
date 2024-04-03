<?php

namespace App\Repositories;

use Validator;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;



class PostRepository
{
    private $title, $content, $file, $publishedAt, $createdAt;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;
        return $this;
    }
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getTableData()
    {
        return Post::all();
    }

    public function getPost()
    {
        return Post::where('id', '=', $this->id)->first();
    }

    public function showPost()
    {
        return Post::with(['user', 'comments.user'])->find($this->id);
    }

    // public function getLeaveAppliedEmailRecipient()
    // {
    //     $appliedUser = DB::table('basic_info')->where('user_id', '=', $this->id)->first();
    //     if($appliedUser == null ) {
    //         return false;
    //     }

    //     $getLineManagers  = DB::table('users as u')
    //     ->leftJoin('line_managers as lm', function ($join) {
    //         $join->on('u.id', '=', 'lm.user_id')
    //         ->whereNULL('lm.deleted_at');
    //     })
    //     ->where('lm.user_id', '=', $appliedUser->user_id)
    //     ->select('lm.line_manager_user_id')
    //     ->get()
    //     ->toArray();

    //     $lineManagerEmail = array();
    //     foreach ($getLineManagers as $glm) {
    //         array_push($lineManagerEmail, DB::table('users')->where('id', '=', $glm->line_manager_user_id)->first()->email);
    //     }

    //     $hasManageLeavePermission = DB::table('permissions as p')
    //         ->leftJoin('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
    //         ->leftJoin('basic_info as bi', 'bi.role_id', '=', 'rp.role_id')
    //         ->where('p.slug', '=', 'notifyLeaveApply')
    //         ->where('bi.branch_id', '=', $appliedUser->branch_id)
    //         ->select('rp.role_id')
    //         ->get()
    //         ->toArray();
    //     if($hasManageLeavePermission == null ) {
    //         return false;
    //     }

    //     $recipientEmail = array();
    //     foreach ($hasManageLeavePermission as $hmlp) {
    //         array_push($recipientEmail, DB::table('basic_info')->where('role_id', '=', $hmlp->role_id)->first()->preferred_email);
    //     }
    //     if($recipientEmail == null ) {
    //         return false;
    //     }
    //     return [$lineManagerEmail, $recipientEmail];
    // }

    public function storePost()
    {
        $post = new Post();
        $post->user_id = auth()->user()->id;
        $post->title = $this->title;
        $post->content = $this->content;
        $post->photo = $this->file;
        $post->published_at = $this->publishedAt;
        $post->created_at = $this->createdAt;
        $post->save();

        return $post;
    }

    public function storeComment()
    {
        $comment = new Comment();
        $comment->user_id = auth()->user()->id;
        $comment->post_id = $this->id;
        $comment->comment = $this->content;
        $comment->created_at = $this->createdAt;
        $comment->save();

        return $comment;
    }

    public function updatePost()
    {
        $postToUpdate = Post::find($this->id);

        if($postToUpdate) {
            $postToUpdate->title = $this->title;
            $postToUpdate->content = $this->content;
            if (!is_null($this->file)) {
                $postToUpdate->photo = $this->file;
            }
            $postToUpdate->save();
            return $postToUpdate;
        } else {
            return response()->json(['error' => 'Post not found'], 404);
        }
    }

    public function delete()
    {
        return Post::destroy($this->id);
    }

    // public function getReciever($employeeId)
    // {
    //     $appliedUser = DB::table('users')->where('employee_id',$employeeId)->first();
    //     $getLineManagers  = DB::table('users as u')
    //     ->leftJoin('line_managers as lm', function ($join) {
    //         $join->on('u.id', '=', 'lm.user_id')
    //         ->whereNULL('lm.deleted_at');
    //     })
    //     ->where('lm.user_id', '=', $appliedUser->id)
    //     ->select('lm.line_manager_user_id')
    //     ->get()
    //     ->toArray();

    //     $lineManagerEmail = array();
    //     foreach ($getLineManagers as $glm) {
    //         array_push($lineManagerEmail, DB::table('users')->where('id', '=', $glm->line_manager_user_id)->first()->email);
    //     }
    //     return [$lineManagerEmail, $appliedUser->email];
    // }
}
