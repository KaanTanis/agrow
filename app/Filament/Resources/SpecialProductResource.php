<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpecialProductResource\Pages;
use App\Filament\Resources\SpecialProductResource\RelationManagers;
use App\Helpers\Helper;
use App\Models\Post;
use App\Models\SpecialProduct;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class SpecialProductResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $slug = 'special-product';

    public static function getLabel(): ?string
    {
        return __('Özel Ürün');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Özel Ürünler');
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

                        Forms\Components\RichEditor::make('fields.content.' . $lang)
                            ->label(__('İçerik')),
                    ])
            ];

            $translatableFields = array_merge($translatableFields, $tab);
        }

        $untranslatableFields = [
            Forms\Components\TextInput::make('fields.price')
                ->label(__('Fiyat'))
                ->numeric(),

            Forms\Components\TextInput::make('fields.stock')
                ->label(__('Stok'))
                ->numeric(),

            Forms\Components\FileUpload::make('fields.cover')
                ->label(__('Kapak Resmi'))
        ];

        $allFields = [
            // define type
            Forms\Components\Hidden::make('type')->default('special_product'),

            Forms\Components\Section::make(__('Müşteriye Verilecek Link'))
                ->schema([
                    Forms\Components\Placeholder::make('link')
                        ->label('')
                        ->content(function ($record) {
                            if ($record) {
                                return new HtmlString('<a href="' . route('product', $record->id) . '" target="_blank">' . route('product', $record->id) . '</a>');
                            }
                        }),
                ]),
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
            'index' => Pages\ListSpecialProducts::route('/'),
            'create' => Pages\CreateSpecialProduct::route('/create'),
            'edit' => Pages\EditSpecialProduct::route('/{record}/edit'),
        ];
    }
}
