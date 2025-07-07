<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JewelleryKarat;
use App\Models\RingColor;
use App\Models\RingSize;
use App\Models\RingStyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RingStyleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rStyles = RingStyle::orderBy('id')->get();
        return view('admin.ring_style.list', compact('rStyles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ring_style.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:ring_styles,title',
            'image' => 'nullable|image|max:2048',
        ]);


        DB::beginTransaction();
        try {
            $rStyles = new RingStyle();
            $rStyles->title = $request->title;

            $destinationPath = public_path('storage/images/ring_styles/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = 'style_img_' . time() . '.' . $file->getClientOriginalExtension();


                if ($file->move($destinationPath, $fileName)) {
                    $rStyles->style_image = $fileName;
                } else {
                    DB::rollBack();
                    return back()->with('error', 'Failed to create image.');
                }
            }

            $rStyles->save();
            DB::commit();
            return redirect()->route('admin.ring-style.index')->with('success', 'Style Added Successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
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
        $style = RingStyle::find($id);
        return view('admin.ring_style.edit', compact('style'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|unique:ring_styles,title,' . $id,
            'image' => 'nullable|image|max:2048',
        ]);


        DB::beginTransaction();
        try {
            $rStyles =  RingStyle::find($id);
            $rStyles->title = $request->title;

            $destinationPath = public_path('storage/images/ring_styles/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = 'style_img_' . time() . '.' . $file->getClientOriginalExtension();


                // Delete old file first
                if (!empty($rStyles->style_image)) {
                    $oldFilePath = $destinationPath . $rStyles->style_image;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }


                if ($file->move($destinationPath, $fileName)) {
                    $rStyles->style_image = $fileName;
                } else {
                    DB::rollBack();
                    return back()->with('error', 'Failed to create image.');
                }
            }

            $rStyles->save();
            DB::commit();
            return redirect()->route('admin.ring-style.index')->with('success', 'Style Updated Successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $rStyles = RingStyle::find($id);

            $destinationPath = public_path('storage/images/ring_styles/');
            // Delete old file first
            if (!empty($rStyles->style_image)) {
                $oldFilePath = $destinationPath . $rStyles->style_image;
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            $rStyles->delete();

            return back()->with('success', 'Style Deleted');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function ringSize()
    {
        $rSizes = RingSize::orderBy('id')->get();
        return view('admin.ring_sizes.list', compact('rSizes'));
    }

    public function ringSizeStore(Request $request)
    {
        if (!$request->size) {
            return back()->with('error', 'Ring Size required');
        }

        $size = RingSize::where('size', $request->size)->first();
        if ($size) {
            return back()->with('error', 'Size already exists');
        }

        DB::beginTransaction();
        try {
            $size = new RingSize();
            $size->size = $request->size;
            $size->save();

            DB::commit();
            return back()->with('success', 'Size added Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function ringSizeEdit($id)
    {
        $size = RingSize::find($id);
        return response()->json(['status' => true, 'data' => $size]);
    }

    public function ringSizeUpdate(Request $request, $id)
    {
        if (!$request->size) {
            return back()->with('error', 'Ring Size required');
        }

        $size = RingSize::where('size', $request->size)->where('id', '!=', $id)->first();
        if ($size) {
            return back()->with('error', 'Size already exists add different');
        }

        try {
            $size = RingSize::findOrFail($id);
            $size->size = $request->size;
            $size->save();

            return back()->with('success', 'Size Updated Successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function ringSizeDelete($id)
    {
        $size = RingSize::find($id);
        $size->delete();
        return back()->with('success', 'Size Deleted');
    }

    public function jewelleryKarat()
    {
        $karats = JewelleryKarat::orderBy('id')->get();
        return view('admin.jewellery_karats.list', compact('karats'));
    }

    public function jewelleryKaratStore(Request $request)
    {
        if (!$request->karat) {
            return back()->with('error', 'Ring karat required');
        }

        $k = JewelleryKarat::where('karat', $request->karat)->first();
        if ($k) {
            return back()->with('error', 'Karat already exists add different');
        }

        try {
            $k = new JewelleryKarat();
            $k->karat = $request->karat;
            $k->save();

            return back()->with('success', ' Added Successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function jewelleryKaratEdit($id)
    {
        $karat = JewelleryKarat::findOrFail($id);
        return response()->json(['status' => true, 'data' => $karat]);
    }

    public function jewelleryKaratUpdate(Request $request, $id)
    {
        if (!$request->karat) {
            return back()->with('error', 'Ring karat required');
        }

        $k = JewelleryKarat::where('karat', $request->karat)->where('id', '!=', $id)->first();
        if ($k) {
            return back()->with('error', 'Karat already exists add different');
        }

        try {
            $k = JewelleryKarat::find($id);
            $k->karat = $request->karat;
            $k->save();

            return back()->with('success', ' Updated Successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function jewelleryKaratDelete($id)
    {
        JewelleryKarat::find($id)->delete();
        return back()->with('success', 'Deleted Successfully');
    }

    public function ringColor()
    {
        $ringColors = RingColor::orderBy('id')->get();
        return view('admin.ring_colors.list', compact('ringColors'));
    }

    public function ringColorStore(Request $request)
    {
        if (!$request->color_name) {
            return back()->with('error', 'Ring Color required');
        }

        $ringColor = RingColor::where('color_name', $request->color_name)
            ->where('color_code', $request->color_code)
            ->first();
        if ($ringColor) {
            return back()->with('error', 'Karat already exists add different');
        }

        try {
            $ringColor = new RingColor();
            $ringColor->color_name = $request->color_name;
            $ringColor->color_code = $request->color_code;
            $ringColor->save();

            return back()->with('success', 'Color Added Successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function ringColorEdit($id)
    {
        $rc = RingColor::find($id);
        return response()->json(['status' => true, 'data' => $rc]);
    }

    public function ringColorUpdate(Request $request, $id)
    {
        if (!$request->color_name) {
            return back()->with('error', 'Ring Color required');
        }

        $ringColor = RingColor::where('color_name', $request->color_name)->where('color_code', $request->color_code)->where('id', '!=', $id)->first();
        if ($ringColor) {
            return back()->with('error', 'Karat already exists add different');
        }

        try {
            $rc = RingColor::find($id);
            $rc->color_name = $request->color_name;
            $rc->save();
            return back()->with('success', 'Color Updated Successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function ringColorDelete($id)
    {
        RingColor::find($id)->delete();
        return back()->with('success', 'Color Deleted');
    }
}
