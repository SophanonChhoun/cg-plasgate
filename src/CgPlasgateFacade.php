<?php


namespace Sophanon\Plasgate;


use Illuminate\Support\Facades\Facade;

class CgPlasgateFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'plasgate';
    }
}
