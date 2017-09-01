<?php

namespace dubroquin\datatables\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class vuetable.
 *
 * @package dubroquin\datatables\Facades
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
class Vuetable extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Vuetable';
    }
}
