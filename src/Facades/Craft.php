<?php

namespace Ibis117\Craft\Facades;

use Illuminate\Support\Facades\Facade;

class Craft extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'craft';
    }
}
