<?php

class ContentHandler extends Section {

    const PreviewRecangle = 750;

    public function showFullPostImage() {
        $args = func_get_arg(0);
        $basePath = $args[0].'/'.$args[1].'/'.$args[2].'/'.$args[3];
        $sourcePath = '../upload/'.$basePath;
        $destPath = 'image/full/'.$basePath;
        $this->showPostImage($destPath, $sourcePath, 1280);
    }

    public function showSmallPostImage() {
        $args = func_get_arg(0);
        $basePath = $args[0].'/'.$args[1].'/'.$args[2].'/'.$args[3];
        $sourcePath = '../upload/'.$basePath;
        $destPath = 'image/small/'.$basePath;
        $this->showPostImage($destPath, $sourcePath, self::PreviewRecangle);
    }

    private function showPostImage($destPath, $sourcePath, $maxSize) {
        if(file_exists($sourcePath)) {
            @mkdir(dirname($destPath), 0777, true);
            $sourceImage = imagecreatefromjpeg($sourcePath);
            $sizes = getimagesize($sourcePath);

            if($sizes[0] > $sizes[1]) {
                if($sizes[0] > $maxSize) {
                    $width = $maxSize;
                    $height = ($width / $sizes[0]) * $sizes[1];
                }
                else {
                    $width = $sizes[0];
                    $height = $sizes[1];
                }
            }
            else {
                if($sizes[1] > $maxSize) {
                    $height = $maxSize;
                    $width = ($height / $sizes[1]) * $sizes[0];
                }
                else {
                    $width = $sizes[0];
                    $height = $sizes[1];
                }
            }
            $destImage = imagecreatetruecolor($width, $height);
            imagecopyresampled($destImage, $sourceImage, 0, 0, 0, 0, $width, $height, $sizes[0], $sizes[1]);
            imagedestroy($sourceImage);
            imagejpeg($destImage, $destPath, 90);
            imagedestroy($destImage);
            $this->getSlim()->response()->header('Content-Type', 'image/jpeg');
            echo file_get_contents($destPath);
        }
        else {
            $this->getSlim()->response()->status(404);

            return;
        }
    }

}
