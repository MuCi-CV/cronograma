<?php

namespace Tests\Unit\Models;

use App\Models\Item;
use App\Models\ItemUpdate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_completed_false_when_no_updates(): void
    {
        $item = Item::factory()->create();
        $this->assertFalse($item->isCompleted);
    }

    public function test_is_completed_reflects_latest_update(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        ItemUpdate::factory()->create([
            'item_id' => $item->id, 'user_id' => $user->id, 'completed' => true,
        ]);

        $item->load('latestUpdate');
        $this->assertTrue($item->isCompleted);
    }

    public function test_latest_update_is_most_recent(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        ItemUpdate::factory()->create([
            'item_id' => $item->id, 'user_id' => $user->id,
            'completed' => true, 'created_at' => now()->subMinute(),
        ]);
        ItemUpdate::factory()->create([
            'item_id' => $item->id, 'user_id' => $user->id,
            'completed' => false, 'created_at' => now(),
        ]);

        $item->load('latestUpdate');
        $this->assertFalse($item->isCompleted);
    }
}
