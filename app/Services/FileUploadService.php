<?php

    namespace App\Services;

    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Config;

    class FileUploadService
    {
        private $pathName;

        public function setPathName($pathName)
        {
            $this->pathName = $pathName;
            return $this;
        }

        public function setPath($photo)
        {
            $originalName = $photo->getClientOriginalName();
            $random = Str::random(25);
            return $random.$originalName;
        }

        public function uploadFile($fileName, $photo)
        {
            $destinationPath = storage_path('app/public') . DIRECTORY_SEPARATOR . 'postFiles';
            return $photo->move($destinationPath, $fileName);
        }
    }
