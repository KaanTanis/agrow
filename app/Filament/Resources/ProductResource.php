<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Helpers\Helper;
use App\Models\Post;
use App\Models\Product;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Nuhel\FilamentCropper\Components\Cropper;

class ProductResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $slug = 'product';

    public static function getLabel(): ?string
    {
        return __('Ürün');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Ürünler');
    }

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
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

                        Forms\Components\Textarea::make('fields.description.' . $lang)
                            ->label(__('Açıklama / Alt Başlık')),
                    ])
            ];

            $translatableFields = array_merge($translatableFields, $tab);
        }

        $untranslatableFields = [
            Forms\Components\TextInput::make('fields.price')
                ->label(__('Fiyat'))
                ->numeric(),

            Cropper::make('fields.image')
                ->imageCropAspectRatio('1:1')
                ->enableOpen()
                ->modalSize('sm')
                ->label(__('Görsel')),
        ];

        $allFields = [
            // define type
            Forms\Components\Hidden::make('type')->default('product'),

            // define tab and section
            Forms\Components\Tabs::make('locale')
                ->schema($translatableFields),
            Forms\Components\Section::make(__('Çevirilemeyen Alanlar'))
                ->schema($untranslatableFields)
        ];

        /////////////////////////////

        return $form
            ->schema($allFields);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fields.title.'.Helper::defaultLang())
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->where('fields->title', 'like', "%{$search}%");
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy('fields->title', $direction);
                    })
                    ->label(__('Başlık')),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
