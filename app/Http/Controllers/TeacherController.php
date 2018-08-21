<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Teacher;
use App\User;

use App\Service\UserService;

class TeacherController extends Controller
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
            $user = $this->service->getTeacher($id);

            if($user == false)
            {
                return response()->json(["data" => false, "error" => "Teacher not found"]);
            }

            return response()->json(['data' => true, 'user' => [$user['user'], 'teacher' => $user['teacher']]]);
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
            $user = $this->service->getTeacher($id);

            if($user == false)
            {
                return response()->json(["data" => false, "error" => "Teacher not found"]);
            }

            \DB::beginTransaction();

            $user['user']->fill($request->all());
            $user['user']->save();

            $optionsTeacher = [];

            if($request->get('type') != "")
            {
                $optionsTeacher['type'] = $request->get('type');
            }
            if($request->get('studentLimit') != "")
            {
                $optionsTeacher['studentLimit'] = $request->get('studentLimit');
            }

            $user['user']->teachers()->update($optionsTeacher);

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
            $user = $this->service->getStudent($id);

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
}
