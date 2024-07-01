<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

//lưu trữ thông tin về các mục yêu thích của người dùng
class Favorite extends Model
{
    protected $fillable = [
        'customer_id',
        'movie_id',
    ];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}