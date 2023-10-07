<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title',
        'slug',
        'view_count',
        'content',
        'image',
        'user_id',
        'category_id',
    ];

    public function setSlugAttribute($value)
    {
        $slug = Str::slug($value);
        $baseSlug = $slug;

        // Verifica se já existe um post com o mesmo slug
        $i = 1;
        while ($this->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        $this->attributes['slug'] = $slug;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
