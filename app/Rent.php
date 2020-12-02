<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    protected $guarded = ['id'];

    public function rented_books()
    {
        return $this->hasMany(RentedBook::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
