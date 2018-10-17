<?php

namespace App\Http\Traits;

use Intervention\Image\Constraint;

trait ImageTrait
{
    public function image(string $path, int $width = null, int $height = null)
    {
        try {
            $fullPath = \Storage::get($path);
            $image    = \Image::make($fullPath);
        } catch (\Exception $e) {
            $image = $this->getDefaultImage();
        }

        if ($width && $height) {
            return $image
                ->fit($width, $height, function (Constraint $constraint) {
                    $constraint->aspectRatio();
                })
                ->response();
        } else {
            return $image
                ->resize($width, $height, function (Constraint $constraint) {
                    $constraint->aspectRatio();
                })
                ->response();
        }
    }

    private function getDefaultImage()
    {
        $image = \Image::canvas(800, 800, '#F00');

        return $image;
    }
}