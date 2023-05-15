<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomeFieldResource\Pages;
use App\Filament\Resources\HomeFieldResource\RelationManagers;
use App\Helpers\Helper;
use App\Models\HomeField;
use App\Models\Post;
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

class HomeFieldResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'home-field';

    public static function getLabel(): ?string
    {
        return __('Ana Sayfa Alanları');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Ana Sayfa Alanları');
    }

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        $translatableFields = [];
        $translatableFieldsMiddle = [];

        foreach (Helper::getLangCodes() as $lang) {
            // define translatable fields
            $tab = [
                Forms\Components\Tabs\Tab::make(Str::upper($lang))
                    ->schema([
                        Forms\Components\TextInput::make('fields.about_title.' . $lang)
                            ->label(__('Hakkımızda Başlık'))
                            ->required(fn() => $lang == 'tr'),

                        Forms\Components\TextInput::make('fields.about_big_title.' . $lang)
                            ->label(__('Hakkımızda Büyük Başlık')),

                        Forms\Components\RichEditor::make('fields.about_content.' . $lang)
                            ->label(__('Hakkımızda İçerik')),

                    ])
            ];

            $translatableFields = array_merge($translatableFields, $tab);
        }

        foreach (Helper::getLangCodes() as $lang) {
            // define translatable fields
            $tab = [
                Forms\Components\Tabs\Tab::make(Str::upper($lang))
                    ->schema([
                        Forms\Components\TextInput::make('fields.middle_title.' . $lang)
                            ->label(__('Başlık')),

                        Forms\Components\RichEditor::make('fields.middle_content.' . $lang)
                            ->label(__('İçerik')),

                    ])
            ];

            $translatableFieldsMiddle = array_merge($translatableFieldsMiddle, $tab);
        }

        $untranslatableFieldsMiddle = [
            Cropper::make('fields.middle_bg_1')
                ->modalSize('xl')
                ->label(__('Görsel')),
            Cropper::make('fields.middle_bg_2')
                ->modalSize('xl')
                ->label(__('Görsel')),
        ];

        $untranslatableFields = [
            Cropper::make('fields.about_bg')
                ->imageCropAspectRatio('9:14')
                ->modalSize('xl')
                ->label(__('Ana Sayfa Hakkımızda Görseli')),

            IconPicker::make('test')
        ];

        $allFields = [
            // define type
            Forms\Components\Hidden::make('type')->default('homeField'),

            // define tab and section
            Forms\Components\Section::make(__('Hakkımızda Alanı'))
                ->schema([
                    Forms\Components\Tabs::make('locale')
                        ->schema($translatableFields),
                    Forms\Components\Section::make(__('Çevirilemeyen Alanlar'))
                        ->schema($untranslatableFields),
                ])->collapsible(),

            Forms\Components\Section::make(__('Orta Tanıtım Alanı'))
                ->schema([
                    Forms\Components\Tabs::make('locale')
                        ->schema($translatableFieldsMiddle),
                    Forms\Components\Section::make(__('Çevirilemeyen Alanlar'))
                        ->schema($untranslatableFieldsMiddle),
                ])->collapsible()->collapsed(),

            Forms\Components\Section::make(__('Partnerlerimiz'))
                ->schema([
                    Forms\Components\FileUpload::make('fields.partners')
                        ->label(__('Partnerler'))
                        ->enableReordering()
                        ->multiple()
                ])->collapsed()->collapsible()
        ];

        /////////////////////////////

        return $form
            ->schema($allFields);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListHomeFields::route('/'),
            'create' => Pages\CreateHomeField::route('/create'),
            'edit' => Pages\EditHomeField::route('/{record}/edit'),
        ];
    }
}
