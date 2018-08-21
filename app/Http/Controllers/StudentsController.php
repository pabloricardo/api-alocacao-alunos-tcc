<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Student;
use App\User;

use App\Service\UserService;

class StudentsController extends Controller
{
    private $service;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->service = new UserService();
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
    public function show($id)
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
            $user = $this->service->getStudent($id);

            if($user == false)
            {
                return response()->json(["data" => false, "error" => "Student not found"]);
            }

            \DB::beginTransaction();

            $user['user']->fill($request->all());
            $user['user']->save();

            $optionsStudent = [];

            if($request->get('curse') != "")
            {
                $optionsStudent['curse'] = $request->get('curse');
            }
            if($request->get('idTeacher') != "")
            {
                $optionsStudent['idTeacher'] = $request->get('idTeacher');
            }

            $user['user']->students()->update($optionsStudent);

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
}
