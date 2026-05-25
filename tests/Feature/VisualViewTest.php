<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisualViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_visual_page_loads(): void
    {
        $this->seed(\Database\Seeders\CronogramaSeeder::class);
        $response = $this->get('/tracker');
        $response->assertOk();
    }

    public function test_visual_page_contains_month_names(): void
    {
        $this->seed(\Database\Seeders\CronogramaSeeder::class);
        $response = $this->get('/tracker');
        $response->assertSeeText('Mayo');
        $response->assertSeeText('Junio');
    }

    public function test_visual_page_has_view_toggle(): void
    {
        $this->seed(\Database\Seeders\CronogramaSeeder::class);
        $response = $this->get('/tracker');
        $response->assertSee('data-view="carousel"', false);
        $response->assertSee('data-view="timeline"', false);
    }
}
