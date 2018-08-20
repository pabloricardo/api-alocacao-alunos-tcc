<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teatcher extends Model
{
    protected $fillable = [
        'type',
        'studentLimit',
        'userId',
    ];

    /**
     * Table user relationship with phones
     */
    public function users()
    {
        return $this->belongsTo('App\User');
    }
}
