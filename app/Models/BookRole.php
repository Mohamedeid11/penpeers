<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookRole extends Model
{
    use HasFactory, Translatable;
    public $trans_fields = ['display_name'];

}
