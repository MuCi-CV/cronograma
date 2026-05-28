<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\ItemImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItemImageControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $editor;
    private Item $item;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\CronogramaSeeder::class);
        $this->editor = User::factory()->create(['email' => 'dir@muci.org']);
        $this->item = Item::first();
    }

    public function test_guest_cannot_upload_image(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('photo.jpg');

        $response = $this->post(route('images.store', $this->item), ['image' => $file]);

        $response->assertRedirect('/auth/google');
        $this->assertDatabaseCount('item_images', 0);
    }

    public function test_editor_can_upload_image(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('photo.jpg', 400, 300);

        $response = $this->actingAs($this->editor)
            ->post(route('images.store', $this->item), ['image' => $file]);

        $response->assertRedirect();

        $image = ItemImage::where('item_id', $this->item->id)->first();
        $this->assertNotNull($image);
        Storage::disk('public')->assertExists($image->path);
        Storage::disk('public')->assertExists($image->original_path);
        $this->assertStringEndsWith('.jpg', $image->path);
        $this->assertStringEndsWith('_orig.jpg', $image->original_path);
    }

    public function test_upload_rejects_non_image_file(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $this->actingAs($this->editor)
            ->post(route('images.store', $this->item), ['image' => $file]);

        $this->assertDatabaseCount('item_images', 0);
    }

    public function test_upload_rejects_file_over_10mb(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('big.jpg')->size(11000);

        $this->actingAs($this->editor)
            ->post(route('images.store', $this->item), ['image' => $file]);

        $this->assertDatabaseCount('item_images', 0);
    }

    public function test_editor_can_delete_image(): void
    {
        Storage::fake('public');
        $path         = 'items/' . $this->item->id . '/test.jpg';
        $originalPath = 'items/' . $this->item->id . '/test_orig.jpg';
        Storage::disk('public')->put($path, 'content');
        Storage::disk('public')->put($originalPath, 'content');
        $image = ItemImage::create([
            'item_id'       => $this->item->id,
            'path'          => $path,
            'original_path' => $originalPath,
            'order'         => 1,
        ]);

        $response = $this->actingAs($this->editor)
            ->delete(route('images.destroy', [$this->item, $image]));

        $response->assertRedirect();
        Storage::disk('public')->assertMissing($path);
        Storage::disk('public')->assertMissing($originalPath);
        $this->assertDatabaseMissing('item_images', ['id' => $image->id]);
    }

    public function test_cannot_delete_image_belonging_to_different_item(): void
    {
        Storage::fake('public');
        $otherItem = Item::skip(1)->first();
        $path      = 'items/' . $otherItem->id . '/test.jpg';
        Storage::disk('public')->put($path, 'content');
        $image = ItemImage::create(['item_id' => $otherItem->id, 'path' => $path, 'order' => 1]);

        $response = $this->actingAs($this->editor)
            ->delete(route('images.destroy', [$this->item, $image]));

        $response->assertForbidden();
        $this->assertDatabaseHas('item_images', ['id' => $image->id]);
    }

    public function test_guest_cannot_delete_image(): void
    {
        Storage::fake('public');
        $path  = 'items/' . $this->item->id . '/test.jpg';
        Storage::disk('public')->put($path, 'content');
        $image = ItemImage::create(['item_id' => $this->item->id, 'path' => $path, 'order' => 1]);

        $this->delete(route('images.destroy', [$this->item, $image]));

        $this->assertDatabaseHas('item_images', ['id' => $image->id]);
    }
}
