<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function videos(){
        return $this->hasMany(Lesson::class);
    }
    public function chapterItems(){
        return $this->hasMany(ChapterItem::class);
    }


}
