<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeTransformer extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:transformer';

    protected $type = '';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new transformer class';

    protected function getStub()
    {
        return app_path() . '/Console/Stubs/Transformer.stub';
    }

    /**
     * The root location the file should be written to
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Transformers';
    }
}
