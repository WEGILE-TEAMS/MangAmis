<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MangaHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'manga_id',
        'chapter_id',
        'read_at',
    ];
}
