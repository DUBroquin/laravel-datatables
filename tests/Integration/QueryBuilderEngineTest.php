<?php

namespace dubroquin\vuetable\Tests\Integration;

use DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use dubroquin\vuetable\vuetable;
use dubroquin\vuetable\Engines\QueryBuilderEngine;
use dubroquin\vuetable\Facades\vuetable as vuetableFacade;
use dubroquin\vuetable\Tests\TestCase;

class QueryBuilderEngineTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_all_records_when_no_parameters_is_passed()
    {
        $crawler = $this->call('GET', '/queryBuilder/users');
        $crawler->assertJson([
            'draw'            => 0,
            'recordsTotal'    => 20,
            'recordsFiltered' => 20,
        ]);
    }

    /** @test */
    public function it_can_perform_global_search()
    {
        $crawler = $this->call('GET', '/queryBuilder/users', [
            'columns' => [
                ['data' => 'name', 'name' => 'name', 'searchable' => "true", 'orderable' => "true"],
                ['data' => 'email', 'name' => 'email', 'searchable' => "true", 'orderable' => "true"],
            ],
            'search'  => ['value' => 'Record-19'],
        ]);

        $crawler->assertJson([
            'draw'            => 0,
            'recordsTotal'    => 20,
            'recordsFiltered' => 1,
        ]);
    }

    /** @test */
    public function it_can_perform_multiple_term_global_search()
    {
        $crawler = $this->call('GET', '/queryBuilder/users', [
            'columns' => [
                ['data' => 'name', 'name' => 'name', 'searchable' => "true", 'orderable' => "true"],
                ['data' => 'email', 'name' => 'email', 'searchable' => "true", 'orderable' => "true"],
            ],
            'search'  => ['value' => 'Record-19 Email-19'],
        ]);

        $crawler->assertJson([
            'draw'            => 0,
            'recordsTotal'    => 20,
            'recordsFiltered' => 1,
        ]);
    }

    /** @test */
    public function it_accepts_a_query_builder_using_of_factory()
    {
        $dataTable = vuetable::of(DB::table('users'));
        $response  = $dataTable->make(true);
        $this->assertInstanceOf(QueryBuilderEngine::class, $dataTable);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /** @test */
    public function it_accepts_a_query_builder_using_facade()
    {
        $dataTable = vuetableFacade::of(DB::table('users'));
        $response  = $dataTable->make(true);
        $this->assertInstanceOf(QueryBuilderEngine::class, $dataTable);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /** @test */
    public function it_accepts_a_query_builder_using_facade_queryBuilder_method()
    {
        $dataTable = vuetableFacade::queryBuilder(DB::table('users'));
        $response  = $dataTable->make(true);
        $this->assertInstanceOf(QueryBuilderEngine::class, $dataTable);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /** @test */
    public function it_accepts_a_query_builder_using_ioc_container()
    {
        $dataTable = app('vuetable')->queryBuilder(DB::table('users'));
        $response  = $dataTable->make(true);
        $this->assertInstanceOf(QueryBuilderEngine::class, $dataTable);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /** @test */
    public function it_accepts_a_query_builder_using_ioc_container_factory()
    {
        $dataTable = app('vuetable')->of(DB::table('users'));
        $response  = $dataTable->make(true);
        $this->assertInstanceOf(QueryBuilderEngine::class, $dataTable);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /** @test */
    public function it_does_not_allow_search_on_added_columns()
    {
        $crawler = $this->call('GET', '/queryBuilder/addColumn', [
            'columns' => [
                ['data' => 'foo', 'name' => 'foo', 'searchable' => "true", 'orderable' => "true"],
                ['data' => 'name', 'name' => 'name', 'searchable' => "true", 'orderable' => "true"],
                ['data' => 'email', 'name' => 'email', 'searchable' => "true", 'orderable' => "true"],
            ],
            'search'  => ['value' => 'Record-19'],
        ]);

        $crawler->assertJson([
            'draw'            => 0,
            'recordsTotal'    => 20,
            'recordsFiltered' => 1,
        ]);
    }

    /** @test */
    public function it_can_return_auto_index_column()
    {
        $crawler = $this->call('GET', '/queryBuilder/indexColumn', [
            'columns' => [
                ['data' => 'DT_Row_index', 'name' => 'index', 'searchable' => "false", 'orderable' => "false"],
                ['data' => 'name', 'name' => 'name', 'searchable' => "true", 'orderable' => "true"],
                ['data' => 'email', 'name' => 'email', 'searchable' => "true", 'orderable' => "true"],
            ],
            'search'  => ['value' => 'Record-19'],
        ]);

        $crawler->assertJson([
            'draw'            => 0,
            'recordsTotal'    => 20,
            'recordsFiltered' => 1,
        ]);

        $this->assertArrayHasKey('DT_Row_Index', $crawler->json()['data'][0]);
    }

    /** @test */
    public function it_allows_search_on_added_column_with_custom_filter_handler()
    {
        $crawler = $this->call('GET', '/queryBuilder/filterColumn', [
            'columns' => [
                ['data' => 'foo', 'name' => 'foo', 'searchable' => "true", 'orderable' => "true"],
                ['data' => 'name', 'name' => 'name', 'searchable' => "true", 'orderable' => "true"],
                ['data' => 'email', 'name' => 'email', 'searchable' => "true", 'orderable' => "true"],
            ],
            'search'  => ['value' => 'Record-19'],
        ]);

        $crawler->assertJson([
            'draw'            => 0,
            'recordsTotal'    => 20,
            'recordsFiltered' => 1,
        ]);

        $queries = $crawler->json()['queries'];
        $this->assertContains('"1" = ?', $queries[1]['query']);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->app['router']->get('/queryBuilder/users', function (vuetable $dataTable) {
            return $dataTable->queryBuilder(DB::table('users'))->make('true');
        });

        $this->app['router']->get('/queryBuilder/addColumn', function (vuetable $dataTable) {
            return $dataTable->queryBuilder(DB::table('users'))
                             ->addColumn('foo', 'bar')
                             ->make('true');
        });

        $this->app['router']->get('/queryBuilder/indexColumn', function (vuetable $dataTable) {
            return $dataTable->queryBuilder(DB::table('users'))
                             ->addIndexColumn()
                             ->make('true');
        });

        $this->app['router']->get('/queryBuilder/filterColumn', function (vuetable $dataTable) {
            return $dataTable->queryBuilder(DB::table('users'))
                             ->addColumn('foo', 'bar')
                             ->filterColumn('foo', function (Builder $builder, $keyword) {
                                 $builder->where('1', $keyword);
                             })
                             ->make('true');
        });
    }
}
