<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Create Post
            </h2>
            <div>
                <a href="{{ route('post.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded " style="text-decoration: none;">
                    Show All Post
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container justify-content-center p-6">
        <div class="row">
            <div class="col-sm-12" >
                <div class="card text-bg-light" style="padding: 5rem">
                    <div class="card-header">
                        <h3 class="card-title">Create a Post</h3>
                    </div>

                    <form class="needs-validation" action="{{ route('post.store') }}" method="POST" id="form" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="title" class="form-label">Title<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="content" class="form-label">Content<span class="text-danger">*</span></label>
                                <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="photo" class="form-label">Select File</label><br>
                                <input type="file" class="form-control" name="photo[]" id="photo[]" multiple><br>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center align-items-center bg-light">
                            <button type="submit" class="btn btn-primary ">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
