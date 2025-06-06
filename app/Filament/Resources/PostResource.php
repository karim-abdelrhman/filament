<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Filament\Resources\PostResource\RelationManagers\AuthorsRelationManager;
use App\Models\Category;
use App\Models\Post;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;


class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static ?string $navigationGroup = "Blog";
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(components: [
                Section::make('Post Details')->schema([
                    TextInput::make('title')->required(),
                    TextInput::make('slug')->required(),
                    Select::make('category_id')->relationship('category', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                    TagsInput::make('tags')->required(),
                    MarkdownEditor::make('content')->required()->columnSpan('full'),
                ])->columnSpan(2)->columns(2)->collapsible(),
                Group::make()->schema([
                    Section::make("Image")->schema([
                        FileUpload::make('thumbnail')->required()->image()->disk('public')->directory('posts/thumbnails'),
                    ])->collapsible(),
                    Section::make("Meta")->schema([
                        ColorPicker::make('color')->required(),
                        Toggle::make('published')->required()
                    ]),
                    Section::make('authors')
                        ->schema([
                            Select::make('authors')
                                ->label('Co Authors')
                                ->multiple()
                                ->preload()
                                ->relationship('authors', 'name'),
                        ])
                ])->columnSpan(1),
            ])->Columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('slug')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                ColorColumn::make('color'),
                ImageColumn::make('thumbnail'),
                TextColumn::make('category.name')->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('tags')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                CheckboxColumn::make('published')
                    ->toggleable(),
                TextColumn::make('created_at')->sortable()->label('Published On')->date('Y m D')
                    ->toggleable(),
            ])
            ->filters([
                Filter::make('Published Posts')
                    ->query(function (Builder $query): Builder {
                        return $query->where('published', true);
                    }),
                //for unpublished posts we can do another filter and make condition false
                // do TernaryFilter::make('published') and it will work perfectly
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
//                ->options(Category::all()->pluck('name', 'id')->toArray())
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                DeleteAction::make(),
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
            AuthorsRelationManager::class,
            RelationManagers\CommentsRelationManager::class
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
