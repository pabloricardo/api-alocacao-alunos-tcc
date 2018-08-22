<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Area;
use App\OcupationArea;

class AreaController extends Controller
{
    private $area;
    private $ocupationArea;

    public function __construct()
    {
        $this->area = new Area();
        $this->ocupationArea = new OcupationArea();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $areas = $this->area->get();

        return response()->json(["data" => true, "area" => $area]);
    }

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
    {
        try
        {
            //$newArea = $this->area->fill($request->all());
            //$newArea->save();
            $newArea = $this->area->create([
                'name' => $request->get('name')
            ]);


            return response()->json(["data" => true, "message" => "New area created successfully"]);
        }
        catch(\Exeception $e)
        {
            return response()->json(["data" => false, "error" => $e->getMessage()]);
        }
    }

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
            $showArea = $this->area->find($id);

            if (!$showArea)
            {
                return response()->json(["data" => false, "error" => "Area not found"]);
            }
            else
            {
                return response()->json(["data" => true, "areas" => $showArea]);
            }
        } 
        catch (\Exeception $e)
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
            $updateArea = $this->area->find($id);

            if(!$updateArea)
            {
                return response()->json(["data" => false, "error" => "Area not found"]);
            }

            $newArea = $updateArea->fill($request->all());

            $newArea->save();

            return response()->json(["data" => true, "message" => "Area updated"]);
        }
        catch (\Exeception $e)
        {
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
            $deleteArea = $this->area->find($id);

            if(!$deleteArea)
            {
                return response()->json(["data" => false, "error" => "Area not found"]);
            }

            $deleteArea->delete();

            return response()->json(["data" => true, "message" => "Area and your dependecies were deleted successfully"]);

        } 
        catch (\Exeception $e)
        {
            return response()->json(["data" => false, "error" => $e->getMessage()]);
        }
    }
}
