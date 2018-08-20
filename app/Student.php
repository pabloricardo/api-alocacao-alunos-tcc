<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'curse',
        'userId',
        'teacthcerId',
    ];

    /**
     * Table user relationship with phones
     */
    public function users()
    {
        return $this->belongsTo('App\User');
    }
}
