<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $editor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->editor = User::factory()->create(['email' => 'dir@muci.org']);
    }

    public function test_guest_cannot_update_settings(): void
    {
        $response = $this->patch(route('tracker.settings.update'), ['image_layout' => 'C']);

        $response->assertRedirect('/auth/google');
        $this->assertDatabaseCount('settings', 0);
    }

    public function test_editor_can_set_layout_to_c(): void
    {
        $this->actingAs($this->editor)
            ->patch(route('tracker.settings.update'), ['image_layout' => 'C']);

        $this->assertDatabaseHas('settings', ['key' => 'image_layout', 'value' => 'C']);
    }

    public function test_editor_can_enable_hide_images_mobile(): void
    {
        $this->actingAs($this->editor)
            ->patch(route('tracker.settings.update'), ['hide_images_mobile' => '1']);

        $this->assertDatabaseHas('settings', ['key' => 'hide_images_mobile', 'value' => '1']);
    }

    public function test_settings_are_upserted_not_duplicated(): void
    {
        $this->actingAs($this->editor)
            ->patch(route('tracker.settings.update'), ['image_layout' => 'C']);
        $this->actingAs($this->editor)
            ->patch(route('tracker.settings.update'), ['image_layout' => 'B']);

        $this->assertDatabaseCount('settings', 1);
        $this->assertDatabaseHas('settings', ['key' => 'image_layout', 'value' => 'B']);
    }

    public function test_rejects_invalid_layout_value(): void
    {
        $response = $this->actingAs($this->editor)
            ->patch(route('tracker.settings.update'), ['image_layout' => 'X']);

        $response->assertSessionHasErrors('image_layout');
        $this->assertDatabaseCount('settings', 0);
    }
}
