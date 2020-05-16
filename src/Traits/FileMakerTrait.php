<?php

namespace Luissobrinho\Builder\Traits;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;

trait FileMakerTrait
{

    public function copyPreparedFiles($directory, $destination)
    {
        $fileSystem = new Filesystem();

        $files = $fileSystem->allFiles($directory);

        $fileDeployed = false;

        $fileSystem->copyDirectory($directory, $destination);

        foreach ($files as $file) {
            $fileContents = $fileSystem->get($file);
            $fileContentsPrepared = str_replace('App\\', $this->getLaravel()->getNamespace(), $fileContents);
            $fileDeployed = $fileSystem->put($destination.'/'.$file->getRelativePathname(), $fileContentsPrepared);
        }

        return $fileDeployed;
    }
}
