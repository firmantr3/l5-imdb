<?php

namespace firmantr3\L5Imdb\Facades;

use Illuminate\Support\Facades\Facade;

class L5Imdb extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'l5imdb';
    }
}
