<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Manager as FractalManager;
use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\TransformerAbstract;

trait CollectionTrait
{
    /**
     * @param \Illuminate\Database\Eloquent\Collection $eloquentCollection
     * @param \League\Fractal\TransformerAbstract      $transformer
     * @return array
     */
    public static function getCollectionAsArray(
        EloquentCollection $eloquentCollection,
        TransformerAbstract $transformer
    ) {
        $collection = new FractalCollection($eloquentCollection, $transformer);
        $manager    = new FractalManager();
        $manager->setSerializer(new ArraySerializer());

        return $manager->createData($collection)->toArray();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $eloquentCollection
     * @param \League\Fractal\TransformerAbstract      $transformer
     * @return array
     */
    public static function getCollectionAsArrayWithoutData(
        EloquentCollection $eloquentCollection,
        TransformerAbstract $transformer
    ) {
        $array = self::getCollectionAsArray($eloquentCollection, $transformer);
        if (array_key_exists('data', $array)) {
            return $array['data'];
        }

        return $array;
    }
}