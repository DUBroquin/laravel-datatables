<?php

namespace dubroquin\vuetable;

use Illuminate\Support\Collection;

/**
 * Class Datatables.
 *
 * @package dubroquin\datatables
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
class Vuetable
{
    /**
     * Datatables request object.
     *
     * @var \dubroquin\datatables\Request
     */
    protected $request;

    /**
     * HTML builder instance.
     *
     * @var \dubroquin\datatables\Html\Builder
     */
    protected $html;

    /**
     * Datatables constructor.
     *
     * @param \dubroquin\datatables\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Gets query and returns instance of class.
     *
     * @param  mixed $source
     * @return mixed
     * @throws \Exception
     */
    public static function of($source)
    {
        $datatables = app('datatables');
        $config     = app('config');
        $engines    = $config->get('datatables.engines');
        $builders   = $config->get('datatables.builders');

        if (is_array($source)) {
            $source = new Collection($source);
        }

        foreach ($builders as $class => $engine) {
            if ($source instanceof $class) {
                $class = $engines[$engine];

                return new $class($source, $datatables->getRequest());
            }
        }

        throw new \Exception('No available engine for ' . get_class($source));
    }

    /**
     * Get request object.
     *
     * @return \dubroquin\datatables\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Datatables using Query Builder.
     *
     * @param \Illuminate\Database\Query\Builder|mixed $builder
     * @return \dubroquin\datatables\Engines\QueryBuilderEngine
     */
    public function queryBuilder($builder)
    {
        return new Engines\QueryBuilderEngine($builder, $this->request);
    }

    /**
     * Datatables using Eloquent Builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder|mixed $builder
     * @return \dubroquin\datatables\Engines\EloquentEngine
     */
    public function eloquent($builder)
    {
        return new Engines\EloquentEngine($builder, $this->request);
    }

    /**
     * Datatables using Collection.
     *
     * @param \Illuminate\Support\Collection|mixed $collection
     * @return \dubroquin\datatables\Engines\CollectionEngine
     */
    public function collection($collection)
    {
        if (is_array($collection)) {
            $collection = new Collection($collection);
        }

        return new Engines\CollectionEngine($collection, $this->request);
    }

    /**
     * Get html builder instance.
     *
     * @return \dubroquin\datatables\Html\Builder
     * @throws \Exception
     */
    public function getHtmlBuilder()
    {
        if (! class_exists('\dubroquin\datatables\Html\Builder')) {
            throw new \Exception('Please install yajra/laravel-datatables-html to be able to use this function.');
        }

        return $this->html ?: $this->html = app('datatables.html');
    }
}
