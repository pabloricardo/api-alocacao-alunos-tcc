<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Area;
use App\OcupationArea;
use App\Response\Response;

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

        return response()->json(Response::toString(true, '', $area));
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
            $newArea = $this->area->create([
                'nome' => $request->get('nome')
            ]);

            return response()->json(Response::toString(true, 'Area criada com sucesso'));
        }
        catch(\Exeception $e)
        {
            return response()->json(Response::toString(false, $e->getMessage()));
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
                return response()->json(Response::toString(false, "Area not found"));
            }
            else
            {
                return response()->json(Response::toString(true, '', $showArea));
            }
        } 
        catch (\Exeception $e)
        {
            return response()->json(Response::toString(false, $e->getMessage()));
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
                return response()->json(Response::toString(false, "Area not found"));
            }

            $newArea = $updateArea->fill($request->all());

            $newArea->save();

            return response()->json(Response::toString(true, 'Area updated'));
        }
        catch (\Exeception $e)
        {
            return response()->json(Response::toString(false, $e->getMessage()));
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
                return response()->json(Response::toString(false, "Area not found"));
            }

            $deleteArea->delete();

            return response()->json(Response::toString(true, 'Area and your dependecies were deleted successfully'));

        } 
        catch (\Exeception $e)
        {
            return response()->json(Response::toString(false, $e->getMessage()));
        }
    }
}
