<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Section;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $editor;
    private Item $item;
    private Section $section;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\CronogramaSeeder::class);
        $this->editor = User::factory()->create(['email' => 'dir@muci.org']);
        $this->section = Section::first();
        $this->item = Item::where('section_id', $this->section->id)->first();
    }

    public function test_guest_cannot_update_item(): void
    {
        $response = $this->patch(route('items.update', $this->item), [
            'completed' => true,
        ]);
        $response->assertRedirect('/auth/google');
        $this->assertDatabaseCount('item_updates', 0);
    }

    public function test_editor_can_mark_item_completed(): void
    {
        $response = $this->actingAs($this->editor)
            ->patch(route('items.update', $this->item), [
                'completed' => true,
                'comment' => null,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('item_updates', [
            'item_id'   => $this->item->id,
            'completed' => true,
        ]);
    }

    public function test_editor_can_add_comment(): void
    {
        $this->actingAs($this->editor)
            ->patch(route('items.update', $this->item), [
                'completed' => false,
                'comment' => 'Pendiente de revisión técnica',
            ]);

        $this->assertDatabaseHas('item_updates', [
            'item_id' => $this->item->id,
            'comment' => 'Pendiente de revisión técnica',
        ]);
    }

    public function test_editor_can_add_item_to_section(): void
    {
        $count = $this->section->items()->count();

        $this->actingAs($this->editor)
            ->post(route('items.store', $this->section), [
                'type' => 'meta',
                'text' => 'Nueva meta agregada en campo',
            ]);

        $this->assertEquals($count + 1, $this->section->fresh()->items()->count());
    }

    public function test_guest_cannot_add_item(): void
    {
        $count = Item::count();
        $this->post(route('items.store', $this->section), [
            'type' => 'meta',
            'text' => 'Intento no autorizado',
        ]);
        $this->assertEquals($count, Item::count());
    }

    public function test_editor_can_add_section_to_stage(): void
    {
        $stage = Stage::where('year', 2027)->first();
        $count = $stage->sections()->count();

        $this->actingAs($this->editor)
            ->post(route('sections.store', $stage), [
                'name' => 'Enero 2027',
            ]);

        $this->assertEquals($count + 1, $stage->fresh()->sections()->count());
    }
}
