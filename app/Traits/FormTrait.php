<?php

namespace App\Traits;

use Filament\Notifications\Notification;

trait FormTrait
{
  public $title = "Form";
  public $backTitle = "Close";
  public $saveTitle = "Save";

  public $model, $model_type, $permission, $data = []; 
	public $autofill = true;

  public function mount_form($data = null){
    if($data){
      $this->form->fill($data);
    }
    else{
      if($this->model){
        $this->form->fill($this->model->toArray());
      }
      else{
          $this->form->fill();
      }
    }
  }

  protected function _model_exists(){
    return filled($this->model) && $this->model->exists;
  }

  protected function _generate_form_model(){
      return $this->_model_exists() ? $this->model : $this->model_type;
  }

  public function submit(){
    $data = $this->form->getState();
    $this->_model_exists() ? $this->onUpdateModel($data) : $this->onCreateModel($data);
  }

  public function onCreateModel($data)
  {
    if($this->permission) $this->authorize($this->permission."create");

    $this->model = new $this->model_type();

    if($this->autofill) $this->model->autofill($data);

    $this->beforeCreate($data);

    $this->model->save();

    $this->form->model($this->model)->saveRelationships(); 

    $this->afterCreate($data);
  }

  public function onUpdateModel($data)
  {
    if($this->permission) $this->authorize($this->permission."update");

    if($this->autofill) $this->model->autofill($data);

    $this->beforeUpdate($data);

    $this->model->save();

    $this->afterUpdate($data);
  }

  public function beforeCreate($data){
  
  }

  public function beforeUpdate($data){
    
  }

  public function afterCreate($data){
    Notification::make() 
            ->title(class_basename($this->model_type).' created successfully')
            ->success()
            ->send(); 
  }

  public function afterUpdate($data){
    Notification::make() 
            ->title(class_basename($this->model_type).' edited successfully')
            ->success()
            ->send(); 
  }

  public function saveAndBack(){
    // $this->submit();
    $this->saveAndGoBackResponse();
  }

  public function saveAndStay(){
      $this->submit();
      $this->saveAndStayResponse();
  }

  public function render()
  {
      return view('livewire.base-form');
  }
}