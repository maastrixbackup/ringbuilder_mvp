<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiamondColor;
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
        // Optional: Validate input
        // $request->validate(['title' => 'required|string']);
        $shapeNames = array_filter(array_map('trim', explode(',', $request->title)));
        // dd($shapeNames);
        try {
            foreach ($shapeNames as $shapeName) {
                if (!DiamondShape::where('title', $shapeName)->exists()) {
                    $shape = new DiamondShape();
                    $shape->title = $shapeName;
                    $shape->save();
                }
            }

            return redirect()->route('admin.diamond-shapes')->with('success', 'Shape(s) added successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function deleteDiamondShape($id)
    {
        try {
            DiamondShape::find($id)->delete();
            return back()->with('success', 'Data deleted');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function diamondColorList()
    {
        $colors = DiamondColor::orderBy('id')->get();
        return view('admin.diamond_color.list', compact('colors'));
    }

    public function createDColor()
    {
        return view('admin.diamond_color.add');
    }

    public function storeDColor(Request $request)
    {
        // Optional: Validate input
        $request->validate(['title' => 'required|string|unique:diamond_colors,title']);
        try {
            $color = new DiamondColor();
            $color->title = $request->title;
            $color->color_code = $request->color_code;
            $color->save();


            return redirect()->route('admin.diamond-colors')->with('success', 'Color(s) added successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function diamondColorEdit($id)
    {
        $dc = DiamondColor::find($id);
        return response()->json(['status' => true, 'data' => $dc]);
    }

    public function diamondColorUpdate(Request $request, $id)
    {
        // Optional: Validate input
        $request->validate([
            'title' => 'required|string|unique:diamond_colors,title,' . $id,
            'color_code' => 'required'
        ]);
        try {
            $color = DiamondColor::find($id);
            $color->title = $request->title;
            $color->color_code = $request->color_code;
            $color->save();


            return redirect()->route('admin.diamond-colors')->with('success', 'Color(s) Updated successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function deleteDiamondColor($id)
    {
        try {
            DiamondColor::find($id)->delete();
            return back()->with('success', 'Data deleted');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
