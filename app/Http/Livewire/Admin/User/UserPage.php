<?php

namespace App\Http\Livewire\Admin\User;

use App\Http\Livewire\BasePage;
use App\Models\User;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class UserPage extends BasePage
{
  public $title = "Users";
  public $permission = "admin:users:";

  public $actions = [
    [
        "label" => "New User",
        "perms" => "admin:users:create",
        "icon" => "plus",
        "action" => "createItem",
        "class" => "bg-green-400 text-white"
    ],
  ];

  public function createItem(){
    $this->emit('openModal', 'admin.user.user-form');
  }

  protected function editItem(User $record){
    $this->emit('openModal', 'admin.user.user-form', [ 'model' => $record->hid ]);
  }

  protected function deleteItem(User $record){
    $record->delete();
  }

  protected function getTableColumns(): array
  {
    return [
      Split::make([
        TextColumn::make('name')->searchable()->sortable()->label('Name'),
        TextColumn::make('email')->searchable()->sortable(),
        TagsColumn::make('roles.name')
      ])->from('lg')
    ];
  }

  protected function getTableQuery(): Builder
  {
      return User::query();   
  }

}