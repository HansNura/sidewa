<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        return [
            'category_id' => ProductCategory::inRandomOrder()->first()->id ?? 1,
            'name' => ucwords($name),
            'slug' => Str::slug($name) . '-' . mt_rand(100, 999),
            'price' => $this->faker->numberBetween(5000, 200000),
            'stock' => $this->faker->boolean(70) ? $this->faker->numberBetween(1, 150) : null,
            'seller_name' => $this->faker->name,
            'seller_phone' => '08' . $this->faker->numberBetween(100000000, 999999999), // Will be mutated to 628
            'description_html' => '<p>' . implode('</p><p>', $this->faker->paragraphs(2)) . '</p>',
            'status' => $this->faker->randomElement(['aktif', 'nonaktif']),
        ];
    }
}
