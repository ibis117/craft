<?php
namespace Ibis117\Craft\Commands;

/**
 * Replace Command Trait
 */
trait CommandHelper
{

    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }
    protected function replaceNamespace(&$stub, $with)
    {
        $for = '{{ namespace }}';
        $stub = $this->replace($stub, $for, $with);
        return $this;
    }

    protected function replaceModelNamespace(&$stub, $with)
    {
        $for = '{{ model_namespace }}';
        $stub = $this->replace($stub, $for, $with);
        return $this;
    }

    protected function replaceClassName(&$stub, $with)
    {
        $for = '{{ class }}';
        $stub = $this->replace($stub, $for, $with);
        return $this;
    }

    protected function replaceModel(&$stub, $with)
    {
        $for = '{{ model }}';
        $stub = $this->replace($stub, $for, $with);
        return $this;
    }

    protected function replaceSingular(&$stub, $with)
    {
        $for = '{{ singular }}';
        $stub = $this->replace($stub, $for, $with);
        return $this;
    }

    protected function replacePlural(&$stub, $with)
    {
        $for = '{{ plural }}';
        $stub = $this->replace($stub, $for, $with);
        return $this;
    }

    protected function replace(&$stub, $for, $with)
    {
        return str_replace($for, $with, $stub);
    }

}
