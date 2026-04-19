<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\InformasiPublik;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InformasiPublikFactory extends Factory
{
    protected $model = InformasiPublik::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement([InformasiPublik::TYPE_PENGUMUMAN, InformasiPublik::TYPE_AGENDA]);
        $title = $this->faker->sentence;
        $start = Carbon::now()->addDays($this->faker->numberBetween(-10, 20));

        return [
            'type' => $type,
            'title' => $title,
            'slug' => Str::slug($title) . '-' . mt_rand(1000, 9999),
            'content_html' => '<p>' . implode('</p><p>', $this->faker->paragraphs(3)) . '</p>',
            'start_date' => $start,
            'end_date' => $type == InformasiPublik::TYPE_PENGUMUMAN ? (clone $start)->addDays($this->faker->numberBetween(1, 14)) : ($this->faker->boolean ? (clone $start)->addHours(4) : null),
            'location' => $type == InformasiPublik::TYPE_AGENDA ? $this->faker->address : null,
            'status' => $this->faker->randomElement([InformasiPublik::STATUS_PUBLISH, InformasiPublik::STATUS_DRAFT, InformasiPublik::STATUS_ARCHIVED]),
        ];
    }
}
