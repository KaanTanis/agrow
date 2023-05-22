<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NutrientResource\Pages;
use App\Filament\Resources\NutrientResource\RelationManagers;
use App\Helpers\Helper;
use App\Models\Nutrient;
use App\Models\Post;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Nuhel\FilamentCropper\Components\Cropper;

class NutrientResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $slug = 'nutrient';

    public static function getLabel(): ?string
    {
        return __('Besin');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Besinler');
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

            Cropper::make('fields.image')
                ->imageCropAspectRatio('1:1')
                ->enableOpen()
                ->modalSize('sm')
                ->label(__('Görsel')),
        ];

        $allFields = [
            // define type
            Forms\Components\Hidden::make('type')->default('nutrient'),

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

            ])->defaultSort('order')->reorderable('order')
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
            'index' => Pages\ListNutrients::route('/'),
            'create' => Pages\CreateNutrient::route('/create'),
            'edit' => Pages\EditNutrient::route('/{record}/edit'),
        ];
    }
}
