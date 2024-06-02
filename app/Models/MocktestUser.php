<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MocktestUser extends Model
{
    use HasFactory;

    protected $table = 'mocktest_user';

    public function mocktest(): BelongsTo
    {
        return $this->belongsTo(Mocktest::class);
    }


    public function userAnswer(){
        return $this->belongsToMany(Question::class, 'mocktest_answer');
    }

}
