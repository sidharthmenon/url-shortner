<?php

namespace App\Http\Livewire\Admin\Role;

use App\Http\Livewire\ModalForm;
use App\Models\Role;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class RoleForm extends ModalForm {

  public $title = "Roles";
    
  public $model_type = Role::class;
  public $permission = "admin:roles:";

  public function mount(?Role $model){
    $this->model = $model;
    $this->mount_form();
  }

  protected function getFormSchema(): array 
  {
      return [
        Grid::make()->schema([
          TextInput::make('name')->required(),
          TextInput::make('type')->required(),
        ]),
        CheckboxList::make('permissions')->relationship('permissions', 'name'),
      ];
  }

}