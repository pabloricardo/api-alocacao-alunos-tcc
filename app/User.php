<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'name',
        'email',
        'idRegistration',
        'cpf',
        'is_verified'
    ];

    protected $hidden = [
        'id',
        'updated_at'
    ];
    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Table student relationship with user
     */
    public function students()
    {
        return $this->hasMany('App\Student', 'userId');
    }

    /**
     * Table teachers relationship with user
     */
    public function teachers()
    {
        return $this->hasMany('App\Teacher', 'userId');
    }
}
