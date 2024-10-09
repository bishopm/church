<?php

namespace Bishopm\Church\Facades;

use Illuminate\Support\Facades\Facade;

class Church extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'church';
    }
}
