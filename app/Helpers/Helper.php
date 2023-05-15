<?php

namespace App\Helpers;

use App\Models\Post;
use Illuminate\Support\Str;

class Helper
{
    /**
     * @return array
     */
    public static function getLangCodes(): array
    {
        $langs = config('languages.list');

        $array = [];

        foreach ($langs as $lang) {
            $array[] = $lang['code_lower'];
        }

        // reorder array by default lang
        $defaultLang = config('languages.default');
        $defaultLangIndex = array_search($defaultLang, $array);
        unset($array[$defaultLangIndex]);
        array_unshift($array, $defaultLang);

        return $array;
    }

    public static function defaultLang()
    {
        return config('languages.default');
    }

    public static function getSlugUrl($id, $title = 'title')
    {
        $post = Post::query()->where('id', $id)->first();

        $slug = Str::slug($post->_get($title));

        return route($post->type, [
            'id' => $id,
            'slug' => $slug,
        ]);
    }

    public static function getMenu($id)
    {
        $menuClean = [];

        $data = [];

        $menu = Post::query()->where('id', $id)->first();

        $menuItems = $menu->menuItems()->orderBy('order')->get();

        foreach ($menuItems as $key => $menuItem) {
            if (isset($menuItem['fields']['childs'])) {
                $relationName = $menuItem['fields']['childs'];

                $childs = $menuItem->$relationName();

                $data = $childs;
            } else {
                $data = Post::query()
//                    ->where('fields', 'like', '%"parent_id": "'.$menuItem['id'].'"%') // sqlite kullanılırsa alttaki çalışmayabilir. Bunu dene
                    ->where('fields->parent_id', $menuItem['id'])
                    ->get();
            }

            $menuClean[] = [
                'id' => $menuItem->id,
                'title' => $menuItem->_get('title'),
                'url' => $data?->count() ? ':;javascript' : $menuItem->_get('url'),
                'childs' => $data?->map(function ($item) use ($menuItem) {
                    return [
                        'id' => $item->id,
                        'title' => $item->_get('title'),
                        'url' => route('page', [
                            'id' => $item->id,
                            'slug' => Str::slug($item->_get('title')),
                            'type' => Str::slug($menuItem->_get('title')),
                        ]),
                    ];
                }),
            ];
        }

        return $menuClean;
    }
}
