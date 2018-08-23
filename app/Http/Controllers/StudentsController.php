<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Student;
use App\User;
use App\NotificableTeacher;
use App\Teacher;
use App\Area;
use Mail;

use App\Service\UserService;

class StudentsController extends Controller
{
    private $service;
    private $notificableTeacher;
    private $teacher;
    private $user;
    private $area;
    private $student;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->service = new UserService();
        $this->notificableTeacher = new NotificableTeacher();
        $this->teacher = new Teacher();
        $this->user = new User();
        $this->area = new Area();
        $this->student = new Student();
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try
        {
            $user = $this->service->getStudent($id);

            if($user == false)
            {
                return response()->json(["data" => false, "error" => "Student not found"]);
            }

            return response()->json(['data' => true, 'user' => [$user['user'], 'student' => $user['student']]]);
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
            $this->service->getStudentLogged($request);

            $user = $this->service->getStudent($id);

            if($user == false)
            {
                return response()->json(["data" => false, "error" => "Student not found"]);
            }

            \DB::beginTransaction();

            $user['user']->fill($request->all());
            $user['user']->save();

            $user['student']->fill($request->all());
            $user['student']->save();

            \DB::commit();
            return response()->json(['data' => true, 'message' => 'Student updated']);
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
            $this->service->getStudentLogged($request);

            $user = $this->service->getStudent($id);

            if($user == false)
            {
                return response()->json(["data" => false, "error" => "Student not found"]);
            }

            \DB::beginTransaction();

            $user['student']->delete();
            $user['user']->delete();

            \DB::commit();

            return response()->json(['data' => true, 'message' => 'Student deleted']);
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            return response()->json(["data" => false, "error" => $e->getMessage()]);
        }
    }

    /**
     * Student request a teacher to guide on TCC
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id id student
     * @return \Illuminate\Http\Response
     */
    public function studentRequestTeacher(request $request, $id)
    {
        try
        {
            \DB::beginTransaction();

            $this->service->getStudentLogged($request);

            $teacherId = $request->get('teacherId');
            $areaId = $request->get('areaId');

            $teacherUse = $this->teacher->find($teacherId);
            $studentUse = $this->student->find($id);

            $numberStudents = $this->student->getNumberStudetnsByTeacher($teacherId);
            
            if(!$teacherUse || $numberStudents[0]->numberStudents >= $teacherUse->studentLimit)
            {
                \DB::rollBack();
                return response()->json(["data" => false, "error" => "Error on request"]);
            }
            
            $userteacher = $this->user->find($teacherUse->userId);
            $userStudent = $this->user->find($studentUse->userId);
            $area        = $this->area->find($areaId);
            
            if (!$userteacher || !$userStudent || !$area)
            {
                \DB::rollBack();
                return response()->json(["data" => false, "error" => "Error on request"]);
            }

            $notificable = $this->notificableTeacher->create([
                'teacherGuide' => 'NO',
                'answered' => 'NO',
                'teacherId' => $teacherId,
                'studentId' => $id,
                'areaId' => $areaId
            ]);

            $name        = $userteacher->name;
            $nameStudent = $userStudent->name;
            $email       = $userteacher->email;
            $nameArea    = $area->name;

            $subject = "New Request of studetn to guide.";

            Mail::send('email.request', ['name' => $name, 'email' => $email, 'nameStudent' => $nameStudent, 'idNotificable' => $notificable->id, "nameArea" => $nameArea],
                function ($mail) use ($email, $name, $subject) {
                    $mail->from("noreplay@alocacaoalunostcc.com", "Alocação alunos TCC");
                    $mail->to($email, $name);
                    $mail->subject($subject);
                }
            );

            \DB::commit();
            return response()->json(["data" => true, "message" => "Notifation send to your teacher"]);
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            return response()->json(["data" => false, "error" => $e->getMessage()]);
        }
    }
}
