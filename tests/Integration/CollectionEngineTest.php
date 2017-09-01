<?php

namespace dubroquin\datatables\Tests\Integration;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use dubroquin\datatables\vuetable;
use dubroquin\datatables\Engines\CollectionEngine;
use dubroquin\datatables\Facades\vuetable as vuetableFacade;
use dubroquin\datatables\Tests\Models\User;
use dubroquin\datatables\Tests\TestCase;

class CollectionEngineTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_all_records_when_no_parameters_is_passed()
    {
        $crawler = $this->call('GET', '/collection/users');
        $crawler->assertJson([
            'draw'            => 0,
            'recordsTotal'    => 20,
            'recordsFiltered' => 20,
        ]);
    }

    /** @test */
    public function it_can_perform_global_search()
    {
        $crawler = $this->call('GET', '/collection/users', [
            'columns' => [
                ['data' => 'name', 'name' => 'name', 'searchable' => "true", 'orderable' => "true"],
                ['data' => 'email', 'name' => 'email', 'searchable' => "true", 'orderable' => "true"],
            ],
            'search'  => ['value' => 'Record 19'],
        ]);

        $crawler->assertJson([
            'draw'            => 0,
            'recordsTotal'    => 20,
            'recordsFiltered' => 1,
        ]);
    }

    /** @test */
    public function it_accepts_a_model_collection_using_of_factory()
    {
        $dataTable = vuetable::of(User::all());
        $response  = $dataTable->make(true);
        $this->assertInstanceOf(CollectionEngine::class, $dataTable);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /** @test */
    public function it_accepts_a_collection_using_of_factory()
    {
        $dataTable = vuetable::of(collect());
        $response  = $dataTable->make(true);
        $this->assertInstanceOf(CollectionEngine::class, $dataTable);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /** @test */
    public function it_accepts_a_model_collection_using_facade()
    {
        $dataTable = vuetableFacade::of(User::all());
        $response  = $dataTable->make(true);
        $this->assertInstanceOf(CollectionEngine::class, $dataTable);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /** @test */
    public function it_accepts_a_collection_using_facade()
    {
        $dataTable = vuetableFacade::of(collect());
        $response  = $dataTable->make(true);
        $this->assertInstanceOf(CollectionEngine::class, $dataTable);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /** @test */
    public function it_accepts_a_model_using_ioc_container()
    {
        $dataTable = app('vuetable')->collection(User::all());
        $response  = $dataTable->make(true);
        $this->assertInstanceOf(CollectionEngine::class, $dataTable);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /** @test */
    public function it_can_sort_case_insensitive_strings()
    {
        config()->set('app.debug', false);
        request()->merge([
            'columns' => [
                ['data' => 'name', 'name' => 'name', 'searchable' => "true", 'orderable' => "true"],
            ],
            'order'   => [["column" => 0, "dir" => "asc"]],
            'start'   => 0,
            'length'  => 10,
            'draw'    => 1,
        ]);

        $collection = collect([
            ['name' => 'ABC'],
            ['name' => 'BCD'],
            ['name' => 'ZXY'],
            ['name' => 'aaa'],
            ['name' => 'bbb'],
            ['name' => 'zzz'],
        ]);

        $dataTable = app('vuetable')->collection($collection);
        /** @var JsonResponse $response */
        $response = $dataTable->make('true');

        $this->assertEquals([
            'draw'            => 1,
            'recordsTotal'    => 6,
            'recordsFiltered' => 6,
            'data'            => [
                ['name' => 'aaa'],
                ['name' => 'ABC'],
                ['name' => 'bbb'],
                ['name' => 'BCD'],
                ['name' => 'ZXY'],
                ['name' => 'zzz'],
            ],
        ], $response->getData(true));
    }

    /** @test */
    public function it_accepts_a_model_using_ioc_container_factory()
    {
        $dataTable = app('vuetable')->of(User::all());
        $response  = $dataTable->make(true);
        $this->assertInstanceOf(CollectionEngine::class, $dataTable);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /** @test */
    public function it_accepts_array_data_source()
    {
        $source = [
            ['id' => 1, 'name' => 'foo'],
            ['id' => 2, 'name' => 'bar'],
        ];
        $dataTable = app('vuetable')->of($source);
        $response  = $dataTable->make(true);
        $this->assertInstanceOf(CollectionEngine::class, $dataTable);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->app['router']->get('/collection/users', function (vuetable $vuetable) {
            return $vuetable->collection(User::all())->make('true');
        });
    }
}
