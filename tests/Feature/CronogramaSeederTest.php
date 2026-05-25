<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CronogramaSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_three_stages(): void
    {
        $this->seed(\Database\Seeders\CronogramaSeeder::class);
        $this->assertDatabaseCount('stages', 3);
    }

    public function test_seeder_creates_ten_sections_for_2026(): void
    {
        $this->seed(\Database\Seeders\CronogramaSeeder::class);
        $stage = \App\Models\Stage::where('year', 2026)->first();
        $this->assertCount(10, $stage->sections);
    }

    public function test_seeder_creates_items_for_marzo(): void
    {
        $this->seed(\Database\Seeders\CronogramaSeeder::class);
        $section = \App\Models\Section::where('name', 'Marzo 2026')->first();
        $this->assertNotNull($section);
        $this->assertGreaterThan(0, $section->items()->count());
    }

    public function test_all_items_are_inactive_by_default(): void
    {
        $this->seed(\Database\Seeders\CronogramaSeeder::class);
        $this->assertDatabaseCount('item_updates', 0);
    }
}
