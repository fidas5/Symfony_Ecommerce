<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PhotoService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function addPhoto(UploadedFile $photo, ?string $folder = '', ?int $width = 300, ?int $height = 300)
    {
        $file = md5(uniqid(rand(), true)) . '.jpeg';

        $photoInfo = getimagesize($photo);

        if($photoInfo === false){
            throw new \Exception('Format not supported');
        }

        switch($photoInfo['mime']){
            case 'image/png':
                $photoFormat = imagecreatefrompng($photo);
                break;
            case 'image/jpeg':
                $photoFormat = imagecreatefromjpeg($photo);
                break;
            case 'image/webp':
                $photoFormat = imagecreatefromwebp($photo);
                break;
            default:
                throw new \Exception('Format not supported');
        }

        $photoWidth = $photoInfo[0];
        $photoHeight = $photoInfo[1];

        switch($photoWidth <=> $photoHeight){
            case -1: // Portrait size
                $square = $photoWidth;
                $x = 0;
                $y = ($photoHeight - $square) / 2;
                break;
            case 0: // Square size
                $square = $photoWidth;
                $x = 0;
                $y = 0;
                break;
            case 1: // Landscape size
                $square = $photoHeight;
                $x = ($photoWidth - $square) / 2;
                $y = 0;
                break;
        }

        $resizedPhoto = imagecreatetruecolor($width, $height);
        imagecopyresampled($resizedPhoto, $photoFormat, 0, 0, $x, $y, $width, $height, $square, $square);

        $path = $this->params->get('photo_directory') . $folder;

        if(!file_exists($path . '/cover/')){
            mkdir($path . '/cover/', 0755, true);
        }

        imagejpeg($resizedPhoto, $path . '/cover/' . $width . 'x' . $height . '-' . $file);

        $photo->move($path. '/', $file);

        return $file;
    }

    public function deletePhoto(string $file, ?string $folder = '', ?int $width = 300, ?int $height = 300)
    {
        if($file !== 'default.jpeg'){
            $success = false;
            $path = $this->params->get('photo_directory') . $folder;

            $cover = $path . '/cover/' . $width . 'x' . $height . '-' . $file;

            if(file_exists($cover)){
                unlink($cover);
                $success = true;
            }

            $original = $path . '/' . $file;

            if(file_exists($original)){
                unlink($original);
                $success = true;
            }
            return $success;
        }
        return false;
    }
}