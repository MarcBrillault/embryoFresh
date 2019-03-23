<?php

namespace App\Http\Controllers;

use App\Http\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Model;

class ImageController extends Controller
{
    use ImageTrait;

    const sizes = [
        'thumbnail' => [40, 40],
        'preview'   => [300, null],
    ];

    public function adminImage(string $objectName, string $size, $id)
    {
        $filename = $this->getFullPath($objectName, $id);
        list($width, $height) = $this::sizes[$size];

        return $this->image($filename, $width, $height);
    }

    private function getObject($objectName, $id): Model
    {
        $objectName = '\App\Models\\' . studly_case($objectName);

        return $objectName::where('id', $id)->orWhere($objectName::getAdminPathAttribute(), $id)->first();
    }

    private function getObjectAsStatic($objectName): Model
    {
        $objectName = '\App\Models\\' . studly_case($objectName);

        return new $objectName;
    }

    private function getFileName(Model $object): string
    {
        $pathAttribute = $object::getAdminPathAttribute();

        return (string) $object->$pathAttribute;
    }

    private function getFullImagePath(Model $object): string
    {
        return $object->getImageDir() . $this->getFileName($object);
    }

    private function getFullPath(string $objectName, $idOrFilename)
    {
        if (is_numeric($idOrFilename)) {
            $object = $this->getObject($objectName, $idOrFilename);

            return $this->getFullImagePath($object);
        } else {
            $object = $this->getObjectAsStatic($objectName);
            $object = new $object;

            return $object->getImageDir() . $idOrFilename;
        }
    }
}
