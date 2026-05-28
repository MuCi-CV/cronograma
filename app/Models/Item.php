<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['section_id', 'type', 'text', 'order', 'active'];

    protected function casts(): array
    {
        return ['active' => 'boolean'];
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function updates(): HasMany
    {
        return $this->hasMany(ItemUpdate::class)->latest();
    }

    public function latestUpdate(): HasOne
    {
        return $this->hasOne(ItemUpdate::class)->latestOfMany();
    }

    protected function isCompleted(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->latestUpdate?->completed ?? false,
        );
    }

    protected function latestComment(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->latestUpdate?->comment,
        );
    }

    public function images(): HasMany
    {
        return $this->hasMany(ItemImage::class)->orderBy('order');
    }
}
