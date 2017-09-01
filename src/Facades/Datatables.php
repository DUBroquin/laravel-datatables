<?php

namespace dubroquin\vuetable;\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class vuetable.
 *
 * @package dubroquin\vuetable;\Facades
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
class vuetable extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'vuetable';
    }
}
