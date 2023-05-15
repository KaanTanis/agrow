<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Helpers\Helper;
use App\Models\Post;
use App\Models\Setting;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Guava\FilamentIconPicker\Forms\IconPicker;
use Heloufir\FilamentLeafLetGeoSearch\Forms\Components\LeafletInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use msuhels\editorjs\Forms\Components\EditorJs;
use Nuhel\FilamentCropper\Components\Cropper;
use Yemenpoint\FilamentGoogleMapLocationPicker\Forms\Components\LocationPicker;

class SettingResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'settings';

    public static function getLabel(): ?string
    {
        return __('Ayar');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Ayarlar');
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
                            ->label(__('Site Başlık'))
                            ->required(fn() => $lang == 'tr'),

                        Forms\Components\Textarea::make('fields.description.' . $lang)
                            ->label(__('Site Açıklama')),

                        Forms\Components\Textarea::make('fields.footer_text.' . $lang)
                            ->label(__('Footer Yazısı')),
                    ])
            ];

            $translatableFields = array_merge($translatableFields, $tab);
        }

        $untranslatableFields = [
            Forms\Components\TextInput::make('fields.phone')
                ->label(__('Telefon')),

            Forms\Components\TextInput::make('fields.email')
                ->label(__('E-Posta')),

            Forms\Components\TextInput::make('fields.address')
                ->label(__('Adres')),

            LeafletInput::make('fields.location')
                ->label(__('Konum'))
                ->setMapHeight(300)
                ->setZoomControl(true)
                ->setScrollWheelZoom(true)
                ->setZoomLevel(16)
                ->required(),

            Forms\Components\Repeater::make('fields.socials')
                ->collapsible()
                ->collapsed()
                ->label(__('Sosyal Medya'))
                ->schema([
                    Forms\Components\Select::make('platform')
                        ->translateLabel()
                        ->options([
                            'facebook' => 'Facebook',
                            'twitter' => 'Twitter',
                            'instagram' => 'Instagram',
                            'linkedin' => 'Linkedin',
                            'youtube' => 'Youtube',
                            'pinterest' => 'Pinterest',
                            'tiktok' => 'TikTok'
                        ]),

                    Forms\Components\TextInput::make('url')
                        ->label(__('URL')),

                    IconPicker::make('icon')
                        ->label(__('İkon')),
                ]),

        ];

        $allFields = [
            // define type
            Forms\Components\Hidden::make('type')->default('settings'),

            // define tab and section
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Tabs::make('locale')
                        ->schema($translatableFields),

                    Forms\Components\Section::make(__('Diğer Ayarlar'))
                        ->schema($untranslatableFields)
                ])
                    ->columnSpan(2),

            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make(__('Logo & Favicon'))
                        ->schema([
                            Cropper::make('fields.logo')
                                ->rotationalStep(5)
                                ->label(__('Logo')),

                            Cropper::make('fields.light_logo')
                                ->rotationalStep(5)
                                ->label(__('Light Logo')),

                            Cropper::make('fields.favicon')
                                ->rotationalStep(5)
                                ->imageCropAspectRatio('1:1')
                                ->label(__('Favicon')),
                        ]),

                    Forms\Components\Section::make(__('Varsayılan Banner'))
                        ->schema([
                            Cropper::make('fields.default_banner')
                                ->imageCropAspectRatio('19:5')
                                ->helperText(__('Varsayılan banner seçildiği zaman, eğer içerikten banner silinirse, bu banner gösterilecektir.'))
                                ->label(''),
                        ])
                ])
                    ->inlineLabel(false)
                    ->columnSpan(1),
        ];

        /////////////////////////////

        return $form
            ->schema($allFields)->columns(3);
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
//                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
