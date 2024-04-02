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
            $names = array();
            foreach($photo as $p) {
                $originalName = $p->getClientOriginalName();
                $random = Str::random(25);
                array_push($names,  $random.$originalName);
            }
            return $names;
        }

        public function uploadFile($fileName, $photo)
        {
            $i = 0;
            foreach($photo as $p) {
                $destinationPath = storage_path('app/public') . DIRECTORY_SEPARATOR . 'postFiles';
                $p->move($destinationPath, $fileName[$i++]);
            }
            return true;
        }
    }
