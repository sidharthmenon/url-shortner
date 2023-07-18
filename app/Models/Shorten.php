<?php

namespace App\Models;

use App\Traits\AutoFill;
use App\Traits\HashId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shorten extends Model
{
    use HasFactory, AutoFill, HashId;
}
