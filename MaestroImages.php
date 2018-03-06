<?php

class MaestroImages
{
    public function resize($input_image, $new_width, $new_height, $quality, $output_image)
    {
        list($width, $height) = getimagesize($input_image);
        // Resample
        $image_p = imagecreatetruecolor($new_width, $new_height);
        $image = imagecreatefromjpeg($input_image);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        // Output the scaled image
        imagejpeg($image_p, $output_image, $quality);
    }

    public function crop($input_image, $x, $y, $w, $h, $quality, $output_image)
    {
        $image = imagecreatefromjpeg($input_image);
        $image = @imagecrop($image, ['x' => $x, 'y' => $y, 'width' => $w, 'height' => $h]);
        imagejpeg($image, $output_image, $quality);
    }

    public function mask($input_image, $mask_path, $quality, $output_image)
    {
        $mask_path = imagecreatefrompng($mask_path);
        $input_image = imagecreatefromjpeg($input_image);
        $xSize = imagesx($input_image);
        $ySize = imagesy($input_image);
        $newPicture = imagecreatetruecolor($xSize, $ySize);
        imagesavealpha($newPicture, true);
        imagefill($newPicture, 0, 0, imagecolorallocatealpha($newPicture, 0, 0, 0, 127));
        if ($xSize != imagesx($mask_path) || $ySize != imagesy($mask_path)) {
            $tempPic = imagecreatetruecolor($xSize, $ySize);
            imagecopyresampled($tempPic, $mask_path, 0, 0, 0, 0, $xSize, $ySize, imagesx($mask_path), imagesy($mask_path));
            imagedestroy($mask_path);
            $mask_path = $tempPic;
        }
        for ($x = 0; $x < $xSize; $x++) {
            for ($y = 0; $y < $ySize; $y++) {
                $alpha = imagecolorsforindex($mask_path, imagecolorat($mask_path, $x, $y));
                $alpha = 127 - floor($alpha['red'] / 2);
                $color = imagecolorsforindex($input_image, imagecolorat($input_image, $x, $y));
                imagesetpixel($newPicture, $x, $y, imagecolorallocatealpha($newPicture, $color['red'], $color['green'], $color['blue'], $alpha));
            }
        }
        imagepng($newPicture, $output_image, $quality);
    }

}


