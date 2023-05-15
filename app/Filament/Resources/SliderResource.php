<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Filament\Resources\SliderResource\RelationManagers;
use App\Helpers\Helper;
use App\Models\Post;
use Camya\Filament\Forms\Components\TitleWithSlugInput;
use Camya\Filament\Forms\Fields\SlugInput;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Guava\FilamentIconPicker\Forms\IconPicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Nuhel\FilamentCropper\Components\Cropper;
use Webbingbrasil\FilamentCopyActions\Tables\CopyableTextColumn;

class SliderResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $slug = 'slider';

    public static function getLabel(): ?string
    {
        return __('Slider');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Sliderlar');
    }

    protected static ?string $navigationIcon = 'heroicon-o-dots-horizontal';

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

                        Forms\Components\TextInput::make('fields.description.' . $lang)
                            ->label(__('Açıklama / Alt Başlık')),
                        Forms\Components\TextInput::make('fields.button_text.' . $lang)
                            ->label(__('Buton Yazısı')),
                    ])
            ];

            $translatableFields = array_merge($translatableFields, $tab);
        }

        $untranslatableFields = [
            // define untraslated fields
            Forms\Components\TextInput::make('fields.button_link')
                ->label(__('Buton Link')),

            Cropper::make('fields.image')
                ->enableImageRotation()
                ->rotationalStep(5)
                ->enableImageFlipping()
                ->enabledAspectRatios([
                    '16:9'
                ])
                ->imageCropAspectRatio('16:9')
                ->modalSize('xl')
                ->label(__('Görsel')),
        ];

        $allFields = [
            // define type
            Forms\Components\Hidden::make('type')->default('slider'),

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

                Tables\Columns\ImageColumn::make('fields.image')
                    ->label(__('Görsel')),
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
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
