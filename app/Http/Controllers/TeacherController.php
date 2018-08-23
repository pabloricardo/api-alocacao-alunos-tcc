<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\Teacher;
use App\Student;
use App\User;
use App\NotificableTeacher;

use App\Service\UserService;

class TeacherController extends Controller
{
    private $service;
    private $student;
    private $notificableTeacher;
    private $user;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->service = new UserService();
        $this->student = new Student();
        $this->notificableTeacher = new NotificableTeacher();
        $this->user = new User();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try
        {
            $user = $this->service->getTeacher($id);

            if($user == false)
            {
                return response()->json(["data" => false, "error" => "Teacher not found"]);
            }

            return response()->json(['data' => true, 'user' => $user['user'], 'teacher' => $user['teacher']]);
        }
        catch (\Exception $e)
        {
            return response()->json(["data" => false, "error" => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try
        {
            $this->service->getTeacherLogged($request);

            $user = $this->service->getTeacher($id);

            if($user == false)
            {
                return response()->json(["data" => false, "error" => "Teacher not found"]);
            }

            \DB::beginTransaction();

            $user['user']->fill($request->all());
            $user['user']->save();
            
            $user['teacher']->fill($request->all());
            $user['teacher']->save();

            \DB::commit();
            return response()->json(['data' => true, 'message' => 'Teacher updated']);
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            return response()->json(["data" => false, "error" => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $this->service->getTeacherLogged($request);

            $user = $this->service->getTeacher($id);

            if($user == false)
            {
                return response()->json(["data" => false, "error" => "Teacher not found"]);
            }

            \DB::beginTransaction();

            $user['teacher']->delete();
            $user['user']->delete();

            \DB::commit();

            return response()->json(['data' => true, 'message' => 'Teacher deleted']);
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            return response()->json(["data" => false, "error" => $e->getMessage()]);
        }
    }

    /**
     * Function for teacher accept request of student
     * *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id Teacher
     */
    public function teacherAcepptStudent($answer, $id)
    {
        try
        {            
            \DB::beginTransaction();

            $notificable = $this->notificableTeacher->find($id);
            
            if(!$notificable)
            {
                return response()->json(["data" => false, "error" => "Error on request"]);
            }
            
            $notificable->update([
                'teacherGuide' => $answer,
                'answered' => 'YES'
            ]);
            
            /**
             * fazer update na tabela de usuarios com o id do professor
             */
            if($answer == "YES")
            {
                $student = $this->student->find($notificable->studentId);

                if(!$student)
                {
                    \DB::rollBack();
                    return response()->json(["data" => false, "error" => "Error on request"]);
                }

                $student->update([
                    'teacherId' => $notificable->teacherId
                ]);
            }

            \DB::commit();
            return response()->json(["data" => true, "message" => "Answer ok!"]);
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            return response()->json(["data" => false, "error" => $e->getMessage()]);
        }
    }
}
