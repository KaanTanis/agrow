<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SolutionForest\FilamentTree\Concern\ModelTree;

class Menu extends Model
{
    use HasFactory, ModelTree;

    protected $guarded = [];

    protected $casts = [
        'fields' => 'array',
    ];

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
}
