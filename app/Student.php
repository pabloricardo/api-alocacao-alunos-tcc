<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'curse',
        'userId',
        'teacherId',
    ];

    protected $hidden = [
        'id'
    ];

    /**
     * Table user relationship with phones
     */
    public function users()
    {
        return $this->belongsTo('App\User', 'userId');
    }
    
    /**
     * Table user relationship with phones
     */
    public function teachers()
    {
        return $this->belongsTo('App\Teacher', 'teacherId');
    }
}
