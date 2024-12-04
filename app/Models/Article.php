<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'title',
        'author',
        'content',
        'source',
        'source_url',
        'published_at',
        'category',
    ];

    /**
     * Get the article's formatted published date.
     *
     * @return string
     */
    public function getFormattedPublishedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->published_at)->format('F j, Y');
    }

}
