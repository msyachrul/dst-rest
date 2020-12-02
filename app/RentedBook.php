<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RentedBook extends Model
{
    protected $guarded = ['id'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function rent()
    {
        return $this->belongsTo(Rent::class);
    }
}
