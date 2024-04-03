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
        try {
            if ($this->postService->storePost($request)) {
                return redirect('/index');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
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
                return redirect('/index');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->postService->delete($id);
            return redirect()->back();
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function show($id)
    {
        $showPost = $this->postService->showPost($id);
        //dd($showPost->comments[1]->user->name);
        return view('post.show', compact('showPost'));
    }

    public function storeComment(Request $request)
    {
        try {
            if ($this->postService->storeComment($request)) {
                return redirect()->back();
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
