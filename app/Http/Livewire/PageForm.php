<?php

namespace App\Http\Livewire;

use App\Traits\FormTrait;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class PageForm extends Component implements HasForms
{
    use InteractsWithForms, FormTrait, AuthorizesRequests;

    protected function getFormModel(){
        return $this->_generate_form_model();
    }
  
    protected function getFormStatePath(): string 
    {
        return 'data';
    } 

    public function saveAndStayResponse()
    {
        
    }

    public function saveAndGoBackResponse()
    {
        return redirect()->back();
    }
}
