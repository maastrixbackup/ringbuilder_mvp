<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JewelleryKarat;
use App\Models\Ring;
use App\Models\RingColor;
use App\Models\RingSize;
use App\Models\RingStyle;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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
        $karats = JewelleryKarat::latest()->get();
        $colors = RingColor::latest()->get();
        $size = RingSize::latest()->get();
        $style = RingStyle::latest()->get();

        return view('admin.rings.add', compact('karats', 'colors', 'size', 'style'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:rings,title',
            'slug' => 'required',
            'ring_price' => 'required',
            'ring_color' => 'required',
            'ring_style' => 'required',
            'ring_size' => 'required',
            'ring_karat' => 'required'
        ]);

        try {
            $ring = new Ring();
            $ring->title = $request->title;
            $ring->slug = $request->slug;
            $ring->sku = $this->generateSku('RING');
            $ring->ring_color = $request->ring_color;
            $ring->ring_style = $request->ring_style;
            $ring->ring_size = $request->ring_size;
            $ring->ring_karat = $request->ring_karat;
            $ring->ring_price = $request->ring_price;
            $ring->save();

            return redirect()->route('admin.rings.index')->with('success', 'Ring Added Successfully');
        } catch (\Throwable $th) {
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
        $karats = JewelleryKarat::latest()->get();
        $colors = RingColor::latest()->get();
        $size = RingSize::latest()->get();
        $style = RingStyle::latest()->get();
        $ring = Ring::find($id);

        return view('admin.rings.edit', compact('karats', 'colors', 'size', 'style', 'ring'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|unique:rings,title,' . $id,
            'slug' => 'required',
            'ring_price' => 'required',
            'ring_color' => 'required',
            'ring_style' => 'required',
            'ring_size' => 'required',
            'ring_karat' => 'required'
        ]);

        try {
            $ring = Ring::find($id);
            $ring->title = $request->title;
            $ring->slug = $request->slug;
            $ring->ring_color = $request->ring_color;
            $ring->ring_style = $request->ring_style;
            $ring->ring_size = $request->ring_size;
            $ring->ring_karat = $request->ring_karat;
            $ring->ring_price = $request->ring_price;
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
