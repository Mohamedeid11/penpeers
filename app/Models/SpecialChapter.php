<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialChapter extends Model
{
    use HasFactory, Translatable;
    public $trans_fields = ['display_name'];
    public function books(){
        return $this->belongsToMany(Book::class, 'book_special_chapters')->withPivot(['edition_id']);
    }
    public function scopeDedications($query){
        $query->where('name', 'dedications');
    }
    public function scopeAcknowledgments($query){
        $query->where('name', 'acknowledgments');
    }
    public function scopeIntro($query){
        $query->where('name', 'intro');
    }

}
