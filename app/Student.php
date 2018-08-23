<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Student extends Model
{
    protected $fillable = [
        'curse',
        'userId',
        'teacherId',
    ];

    protected $hidden = [
        'updated_at',
        'user_id'
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

    /**
     * Table teacher relationship with user
     */
    public function notificableTeacher()
    {
        return $this->belongsTo('App\NotificableTeacher', 'studentId');
    }

    /**
     * get number of students by teacher id
     */
    public function getNumberStudetnsByTeacher($teacherId)
    {
        $data = DB::table('students')
            ->select(DB::raw('count(*) as numberStudents'))
            ->where('teacherId', $teacherId)
            ->get();
        
        return $data;
    }

}
