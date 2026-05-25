<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DetailViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_detail_shows_all_stages(): void
    {
        $this->seed(\Database\Seeders\CronogramaSeeder::class);
        $response = $this->get('/tracker/detalle');
        $response->assertSeeText('Etapa 1');
        $response->assertSeeText('Etapa 2');
        $response->assertSeeText('Etapa 3');
    }

    public function test_detail_shows_section_names(): void
    {
        $this->seed(\Database\Seeders\CronogramaSeeder::class);
        $response = $this->get('/tracker/detalle');
        $response->assertSeeText('Marzo 2026');
        $response->assertSeeText('Mayo 2026');
    }

    public function test_detail_shows_item_text(): void
    {
        $this->seed(\Database\Seeders\CronogramaSeeder::class);
        $response = $this->get('/tracker/detalle');
        $response->assertSeeText('Inicio formal del proyecto paisajístico');
    }

    public function test_edit_controls_not_visible_to_guests(): void
    {
        $this->seed(\Database\Seeders\CronogramaSeeder::class);
        $response = $this->get('/tracker/detalle');
        $response->assertDontSee('name="completed"', false);
    }
}
