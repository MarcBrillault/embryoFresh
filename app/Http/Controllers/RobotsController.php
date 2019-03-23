<?php

namespace App\Http\Controllers;

use App\Http\Traits\PlatformTrait;
use Illuminate\Http\Response;

class RobotsController extends Controller
{
    use PlatformTrait;

    private $devFile  = 'robots/dev.txt';
    private $prodFile = 'robots/prod.txt';

    /**
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        $file = $this->isDev() ? $this->devFile : $this->prodFile;
        try {
            $fileContents = \Storage::get($file);
        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
            $fileContents = '';
        }

        $headers = [
            'Content-Type' => 'text/plain',
        ];

        return \Response::make($fileContents, 200, $headers);
    }
}
