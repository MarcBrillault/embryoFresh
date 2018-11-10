<?php

namespace App\Http\Traits;

use Intervention\Image\Constraint;
use Intervention\Image\ImageCache;

trait ImageTrait
{
    public function image(string $path, int $width = null, int $height = null)
    {
        return \Image::cache(function (ImageCache $image) use ($path, $width, $height): ImageCache {
            try {
                $fullPath = \Storage::get($path);
                $image    = $image->make($fullPath);
            } catch (\Exception $e) {
                $image = $this->getDefaultImage();
            }

            if ($width && $height) {
                return $image
                    ->fit($width, $height, function (Constraint $constraint) {
                        $constraint->aspectRatio();
                    });
            } else {
                return $image
                    ->resize($width, $height, function (Constraint $constraint) {
                        $constraint->aspectRatio();
                    });
            }
        }, 60 * 24, true)->response();
    }

    private function getDefaultImage()
    {
        $image = \Image::canvas(800, 800, '#F00');

        return $image;
    }
}