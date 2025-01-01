<?php

namespace Bishopm\Church\Filament\Clusters\Website\Resources;

use Bishopm\Church\Filament\Clusters\Website;
use Bishopm\Church\Filament\Clusters\Website\Resources\PostResource\Pages;
use Bishopm\Church\Filament\Clusters\Website\Resources\PostResource\RelationManagers;
use Bishopm\Church\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $cluster = Website::class;

    protected static ?string $modelLabel = 'Blog post';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Add New Blog Post')->columnSpanFull()->tabs([
                    Tab::make('Details')->columns(2)->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->maxLength(191),
                        Forms\Components\Select::make('person_id')
                            ->relationship(
                                name: 'person',
                                modifyQueryUsing: fn (Builder $query) => $query->where('role', 'LIKE', '%Blogger%')->orderBy('firstname')->orderBy('surname'),
                            )
                            ->label('Author')
                            ->placeholder('')
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->firstname} {$record->surname}")
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('firstname')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('surname')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('bio')
                                    ->columnSpanFull(),
                                Forms\Components\FileUpload::make('image')
                                    ->image(),
                                Forms\Components\TextInput::make('role')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Toggle::make('active'),
                            ]),
                        SpatieTagsInput::make('tags'),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user')
                            ->required()
                            ->label('User')
                            ->placeholder('')
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name}")
                            ->default(auth()->id()),
                        Forms\Components\DateTimePicker::make('published_at')
                        ->native(false)
                        ->default(now())
                        ->displayFormat('Y-m-d')
                        ->format('Y-m-d'),
                        Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(191),
                        Forms\Components\Toggle::make('published')
                            ->default(0),                        
                    ]),
                    Tab::make('Content')->columns(2)->schema([
                        Forms\Components\Textarea::make('excerpt')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull(),
                    ]),
                    Tab::make('Media')->columns(2)->schema([
                        Forms\Components\FileUpload::make('image')
                        ->image()
                        ->directory('media/images/blog')
                        ->previewable(false)
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('16:9')
                        ->imageResizeTargetWidth('960')
                        ->imageResizeTargetHeight('540')
                    ])
                ])                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tags.name')
                    ->badge()
                    ->forceSearchCaseInsensitive(true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('published')
                    ->icon(fn (string $state): string => match ($state) {
                        '0' => 'heroicon-o-x-circle',
                        '1' => 'heroicon-o-check-circle'
                    })
            ])
            ->filters([
                //
            ])
            ->defaultSort('published_at','DESC')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
