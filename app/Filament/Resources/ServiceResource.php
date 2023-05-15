<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Helpers\Helper;
use App\Models\Post;
use App\Models\Service;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Nuhel\FilamentCropper\Components\Cropper;

class ServiceResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $slug = 'service';

    public static function getLabel(): ?string
    {
        return __('Servis');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Servisler');
    }

    public function modelType(): string
    {
        return 'service';
    }

    protected static ?string $navigationIcon = 'heroicon-o-server';

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
                            ->label(__('Açıklama')),

                        Forms\Components\RichEditor::make('fields.content.' . $lang)
                            ->label(__('İçerik')),
                    ])
            ];

            $translatableFields = array_merge($translatableFields, $tab);
        }

        $untranslatableFields = [
            Forms\Components\Select::make('parent_id')
                ->options(fn() => Post::query()
                    ->where('type', 'service')
                    ->whereNull('parent_id')
                    ->get()
                    ->mapWithKeys(fn($item) => [$item->id => $item['fields']['title'][Helper::defaultLang()]])
                )->label(__('Üst Servis')),

            Forms\Components\FileUpload::make('fields.gallery')
                ->label(__('Galeri'))
                ->enableReordering()
                ->multiple(),

            Cropper::make('fields.cover')
                ->imageCropAspectRatio('16:12')
                ->enableImageRotation()
                ->rotationalStep(5)
                ->enableImageFlipping()
                ->label(''),

            Cropper::make('fields.banner')
                ->imageCropAspectRatio('14:3')
                ->enableImageRotation()
                ->rotationalStep(5)
                ->enableImageFlipping()
                ->label(''),
        ];

        $allFields = [
            // define type
            Forms\Components\Hidden::make('type')->default('service'),

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

                Tables\Columns\TextColumn::make('parent.fields.title.'.Helper::defaultLang())
                    ->label(__('Üst Servis')),

            ])->reorderable('order')->defaultSort('order')
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
