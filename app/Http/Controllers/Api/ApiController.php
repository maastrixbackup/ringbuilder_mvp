<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiamondProduct;
use App\Models\DiamondShape;
use App\Models\JewelleryKarat;
use App\Models\Ring;
use App\Models\RingColor;
use App\Models\RingSize;
use App\Models\RingStyle;
use App\Models\RingWidth;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function ringProducts(Request $request)
    {
        $productQuery = Ring::orderBy('id');

        if ($request->has('ring_karat')) {
            $productQuery->where('ring_karat', $request->ring_karat);
        }

        if ($request->has('ring_style')) {
            $productQuery->where('ring_style', $request->ring_style);
        }

        $karats = JewelleryKarat::orderBy('id')->get()->map(function ($k) {
            return [
                'id' => $k->id,
                'title' => $k->karat,
            ];
        });

        $colors = RingColor::orderBy('id')->get()->map(function ($c) {
            return [
                'id' => $c->id,
                'name' => $c->color_name,
                'code' => $c->color_code,
            ];
        });

        $width = RingWidth::orderBy('id')->get()->map(function ($w) {
            return [
                'id' => $w->id,
                'width' => $w->width,
            ];
        });

        $size = RingSize::orderBy('id')->get()->map(function ($s) {
            return [
                'id' => $s->id,
                'size' => $s->size,
            ];
        });

        $style = RingStyle::orderBy('id')->get()->map(function ($st) {
            return [
                'id' => $st->id,
                'title' => $st->title,
                'image' => $st->style_image ? url('/storage/images/ring_styles/' . $st->style_image) : ''
            ];
        });

        $shapes = DiamondShape::orderBy('id')->get()->map(function ($sp) {
            return [
                'id' => $sp->id,
                'title' => $sp->title,
            ];
        });

        $products = $productQuery->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'title' => $p->title,
                'slug' => $p->slug,
                'ring_price' => $p->ring_price,
                'ring_karat' => $p->ring_karat,
                'ring_color' => $p->ring_color,
                'ring_width' => $p->ring_width,
                'ring_style' => $p->ring_style,
                'ring_size' => $p->ring_size,
                'd_shape' => $p->diamond_shape,
                'normal_image' => asset('storage/images/rings/' . $p->ring_image),
                'hover_image' => asset('storage/images/rings/' . $p->ring_hover_img),
                // Add more fields as needed
            ];
        });

        $data = [
            'style' => $style,
            'colors' => $colors,
            'width' => $width,
            'size' => $size,
            'karats' => $karats,
            'shapes' => $shapes,
            'products' => $products,
        ];


        return response()->json(['status' => true, 'data' => $data]);
    }

    public function diamondProducts(Request $request)
    {
        $productQuery = DiamondProduct::orderByDesc('id');

        if ($request->has('shape')) {
            $productQuery->where('shape', $request->shape);
        }

        $products = $productQuery->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'title' => $p->title,
                'slug' => $p->slug,
                'price' => $p->price,
                'main_image' => asset('storage/images/diamonds/' . $p->img_one),
                // Add more fields as needed
            ];
        });


        $shapes = DiamondShape::orderBy('id')->get()->map(function ($sp) {
            return [
                'id' => $sp->id,
                'title' => $sp->title,
                'image' => asset('storage/images/shapes/' . $sp->shape_image),
            ];
        });

        $data = [
            'shapes' => $shapes,
            'products' => $products,
        ];

        return response()->json(['status' => true, 'data' => $data]);
    }
}
