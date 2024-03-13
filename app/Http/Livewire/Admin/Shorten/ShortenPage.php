<?php

namespace App\Http\Livewire\Admin\Shorten;

use App\Http\Livewire\BasePage;
use App\Jobs\deleteUrl;
use App\Models\Shorten;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class ShortenPage extends BasePage
{
  public $title = "Shorten Url";
  public $permission = "admin:urls:";

  public $actions = [
    [
        "label" => "New Shorten Url",
        "perms" => "admin:urls:create",
        "icon" => "plus",
        "action" => "createItem",
        "class" => "bg-green-400 text-white"
    ],
  ];

  public function createItem(){
    $this->emit('openModal', 'admin.shorten.shorten-form');
  }

  protected function editItem(Shorten $record){
    $this->emit('openModal', 'admin.shorten.shorten-form', [ 'model' => $record->hid ]);
  }

  protected function deleteItem(Shorten $record){
    dispatch(new deleteUrl($record));
  }

  protected function getTableColumns(): array
  {
    return [
      
        TextColumn::make('code')->searchable()->sortable()->label('Short Code'),
        TextColumn::make('url')->searchable()->sortable()->wrap(),
        TextColumn::make('user.name')->toggleable(true, true),
      
    ];
  }

  protected function getTableQuery(): Builder
  {

      if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super')){
        return Shorten::query();   
      }
      
      return Shorten::query()->where('user_id', auth()->user()->id);

  }

}