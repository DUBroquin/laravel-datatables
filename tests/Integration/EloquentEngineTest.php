<?php

namespace dubroquin\vuetable\Tests\Integration;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use dubroquin\vuetable\vuetable;
use dubroquin\vuetable\Engines\EloquentEngine;
use dubroquin\vuetable\Facades\vuetable as vuetableFacade;
use dubroquin\vuetable\Tests\Models\User;
use dubroquin\vuetable\Tests\TestCase;

class EloquentEngineTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_all_records_when_no_parameters_is_passed()
    {
        $crawler = $this->call('GET', '/eloquent/users');
        $crawler->assertJson([
            'draw'            => 0,
            'recordsTotal'    => 20,
            'recordsFiltered' => 20,
        ]);
    }

    /** @test */
    public function it_can_perform_global_search()
    {
        $crawler = $this->call('GET', '/eloquent/users', [
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
    public function it_accepts_a_model_using_of_factory()
    {
        $dataTable = vuetable::of(User::query());
        $response  = $dataTable->make(true);
        $this->assertInstanceOf(EloquentEngine::class, $dataTable);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /** @test */
    public function it_accepts_a_model_using_facade()
    {
        $dataTable = vuetableFacade::of(User::query());
        $response  = $dataTable->make(true);
        $this->assertInstanceOf(EloquentEngine::class, $dataTable);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /** @test */
    public function it_accepts_a_model_using_facade_eloquent_method()
    {
        $dataTable = vuetableFacade::eloquent(User::query());
        $response  = $dataTable->make(true);
        $this->assertInstanceOf(EloquentEngine::class, $dataTable);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /** @test */
    public function it_accepts_a_model_using_ioc_container()
    {
        $dataTable = app('vuetable')->eloquent(User::query());
        $response  = $dataTable->make(true);
        $this->assertInstanceOf(EloquentEngine::class, $dataTable);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /** @test */
    public function it_accepts_a_model_using_ioc_container_factory()
    {
        $dataTable = app('vuetable')->of(User::query());
        $response  = $dataTable->make(true);
        $this->assertInstanceOf(EloquentEngine::class, $dataTable);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->app['router']->get('/eloquent/users', function (vuetable $vuetable) {
            return $vuetable->eloquent(User::query())->make('true');
        });
    }
}
