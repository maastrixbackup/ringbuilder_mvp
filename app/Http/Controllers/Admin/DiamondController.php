<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiamondColor;
use App\Models\DiamondCut;
use App\Models\DiamondProduct;
use App\Models\DiamondShape;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiamondController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $diamonds = DiamondProduct::orderByDesc('id')->get();
        return view('admin.diamon_products.list', compact('diamonds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dShapes = DiamondShape::orderBy('id')->get();
        return view('admin.diamon_products.add', compact('dShapes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $destinationPath = public_path('storage/images/diamonds/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $sku = $this->generateSku('DMND');
            $slug = $this->slugChecker($request->slug);
            $dImage = $this->handleImageUpload($request, 'image_one', $destinationPath, 'Diamond_' . $request->shape . '-' . $request->carat . 'Carat_');
            $diamond = new DiamondProduct();
            $diamond->title = $request->title;
            $diamond->slug = $slug . '-' . $sku;
            $diamond->sku = $sku;
            $diamond->img_one = $dImage;
            $diamond->shape = $request->shape;
            $diamond->grown_type = $request->grown_type;
            $diamond->cut = $request->cut;
            $diamond->color = $request->color;
            $diamond->carat = $request->carat;
            $diamond->clarity = $request->clarity;
            $diamond->price = $request->price;

            $diamond->save();

            DB::commit();
            return redirect()->route('admin.diamonds.index')->with('success', 'Diamond Created');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
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
        $diamond = DiamondProduct::find($id);
        $dShapes = DiamondShape::orderBy('id')->get();
        return view('admin.diamon_products.edit', compact('diamond', 'dShapes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {

            $diamond = DiamondProduct::find($id);
            $diamond->title = $request->title;
            $diamond->slug = $request->slug;
            $diamond->shape = $request->shape;
            $diamond->grown_type = $request->grown_type;
            $diamond->cut = $request->cut;
            $diamond->color = $request->color;
            $diamond->carat = $request->carat;
            $diamond->clarity = $request->clarity;
            $diamond->price = $request->price;

            $destinationPath = public_path('storage/images/diamonds/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            if ($request->hasFile('image_one')) {
                $file = $request->file('image_one');
                $fileName = 'Diamond_' . $request->shape . '-' . $request->carat . 'Carat_' . time() . '.' . $file->getClientOriginalExtension();

                // Delete old file first
                if (!empty($diamond->img_one)) {
                    $oldFilePath = $destinationPath . $diamond->img_one;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                if ($file->move($destinationPath, $fileName)) {
                    $diamond->img_one = $fileName;
                }
            }

            $diamond->save();

            DB::commit();
            return redirect()->route('admin.diamonds.index')->with('success', 'Diamond Updated');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $diamond = DiamondProduct::find($id);
            $destinationPath = public_path('storage/images/diamonds/');
            // Delete old file first
            if (!empty($diamond->img_one)) {
                $oldFilePath = $destinationPath . $diamond->img_one;
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            $diamond->delete();
            return back()->with('success', 'Diamond Deleted');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
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
        try {

            $destinationPath = public_path('storage/images/shapes/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $shape =  new DiamondShape();
            $shape->title = $request->title;


            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = $request->title . '_img_' . time() . '.' . $file->getClientOriginalExtension();

                if ($file->move($destinationPath, $fileName)) {
                    $shape->shape_image = $fileName;
                }
            }
            $shape->save();
            return redirect()->route('admin.diamond-shapes')->with('success', 'Shape(s) updated successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }


    public function editDShape($id)
    {
        $shape = DiamondShape::find($id);
        return view('admin.diamond_shape.edit', compact('shape'));
    }

    public function updateDShape(Request $request, $id)
    {
        try {
            $destinationPath = public_path('storage/images/shapes/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $shape =  DiamondShape::find($id);
            $shape->title = $request->title;


            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = $request->title . '_img_' . time() . '.' . $file->getClientOriginalExtension();

                // Delete old file first
                if (!empty($shape->shape_image)) {
                    $oldFilePath = $destinationPath . $shape->shape_image;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                if ($file->move($destinationPath, $fileName)) {
                    $shape->shape_image = $fileName;
                }
            }
            $shape->save();
            return redirect()->route('admin.diamond-shapes')->with('success', 'Shape(s) updated successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function deleteDiamondShape($id)
    {
        try {
            $shape =  DiamondShape::find($id);

            $destinationPath = public_path('storage/images/shapes/');
            // Delete old file first
            if (!empty($shape->shape_image)) {
                $oldFilePath = $destinationPath . $shape->shape_image;
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $shape->delete();
            return back()->with('success', 'Data deleted');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function diamondCutList()
    {
        $cuts = DiamondCut::orderBy('id')->get();
        return view('admin.diamond_cut.list', compact('cuts'));
    }

    public function storeDiamondCut(Request $request)
    {
        if (!$request->cut) {
            return back()->with('error', 'Diamond cut is required');
        }

        $diamondCut = DiamondCut::where('cut', $request->cut)->first();
        if ($diamondCut) {
            return back()->with('error', 'Cut already exists add different');
        }

        try {
            $diamondCut = new DiamondCut();
            $diamondCut->cut = $request->cut;
            $diamondCut->save();

            return back()->with('success', 'Cut Added Successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function editDiamondCut($id)
    {
        $dc = DiamondCut::find($id);
        return response()->json(['status' => true, 'data' => $dc]);
    }

    public function updateDiamondCut(Request $request, $id)
    {
        if (!$request->cut) {
            return back()->with('error', 'Diamond cut required');
        }

        $diamondCut = DiamondCut::where('cut', $request->cut)->where('id', '!=', $id)->first();
        if ($diamondCut) {
            return back()->with('error', 'Cut already exists add different');
        }

        try {
            $dc = DiamondCut::find($id);
            $dc->cut = $request->cut;
            $dc->save();
            return back()->with('success', 'Cut Updated Successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function deleteDiamondCut($id)
    {
        DiamondCut::find($id)->delete();
        return back()->with('success', 'Cut Deleted');
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

    public function slugChecker($slug)
    {
        $originalSlug = $slug;
        $count = 1;

        while (
            DiamondProduct::where('slug', $slug)
            // ->when($id, fn($query) => $query->where('id', '!=', $id))
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    public function generateSku($categoryCode = 'ABC')
    {

        // Generate a random 3-digit number
        $numberPart = mt_rand(100, 999);

        // Generate a 3-character random string
        $randomPart = strtoupper(Str::random(3)); // e.g. XYZ

        // Combine all parts
        $sku = 'SKU-' . strtoupper($categoryCode) . $numberPart . $randomPart;

        // Optional: check uniqueness (if stored in DB)
        while (DiamondProduct::where('sku', $sku)->exists()) {
            $numberPart = mt_rand(100, 999);
            $randomPart = strtoupper(Str::random(3));
            $sku = 'SKU-' . strtoupper($categoryCode) . $numberPart . $randomPart;
        }
        return $sku;
    }

    private function handleImageUpload(Request $request, $fieldName, $destinationPath, $prefix)
    {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);
            $fileName = $prefix . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            if ($file->move($destinationPath, $fileName)) {
                return $fileName;
            }
        }
        return null;
    }
}
