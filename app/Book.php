<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $guarded = ['id'];

    public function last_position_user()
    {
        return $this->belongsTo(User::class, 'last_position');
    }
}
