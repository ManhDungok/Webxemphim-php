<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'video',
        'backdrop',
        'trending',
        'price',
        'point',
        'release_date',
        'duration',
        'status',
    ];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function nation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Nation::class);
    }
    public function favorites() {
        return $this->hasMany(Favorite::class);
    }
    public function orders() {
        return $this->hasMany(Order::class);
    }
    public function getPurchaseCountAttribute()
    {
        return $this->orders()->count();
    }

    public function getPriceLabelAttribute()
    {
        return $this->price == 0 ? 'Miễn phí' : $this->price;
    }
}