<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['title', 'slug', 'content', 'published_at'];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($article): void {
            $slug = Str::slug($article->title);

            $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

            $article->slug = $count ? "{$slug}-{$count}" : $slug;
        });
    }

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }
}
