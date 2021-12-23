<?php

namespace Tests\APIs;

use App\Models\Category;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Vendor;

class VendorTest extends TestCase
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
            '/api/v1/vendors'
        );

        $this->assertApiSuccess();
    }

    /**
     * @test
     */
    public function a_vendor_can_be_added()
    {
        $category = Category::create([
            'name' => 'Software'
        ]);

        $vendor = Vendor::factory()->make([
            'category_id' => $category->id
        ])->toArray();

        $this->response = $this->json('POST', '/api/v1/vendors', $vendor);
        $this->assertApiResponse($vendor);
    }


    /**
     * @test
     */
    public function a_vendor_details_can_be_viewed()
    {
        $category = Category::create([
            'name' => 'Software'
        ]);

        $vendor = Vendor::factory()->create([
            'category_id' => $category->id
        ]);

        $this->response = $this->json(
            'GET',
            '/api/v1/vendors/' . $vendor->id
        );
        $this->assertApiResponse($vendor->toArray());
    }

    /**
     * @test
     */
    public function a_vendor_can_be_updated()
    {
        $category = Category::create([
            'name' => 'Software'
        ]);

        $vendor = Vendor::factory()->create([
            'category_id' => $category->id
        ]);

        $this->response = $this->json(
            'PUT',
            '/api/v1/vendors/' . $vendor->id,
            [
                'name' => 'New Name'
            ]
        );

        $this->response->assertSeeText('Vendor Updated Successfully');
    }

    /**
     * @test
     */
    public function a_vendor_can_be_deleted()
    {
        $category = Category::create([
            'name' => 'Software'
        ]);

        $vendor = Vendor::factory()->create([
            'category_id' => $category->id
        ]);

        $this->response = $this->json(
            'DELETE',
            '/api/v1/vendors/' . $vendor->id
        );

        $this->assertApiSuccess();

        $this->response = $this->json(
            'GET',
            '/api/v1/vendors/' . $vendor->id
        );

        $this->response->assertStatus(404);
    }
}
