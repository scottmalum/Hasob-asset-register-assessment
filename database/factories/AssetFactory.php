<?php

namespace Database\Factories;

use App\Models\Asset;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Asset::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'serial' => $this->faker->randomNumber(),
            'description' => $this->faker->text,
            'quantity' => 1,
            'purchase_price' => $this->faker->randomFloat(),
            'purchase_date' => Carbon::parse('03/19/2021'),
            'warranty_exp_date' =>  '1 month',
            'status' => 'unassigned',
            'vendor_id' => 1,
            'category_id' => 1,
            'location_id' => 1
        ];
    }
}
