<?php

namespace dubroquin\datatables\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Datatables.
 *
 * @package dubroquin\datatables\Facades
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
class Datatables extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'datatables';
    }
}
