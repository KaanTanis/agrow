<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Filament\Facades\Filament;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * @param int $id
     * @param string|null $type
     * @param string|null $slug
     * @return Application|Factory|View|RedirectResponse
     */
    public function page(int $id, string $type = null, string $slug = null)
    {
        $data = Post::query()
            ->where(function ($query) {
                $query->where('type', 'page');

                $resources = collect(Filament::getResources());

                foreach ($resources as $resource) {
                    $res = app($resource);
                    if (method_exists($res, 'modelType')) {
                        $query->orWhere('type', $res->modelType());
                    }
                }
            })->where('id', $id)->first();

        if ($slug == null || $type == null) {
            return redirect()->route('page', [
                'id' => $id,
                'type' => __($data->type),
                'slug' => Str::slug($data->_get('title')),
            ])->setStatusCode(301);
        }

        return view('page', compact('data'));
    }
}
