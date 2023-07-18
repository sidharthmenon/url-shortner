<?php

namespace App\Http\Livewire\Admin\User;

use App\Http\Livewire\ModalForm;
use App\Models\User;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class UserForm extends ModalForm {

  public $title = "Users";
    
  public $model_type = User::class;
  public $permission = "admin:users:";

  public function mount(?User $model){
    $this->model = $model;
    $this->mount_form();
  }

  protected function getFormSchema(): array 
  {
      return [
          Grid::make()
              ->schema([
                  TextInput::make('name')->required(),
                  TextInput::make('email')->required()->email()->unique(ignorable: $this->model),
                  Select::make('roles')->required()->multiple()->relationship('roles', 'name')->preload(),
              ])
      ];
  }

  public function beforeCreate($data){
      $this->model->password = "nopass";
  }


}