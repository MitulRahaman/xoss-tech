<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostAddRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Services\PostService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Notifications\PostCreationNotification;
use App\Notifications\CommentNotification;
use Illuminate\Support\Facades\Notification;

class PostController extends Controller
{
    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        return view('post.index');
    }

    public function getTableData()
    {
        return $this->postService->getTableData();
    }

    public function create()
    {
        return view('post.create');
    }

    public function store(PostAddRequest $request)
    {
        $user = $this->postService->getUser(auth()->user()->id);

        try {
            if ($this->postService->storePost($request)) {
                Notification::send($user, new PostCreationNotification());
                return redirect('/index')->with('success', 'Post created successfully.');
            }
        } catch (\Exception $exception) {
            return redirect('/index')->with('error', 'Post created but unable to send email.');
        }
    }

    public function edit($id)
    {
        $post = $this->postService->getPost($id);
        return view('post.edit', compact('post'));
    }

    public function update(PostUpdateRequest $request, $id)
    {
        try {
            if ($this->postService->updatePost($request, $id)) {
                return redirect('/index')->with('success', 'Post updated successfully.');;
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->postService->delete($id);
            return redirect()->back()->with('success', 'Post deleted successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function show($id)
    {
        $showPost = $this->postService->showPost($id);
        return view('post.show', compact('showPost'));
    }

    public function storeComment(Request $request)
    {
        $user = $this->postService->getUser(auth()->user()->id);
        try {
            if ($this->postService->storeComment($request)) {
                Notification::send($user, new CommentNotification());
                return redirect()->back()->with('success', 'Commented successfully');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Commented but unable to send email');
        }
    }
}
