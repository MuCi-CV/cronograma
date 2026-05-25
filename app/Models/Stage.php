<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'year', 'order'];

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->orderBy('order');
    }

    public function items(): HasManyThrough
    {
        return $this->hasManyThrough(Item::class, Section::class);
    }

    public function progressPct(): int
    {
        $items = $this->sections->flatMap->items;
        $total = $items->count();
        if ($total === 0) return 0;
        $done = $items->filter(fn($i) => $i->isCompleted)->count();
        return (int) round($done / $total * 100);
    }
}
