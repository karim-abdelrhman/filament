<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
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
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(components: [
                Section::make('Post Details')->schema([
                    TextInput::make('title')->required(),
                    TextInput::make('slug')->required(),
                    Select::make('category_id')->relationship('category', 'name')->required(),
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
                    ])
                ])->columnSpan(1),
            ])->Columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('slug'),
                ColorColumn::make('color'),
                ImageColumn::make('thumbnail'),
                TextColumn::make('category.name'),
                TextColumn::make('tags'),
                CheckboxColumn::make('published'),
            ])
            ->filters([
                //
            ])
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
