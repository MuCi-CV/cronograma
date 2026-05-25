<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackerRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_visual_page_is_accessible(): void
    {
        $this->seed(\Database\Seeders\CronogramaSeeder::class);
        $response = $this->get('/tracker');
        $response->assertOk();
        $response->assertViewIs('tracker.visual');
    }

    public function test_detail_page_is_accessible(): void
    {
        $this->seed(\Database\Seeders\CronogramaSeeder::class);
        $response = $this->get('/tracker/detalle');
        $response->assertOk();
        $response->assertViewIs('tracker.detail');
    }

    public function test_root_redirects_to_tracker(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/tracker');
    }

    public function test_visual_page_passes_stages_to_view(): void
    {
        $this->seed(\Database\Seeders\CronogramaSeeder::class);
        $response = $this->get('/tracker');
        $response->assertViewHas('stages');
    }
}
