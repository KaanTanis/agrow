<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Helpers\Helper;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Nuhel\FilamentCropper\Components\Cropper;

class PageResource extends Resource
{
    protected static ?string $model = Post::class;

    public function modelType(): string
    {
        return 'page';
    }

    public static function getLabel(): ?string
    {
        return __('Sayfa');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Sayfalar');
    }

    protected static ?string $slug = 'pages';

    protected static ?string $navigationIcon = 'heroicon-o-paper-clip';

    public static function form(Form $form): Form
    {
        $translatableFields = [];

        foreach (Helper::getLangCodes() as $lang) {
            // define translatable fields
            $tab = [
                Forms\Components\Tabs\Tab::make(Str::upper($lang))
                    ->schema([
                        Forms\Components\TextInput::make('fields.title.'.$lang)
                            ->autofocus()
                            ->label(__('Başlık'))
                            ->required(fn() => $lang == 'tr')
                            ->rules('required', 'max:255'),

                        Forms\Components\RichEditor::make('fields.content.'.$lang)
                            ->label(__('İçerik')),

                    ])
            ];

            $translatableFields = array_merge($translatableFields, $tab);
        }

        return $form
            ->schema([
                Forms\Components\Hidden::make('type')->default('page'),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Tabs::make('locale')
                            ->schema($translatableFields),

                        Forms\Components\Section::make(__('Galeri'))
                            ->schema([
                                Forms\Components\FileUpload::make('fields.images')
                                    ->enableReordering()
                                    ->multiple()
                                    ->label(''),
                            ])->collapsible(),
                    ])
                    ->inlineLabel(false)
                    ->columnSpan(2),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('Kapak Görseli'))
                            ->schema([
                                Cropper::make('fields.cover')
                                    ->imageCropAspectRatio('16:12')
                                    ->enableImageRotation()
                                    ->rotationalStep(5)
                                    ->enableImageFlipping()
                                    ->label(''),
                            ]),

                        Forms\Components\Section::make(__('Banner Görseli'))
                            ->schema([
                                Cropper::make('fields.banner')
                                    ->imageCropAspectRatio('14:3')
                                    ->enableImageRotation()
                                    ->rotationalStep(5)
                                    ->enableImageFlipping()
                                    ->label(''),
                            ]),

                        Forms\Components\Section::make(__('Görünürlük'))
                            ->schema([
                                Forms\Components\Toggle::make('status')
                                    ->label(__('Aktif'))
                            ]),
                    ])->columnSpan(1)->inlineLabel(false),
            ])->columns(3);
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

                Tables\Columns\ToggleColumn::make('status')
                    ->label(__('Aktif')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('Görünürlük'))
                    ->options([
                        '1' => __('Görünür'),
                        '0' => __('Görünmez'),
                    ]),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
