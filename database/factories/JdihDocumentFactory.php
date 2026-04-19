<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\JdihDocument;
use App\Models\JdihCategory;
use App\Models\User;

class JdihDocumentFactory extends Factory
{
    protected $model = JdihDocument::class;

    public function definition(): array
    {
        return [
            'category_id' => JdihCategory::inRandomOrder()->first()->id ?? 1,
            'title' => $this->faker->sentence(6),
            'document_number' => $this->faker->numberBetween(1, 100) . ' Tahun ' . $this->faker->year(),
            'established_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['berlaku', 'berlaku', 'dicabut', 'draft']),
            'description' => $this->faker->paragraph(),
            'file_path' => null, // We won't generate dummy PDF files, just null
            'file_size' => $this->faker->numberBetween(100000, 5000000), // 100KB to 5MB
            'uploader_id' => User::where('role', 'operator')->first()->id ?? 1,
        ];
    }
}
