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
        return Post::find($this->id);
    }

    public function getUser()
    {
        return User::find($this->id);
    }

    public function showPost()
    {
        return Post::with(['user', 'comments.user'])->find($this->id);
    }

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

}
