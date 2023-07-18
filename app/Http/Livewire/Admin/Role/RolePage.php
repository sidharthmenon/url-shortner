<?php

namespace App\Http\Livewire\Admin\Role;

use App\Http\Livewire\BasePage;
use App\Models\Role;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class RolePage extends BasePage
{
  public $title = "Roles";
  public $permission = "admin:roles:";

  public $actions = [
    [
        "label" => "New Role",
        "perms" => "admin:roles:create",
        "icon" => "plus",
        "action" => "createItem",
        "class" => "bg-green-400 text-white"
    ],
  ];

  public function createItem(){
    $this->emit('openModal', 'admin.role.role-form');
  }

  protected function editItem(Role $record){
    $this->emit('openModal', 'admin.role.role-form', [ 'model' => $record->id ]);
  }

  protected function deleteItem(Role $record){
    $record->delete();
  }

  protected function getTableColumns(): array
  {
    return [
      // Split::make([
        TextColumn::make('name')->searchable()->sortable(),
        TextColumn::make('type')->searchable()->sortable(),
      // ])->from('lg')
    ];
  }

  protected function getTableQuery(): Builder
  {
      return Role::query();   
  }

}