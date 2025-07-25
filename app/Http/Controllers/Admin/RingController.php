<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiamondShape;
use App\Models\JewelleryKarat;
use App\Models\Ring;
use App\Models\RingColor;
use App\Models\RingSize;
use App\Models\RingStyle;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rings = Ring::latest()->get();
        return view('admin.rings.list', compact('rings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $karats = JewelleryKarat::orderBy('id')->get();
        $colors = RingColor::orderBy('id')->get();
        $size = RingSize::orderBy('id')->get();
        $style = RingStyle::orderBy('id')->get();
        $shapes = DiamondShape::orderBy('id')->get();

        return view('admin.rings.add', compact('karats', 'colors', 'size', 'style', 'shapes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required',
            'ring_color' => 'required',
            'ring_style' => 'required',
            'ring_size' => 'required',
            'ring_karat' => 'required',
            'd_shape' => 'required|array',
            'ring_price' => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            $destinationPath = public_path('storage/images/rings/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $slug = $this->slugChecker($request->slug);
            $ringImage = $this->handleImageUpload($request, 'ring_image', $destinationPath, 'r_img_');
            $ringHoverImage = $this->handleImageUpload($request, 'ring_hover_img', $destinationPath, 'rh_img_');

            foreach ($request->d_shape as $key => $shape) {
                $ring = new Ring();
                $ring->title = $request->title;
                $ring->slug = $slug;
                $ring->sku = $this->generateSku('RING');
                $ring->ring_color = $request->ring_color;
                $ring->ring_style = $request->ring_style;
                $ring->ring_size = $request->ring_size;
                $ring->ring_karat = $request->ring_karat;
                $ring->diamond_shape = $shape;
                $ring->ring_price = $request->ring_price[$key] ?? 0;
                $ring->ring_image = $ringImage;
                $ring->ring_hover_img = $ringHoverImage;
                $ring->save();
            }

            DB::commit();
            return redirect()->route('admin.rings.index')->with('success', 'Ring(s) added successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Failed to add ring: ' . $th->getMessage());
        }
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
        $karats = JewelleryKarat::orderBy('id')->get();
        $colors = RingColor::orderBy('id')->get();
        $size = RingSize::orderBy('id')->get();
        $style = RingStyle::orderBy('id')->get();
        $shapes = DiamondShape::orderBy('id')->get();
        $ring = Ring::find($id);

        return view('admin.rings.edit', compact('karats', 'colors', 'size', 'style', 'ring', 'shapes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required', // Exclude current record's ID
            'slug' => 'required|string|unique:rings,title,' . $id,
            'ring_price' => 'required|numeric',
            'd_shape' => 'required|string',
            'ring_color' => 'required|string',
            'ring_style' => 'required|string',
            'ring_size' => 'required|string',
            'ring_karat' => 'required|string'
        ]);


        try {

            $destinationPath = public_path('storage/images/rings/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $ring = Ring::find($id);
            $ring->title = $request->title;
            $ring->slug = $request->slug;
            $ring->ring_color = $request->ring_color;
            $ring->ring_style = $request->ring_style;
            $ring->ring_size = $request->ring_size;
            $ring->ring_karat = $request->ring_karat;
            $ring->diamond_shape = $request->d_shape;
            $ring->ring_price = $request->ring_price;

            if ($request->hasFile('ring_image')) {
                $file = $request->file('ring_image');
                $fileName = 'r_img_' . time() . '.' . $file->getClientOriginalExtension();

                // Delete old file first
                if (!empty($ring->ring_image)) {
                    $oldFilePath = $destinationPath . $ring->ring_image;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                if ($file->move($destinationPath, $fileName)) {
                    $ring->ring_image = $fileName;
                }
            }

            if ($request->hasFile('ring_hover_img')) {
                $file = $request->file('ring_hover_img');
                $fileName = 'rh_img_' . time() . '.' . $file->getClientOriginalExtension();

                // Delete old file first
                if (!empty($ring->ring_hover_img)) {
                    $oldFilePath = $destinationPath . $ring->ring_hover_img;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                if ($file->move($destinationPath, $fileName)) {
                    $ring->ring_hover_img = $fileName;
                }
            }
            $ring->save();

            return redirect()->route('admin.rings.index')->with('success', 'Ring Updated Successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Ring::find($id)->delete();
            return back()->with('success', 'Data Deleted successfully');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function slugChecker($slug)
    {
        $originalSlug = $slug;
        $count = 1;

        while (
            Ring::where('slug', $slug)
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
        while (Ring::where('sku', $sku)->exists()) {
            $numberPart = mt_rand(100, 999);
            $randomPart = strtoupper(Str::random(3));
            $sku = 'SKU-' . strtoupper($categoryCode) . $numberPart . $randomPart;
        }
        return $sku;

        // Current date
        // $datePart = time(); // 20250710

        // // Get the next product ID (or count + 1 as fallback)
        // $lastProduct = Ring::latest('id')->first();
        // $nextId = $lastProduct ? $lastProduct->id + 1 : 1;

        // // Pad the ID to 5 digits
        // $idPart = str_pad($nextId, 5, '0', STR_PAD_LEFT);

        // // Combine to form SKU
        // $sku = strtoupper($categoryCode) . '-' . $datePart . '-' . $idPart;


    }
}
