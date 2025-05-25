<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs() : array
    {
        return [
          'All' => ListRecords\Tab::make(),
          'Published' => ListRecords\Tab::make()->modifyQueryUsing(function ($query) {
              $query->where('published' , true);
          }),
            'UnPublished' => ListRecords\Tab::make()->modifyQueryUsing(function ($query) {
                $query->where('published' , false);
            }),
        ];
    }
}
