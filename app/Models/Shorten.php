<?php

namespace App\Models;

use App\Traits\AutoFill;
use App\Traits\HashId;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('url', 'code', 'user_id')]
class Shorten extends Model
{
    use  AutoFill, HashId;

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function getLinkAttribute(){
        return "https://ksum.in/".$this->code;
    }
}
