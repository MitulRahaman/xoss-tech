<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn {
            padding: 0.3rem 0.5rem;
            font-weight: 600;
            cursor: pointer;
            border-radius: 0.25rem;
        }
        .btn.btn-alt-primary {
            color: #fff;
            background-color: #007bff;
        }
        .btn.btn-alt-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 10px;
            padding: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            margin: 0;
            font-size: 18px;
        }
        .card-body {
            padding: 10px;
        }
    </style>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Post By {{ $showPost->user->name }}
            </h2>
            <div>
                <a href="{{ route('post.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" style="text-decoration: none;">
                    Show All Post
                </a>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close text-dark mr-4" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close text-dark mr-4" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ session('error') }}
        </div>
    @endif

    <div class="container justify-content-center p-6 px-8">
        <div class="row">
            <div class="col-sm-12 col-md-12 px-8">
                <div class="row mt-4">
                    <div class="col-sm-12">
                        <div class="card text-bg-light" style="padding: 1rem;">
                            <h2 class="card-title mb-4" style="font-size: 24px;">{{ $showPost->title }}</h2>
                            <p class="card-text mb-4">{{ $showPost->content }}</p>
                            @if ($showPost->photo)
                                <img src="{{ asset('storage/postFiles/'. $showPost->photo) }}" class="rounded" width="10%" alt="post_image">
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mt-8">
                    <div class="col-sm-12">
                        <h4 class="ml-3" style="font-size: 24px;">Comments</h4>
                        @if($showPost->comments)
                            @foreach ($showPost->comments as $comment)
                                <div class="card mt-2">
                                    <div class="card-title">
                                        <strong>{{ $comment->user['name'] }}</strong> - {{ $comment['updated_at'] }}
                                    </div>
                                    <div class="card-body">
                                        <p>{{ $comment['comment'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-sm-12">
                        <div class="card mt-2">
                            <form action="{{ route('post.storeComment') }}" method="POST">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $showPost->id }}">
                                <label for="comment">Add a Comment:</label>
                                <div class="form-group">
                                    <textarea class="form-control" style="width: 600px;" id="comment" name="comment" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-alt-primary mt-4">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</x-app-layout>
