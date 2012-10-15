<?php

class ContentHandler extends Page {

    public function showFullPostImage() {
        $args = func_get_arg(0);
        $basePath = $args[0].'/'.$args[1].'/'.$args[2].'/'.$args[3];
        $sourcePath = '../upload/'.$basePath;
        $destPath = 'image/full/'.$basePath;
        if(!file_exists($destPath)) {
            @mkdir(dirname($destPath), 0777, true);
            @copy($sourcePath, $destPath);
        }
        $this->getSlim()->response()->header('Content-Type', 'image/jpeg');
        echo file_get_contents($sourcePath);
    }

    public function showSmallPostImage() {
        $args = func_get_arg(0);
        $basePath = $args[0].'/'.$args[1].'/'.$args[2].'/'.$args[3];
        $sourcePath = '../upload/'.$basePath;
        $destPath = 'image/small/'.$basePath;
        if(!file_exists($destPath)) {
            @mkdir(dirname($destPath), 0777, true);
            $sourceImage = imagecreatefromjpeg($sourcePath);
            $sizes = getimagesize($sourcePath);

            if($sizes[0] > $sizes[1]) {
                if($sizes[0] > 650) {
                    $width = 650;
                    $height = ($width / $sizes[0]) * $sizes[1];
                }
                else {
                    $width = $sizes[0];
                    $height = $sizes[1];
                }
            }
            else {
                if($sizes[1] > 650) {
                    $height = 650;
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
        }
        $this->getSlim()->response()->header('Content-Type', 'image/jpeg');
        echo file_get_contents($destPath);
    }

}
