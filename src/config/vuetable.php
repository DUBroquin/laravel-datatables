<?php

return [
    /**
     * vuetable search options.
     */
    'search'         => [
        /**
         * Smart search will enclose search keyword with wildcard string "%keyword%".
         * SQL: column LIKE "%keyword%"
         */
        'smart'            => true,

        /**
         * Case insensitive will search the keyword in lower case format.
         * SQL: LOWER(column) LIKE LOWER(keyword)
         */
        'case_insensitive' => true,

        /**
         * Wild card will add "%" in between every characters of the keyword.
         * SQL: column LIKE "%k%e%y%w%o%r%d%"
         */
        'use_wildcards'    => false,
    ],

    /**
     * vuetable internal index id response column name.
     */
    'index_column'   => 'DT_Row_Index',

    /**
     * vuetable fractal configurations.
     */
    'fractal'        => [
        /**
         * Request key name to parse includes on fractal.
         */
        'includes'   => 'include',

        /**
         * Default fractal serializer.
         */
        'serializer' => 'League\Fractal\Serializer\DataArraySerializer',
    ],

    /**
     * vuetable list of available engines.
     * This is where you can register your custom vuetable engine.
     */
    'engines'        => [
        'eloquent'   => dubroquin\vuetable\Engines\EloquentEngine::class,
        'query'      => dubroquin\vuetable\Engines\QueryBuilderEngine::class,
        'collection' => dubroquin\vuetable\Engines\CollectionEngine::class,
    ],

    /**
     * vuetable accepted builder to engine mapping.
     */
    'builders'       => [
        Illuminate\Database\Eloquent\Relations\Relation::class => 'eloquent',
        Illuminate\Database\Eloquent\Builder::class            => 'eloquent',
        Illuminate\Database\Query\Builder::class               => 'query',
        Illuminate\Support\Collection::class                   => 'collection',
    ],

    /**
     * Nulls last sql pattern for Posgresql & Oracle.
     * For MySQL, use '-%s %s'
     */
    'nulls_last_sql' => '%s %s NULLS LAST',

    /**
     * User friendly message to be displayed on user if error occurs.
     * Possible values:
     * null             - The exception message will be used on error response.
     * 'throw'          - Throws a \dubroquin\vuetable\Exception. You can then use your custom error handler if needed.
     * 'custom message' - Any friendly message to be displayed to the user. You can also use translation key.
     */
    'error'          => env('vuetable_ERROR', null),

    /**
     * JsonResponse header and options config.
     */
    'json'           => [
        'header'  => [],
        'options' => 0,
    ],

];
