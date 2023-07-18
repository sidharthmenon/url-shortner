<?php

namespace App\Traits;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

trait ActionTrait{

  use AuthorizesRequests;

  public $actions = [];

  public function getActions(){
    $authorized_actions = [];

    foreach($this->actions as $action){
      $req_action = $action['perms'];

      if($req_action){
        if(auth()->user()->can($req_action)){
          array_push($authorized_actions, $action);
        }
      }
      else{
        array_push($authorized_actions, $action);
      }

    }

    if(count($authorized_actions)){
      return $authorized_actions;
    }

    return false;

  }

}
