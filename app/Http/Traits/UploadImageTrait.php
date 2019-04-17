<?php

namespace App\Http\Traits;

trait UploadImageTrait
{
    public static function getImageDir(): string
    {
        if (defined('self::IMAGE_DIR')) {
            return rtrim(self::IMAGE_DIR, '/') . '/';
        }

        return '/';
    }

    public static function getAdminPathAttribute(): string
    {
        if (defined('self::ADMIN_PATH_ATTRIBUTE')) {
            return self::ADMIN_PATH_ATTRIBUTE;
        }

        return '';
    }

    public function uploadImage($imageContent, string $attributeName)
    {
        // if the image was erased
        if ($imageContent == null) {
            // delete the image from disk
            \Storage::delete($this->{$attributeName});

            // set null in the database column
            $this->attributes[$attributeName] = null;
        }

        // if a base64 was sent, store it in the db
        if (starts_with($imageContent, 'data:image')) {
            // 0. Make the image
            $image = \Image::make($imageContent);
            // 1. Generate a filename.
            $filename = md5($imageContent . time()) . '.jpg';
            // 2. Store the image on disk.
            \Storage::put(self::getImageDir() . '/' . $filename, $image->stream());
            // 3. Save the path to the database
            $this->attributes[$attributeName] = $filename;
        } elseif (strlen($imageContent) <= 255) {
            // If the content is a single string, that means we've got a filename
            $this->attributes[$attributeName] = basename($imageContent);
        }
    }

    private function getAdminImage(string $imageType)
    {
        $pattern = '<img src="%s" />';

        return sprintf($pattern, route('admin_image',
            [
                'type' => strtolower((new \ReflectionClass($this))->getShortName()),
                'id'   => $this->attributes['id'],
                'size' => $imageType,
            ]
        ));
    }

    public function getAdminImageAttribute()
    {
        return $this->getAdminImage('thumbnail');
    }

    /**
     * Returns the thumbnail image to be displayed on the admin interfaces
     *
     * @return string
     */
    public function getAdminThumbnail()
    {
        return $this->getAdminImage('thumbnail');
    }

    public function getAdminPreview()
    {
        return $this->getAdminImage('preview');
    }
}