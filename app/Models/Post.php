<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;
use Spatie\Translatable\HasTranslations;

/**
 * @method __get($key)
 */
class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'meta' => 'array',
        'fields' => 'array',
    ];

    public function menuItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class, 'parent_id');
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Post::class, 'parent_id');
    }

    public function _get($key, $lang = null)
    {
        if ($lang == null) {
            $lang = app()->getLocale();
        }

        $data = $this->fields[$key][$lang]
            ?? $this->fields[$key][Helper::defaultLang()]
            ?? $this->fields[$key] ?? null;

        if (! is_array($data)) {
            return $data;
        }

        return null;
    }

    public function pages()
    {
        return $this
            ->where('type', 'page')
            ->orderBy('order')
            ->get();
    }

    public function blog()
    {
         return Post::query()->where('type', 'blog')
            ->orderBy('order')
            ->get();
    }
}
