<?php

namespace App\Filament\Pages;

use App\Helpers\Helper;
use App\Models\Menu;
use App\Models\Post;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Pages\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use SolutionForest\FilamentTree\Actions;
use SolutionForest\FilamentTree\Concern;
use SolutionForest\FilamentTree\Pages\TreePage as BasePage;
use SolutionForest\FilamentTree\Support\Utils;

class MenuBuilder extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-view-list';

    public static function getMaxDepth(): int
    {
        return 2;
    }

    public function getModel(): string
    {
        return Menu::class;
    }

    protected function getFormSchema(): array
    {
        $translatableFields = [];

        foreach (Helper::getLangCodes() as $lang) {
            // define translatable fields
            $tab = [
                Forms\Components\Tabs\Tab::make(Str::upper($lang))
                    ->schema([
                        Forms\Components\TextInput::make('fields.title.' . $lang)
                            ->label(__('Başlık'))
                            ->required(fn() => $lang == 'tr'),
                    ])
            ];

            $translatableFields = array_merge($translatableFields, $tab);
        }

        $untranslatableFields = [
            Forms\Components\Select::make('fields.page_id')
                ->label(__('Sayfa Seç'))
                ->options(fn() => Post::query()
                    ->where(function (Builder $query) {
                        $query->where('type', 'page');

                        $resources = collect(Filament::getResources());

                        foreach ($resources as $resource) {
                            $res = app($resource);
                            if (method_exists($res, 'modelType')) {
                                $query->orWhere('type', $res->modelType());
                            }
                        }
                    })
                    ->get()
                    ->mapWithKeys(fn($item) => [$item->id => $item['fields']['title'][Helper::defaultLang()]])
                )->afterStateUpdated(function (\Closure $get, \Closure $set, ?string $state) {
                    $set('fields.url',
                        Str::replace(url('/'),
                            '',
                            \route('page', [
                                'id' => $state,
                            ])
                        )
                    );
                })
                ->reactive(),

            Forms\Components\TextInput::make('fields.url')
                ->label(__('URL'))
                ->prefix(url()->to('/') . '/'),

            Forms\Components\TextInput::make('fields.childs')
                ->hint(__('Bu relation sonradan değiştirilemez. Otomatik childları getirir.'))
                ->disabledOn('edit')
                ->label(__('Childlar'))
        ];

        $allFields = [
            // define tab and section
            Forms\Components\Tabs::make('locale')
                ->schema($translatableFields)->columnSpanFull(),
            Forms\Components\Section::make(__('Çevirilemeyen Alanlar'))
                ->schema($untranslatableFields)
        ];

        /////////////////////////////

        return $allFields;
    }

    public function getTreeRecordTitle(?Model $record = null): string
    {
        return $record->_get('title');
    }
}
