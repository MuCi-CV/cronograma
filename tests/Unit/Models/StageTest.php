<?php

namespace Tests\Unit\Models;

use App\Models\Item;
use App\Models\ItemUpdate;
use App\Models\Section;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StageTest extends TestCase
{
    use RefreshDatabase;

    public function test_stage_has_sections(): void
    {
        $stage = Stage::factory()->create();
        Section::factory()->count(3)->create(['stage_id' => $stage->id]);

        $this->assertCount(3, $stage->sections);
    }

    public function test_progress_pct_returns_zero_when_no_items(): void
    {
        $stage = Stage::factory()->create();
        Section::factory()->create(['stage_id' => $stage->id]);

        $this->assertEquals(0, $stage->progressPct());
    }

    public function test_progress_pct_calculates_correctly(): void
    {
        $user = User::factory()->create();
        $stage = Stage::factory()->create();
        $section = Section::factory()->create(['stage_id' => $stage->id]);
        $item1 = Item::factory()->create(['section_id' => $section->id]);
        $item2 = Item::factory()->create(['section_id' => $section->id]);

        ItemUpdate::factory()->create([
            'item_id' => $item1->id,
            'user_id' => $user->id,
            'completed' => true,
        ]);

        $stage->load('sections.items.latestUpdate');
        $this->assertEquals(50, $stage->progressPct());
    }
}
