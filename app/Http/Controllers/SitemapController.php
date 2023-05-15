<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Post;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SitemapController extends Controller
{
    /*
     * Sitemap ve menüyü oluşturan URL adresleri farklı.
     * Buna daha sonra bakılacak
     *
     * Generate sitemap.xml
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function __invoke(): mixed
    {
        $resources = Filament::getResources();

        $collect = collect($resources)->transform(function ($resource) {

            $res = app($resource);
            $model = $res->getModel();

            if (method_exists($res, 'modelType')) {
                return Post::query()->where('type', $res->modelType())->get()->transform(function ($item) use ($res) {
                    return [
                        'loc' => route('page', [
                            'id' => $item->id,
                            'type' => __($item->type),
                            'slug' => Str::slug($item->_get('title')),
                        ]),
                        'lastmod' => $item->updated_at->tz('UTC')->toAtomString(),
                        'changefreq' => 'daily',
                        'priority' => '0.8',
                    ];
                });
            }

            return null;
        })
            ->filter()
            ->values()
            ->toArray();

        return response()->view('sitemap', compact('collect'))
            ->header('Content-Type', 'text/xml');
    }
}
