<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Location;

class LocationAPITest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @test
     */
    public function api_can_be_accessed()
    {
        $this->response = $this->json(
            'GET',
            '/api/v1/locations'
        );

        $this->assertApiSuccess();
    }

    /**
     * @test
     */
    public function a_location_can_be_added()
    {
        $location = Location::factory()->make()->toArray();
        $this->response = $this->json('POST', '/api/v1/locations', $location);

        $this->assertApiResponse($location);
    }


    /**
     * @test
     */
    public function a_location_details_can_be_viewed()
    {
        $location = Location::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/locations/' . $location->id
        );
        $this->assertApiResponse($location->toArray());
    }

    /**
     * @test
     */
    public function a_location_can_be_updated()
    {
        $location = Location::factory()->create();
        $editedLocation = Location::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/locations/' . $location->id,
            $editedLocation
        );

        $this->assertApiResponse($editedLocation);
    }

    /**
     * @test
     */
    public function a_location_can_be_deleted()
    {
        $location = Location::factory()->create();
        $this->response = $this->json(
            'DELETE',
            '/api/v1/locations/' . $location->id
        );

        $this->assertApiSuccess();

        $this->response = $this->json(
            'GET',
            '/api/v1/vendors/' . $location->id
        );

        $this->response->assertStatus(404);
    }
}
