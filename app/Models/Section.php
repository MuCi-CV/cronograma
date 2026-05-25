<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['stage_id', 'name', 'order'];

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class)->where('active', true)->orderBy('order');
    }

    public function progressPct(): int
    {
        $total = $this->items->count();
        if ($total === 0) return 0;
        $done = $this->items->filter(fn($i) => $i->isCompleted)->count();
        return (int) round($done / $total * 100);
    }
}
