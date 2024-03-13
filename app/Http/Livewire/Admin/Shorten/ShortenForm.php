<?php

namespace App\Http\Livewire\Admin\Shorten;

use App\Helpers\HashHelper;
use App\Http\Livewire\ModalForm;
use App\Jobs\createUrl;
use App\Models\Shorten;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class ShortenForm extends ModalForm {

  public $title = "Shorten Url";
    
  public $model_type = Shorten::class;
  public $permission = "admin:urls:";
  public $autofill = false;

  public function mount(?Shorten $model){
    $this->model = $model;
    $this->mount_form();
  }

  protected function getFormSchema(): array 
  {
      return [
        
        TextInput::make('url')->label('Long Url')->required()->activeUrl(),
        Toggle::make('generate')->label('Edit Short Code')->reactive(),
        TextInput::make('code')->label('Short Code')->hidden(function(callable $get){
          return $get('generate') != true;
        })->unique('shortens', 'code', $this->model)->required(),
        
      ];
  }

  public function beforeCreate($data){
    
    $latest = Shorten::latest()->first();
    $id = $latest?->id ?? 1;
    $code = HashHelper::HashId('url', $id+1);

    $this->model->url = $data['url'];
    $this->model->user_id = auth()->user()->id;
    $this->model->code = $data['code'] ?? $code;

  }

  public function saveAndStay(){
    $this->submit();

    dispatch(new createUrl($this->model));

    $this->saveAndStayResponse();
  }

}