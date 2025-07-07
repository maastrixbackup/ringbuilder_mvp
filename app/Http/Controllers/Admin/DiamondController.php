<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiamondShape;
use Illuminate\Http\Request;

class DiamondController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function diamondShapeList()
    {
        $shapes = DiamondShape::orderBy('id')->get();
        return view('admin.diamond_shape.list', compact('shapes'));
    }

    public function createDShape()
    {
        return view('admin.diamond_shape.add');
    }
    public function storeDShape(Request $request)
    {
        $request->validate(['title' => 'required|unique:diamond_shapes,title']);
        return back();
    }
}
