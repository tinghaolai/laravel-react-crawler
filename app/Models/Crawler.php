<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Crawler extends Model
{
    protected $fillable = [
        'url',
        'title',
        'description',
        'screen_shot_path',
        'body',
        'origin_html'
    ];
}
