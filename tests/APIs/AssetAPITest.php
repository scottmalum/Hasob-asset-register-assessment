<?php

namespace Tests\APIs;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;
use App\Models\Vendor;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\ApiTestTrait;

class AssetAPITest extends TestCase
{
    // DatabaseTransactions
    use ApiTestTrait, WithoutMiddleware, RefreshDatabase;

    /**
     * @test
     */
    public function test_create_asset()
    {
        $category = Category::create(['name' => 'Hardware']);
        $location = Location::factory()->create();
        $vendor = Vendor::factory()->create([
            'category_id' => $category->id
        ]);

        $asset = Asset::factory()->make(
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'vendor_id' => $vendor->id
            ]
        )->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/assets',
            $asset
        );

        $this->assertApiSuccess();
        $this->response->assertSeeText('Asset saved successfully');
    }

    /**
     * @test
     */
    public function test_fetch_all_asset()
    {
        $category = Category::create(['name' => 'Hardware']);
        $location = Location::factory()->create();
        $vendor = Vendor::factory()->create([
            'category_id' => $category->id
        ]);

        Asset::factory()->make(
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'vendor_id' => $vendor->id
            ]
        )->toArray();


        $this->response = $this->json(
            'GET',
            '/api/v1/assets',
        );

        $this->assertApiSuccess();
        $this->response->assertSeeText('All Assets retrieved successfully');
    }

    /**
     * @test
     */
    public function test_fetch_asset()
    {
        $category = Category::create(['name' => 'Hardware']);
        $location = Location::factory()->create();
        $vendor = Vendor::factory()->create([
            'category_id' => $category->id
        ]);

        $asset = Asset::factory()->create(
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'vendor_id' => $vendor->id
            ]
        );

        $this->response = $this->json(
            'GET',
            "/api/v1/assets/{$asset->id}",
        );

        $this->assertApiSuccess();
        $this->response->assertSeeText('Asset Retrieved Successfully');
    }

    /**
     * @test
     */
    public function test_update_asset()
    {
        $category = Category::create(['name' => 'Hardware']);
        $location = Location::factory()->create();
        $vendor = Vendor::factory()->create([
            'category_id' => $category->id
        ]);

        $asset = Asset::factory()->create(
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'vendor_id' => $vendor->id
            ]
        );

        $this->response = $this->json(
            'PUT',
            '/api/v1/assets/' . $asset->id,
            [
                'name' => 'Excel',
                'description' => 'Something something',
            ]
        );

        $this->assertApiSuccess();
        $this->response->assertSeeText("Asset successfully updated");
    }

    /**
     * @test
     */
    public function test_delete_asset()
    {
        $category = Category::create(['name' => 'Hardware']);
        $location = Location::factory()->create();
        $vendor = Vendor::factory()->create([
            'category_id' => $category->id
        ]);

        $asset = Asset::factory()->create(
            [
                'category_id' => $category->id,
                'location_id' => $location->id,
                'vendor_id' => $vendor->id
            ]
        );

        $this->response = $this->json(
            'DELETE',
            '/api/v1/assets/' . $asset->id
        );

        $this->assertApiSuccess();
        $this->response->assertSeeText("Asset Deleted Successfully");
    }
}
