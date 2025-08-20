<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiamondClarity;
use App\Models\DiamondColor;
use App\Models\DiamondCut;
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
    // public function ringProducts(Request $request)
    // {
    //     $productQuery = Ring::orderBy('id');

    //     if ($request->has('ring_karat')) {
    //         $productQuery->where('ring_karat', $request->ring_karat);
    //     }

    //     if ($request->has('ring_style')) {
    //         $productQuery->where('ring_style', $request->ring_style);
    //     }

    //     $products = $productQuery->get()->map(function ($p) {
    //         return [
    //             'id' => $p->id,
    //             'title' => $p->title,
    //             'slug' => $p->slug,
    //             'ring_price' => $p->ring_price,
    //             'ring_karat' => $p->ring_karat,
    //             'ring_color' => $p->ring_color,
    //             'ring_width' => $p->ring_width,
    //             'ring_style' => $p->ring_style,
    //             'ring_size' => $p->ring_size,
    //             'd_shape' => $p->diamond_shape,
    //             'normal_image' => asset('storage/images/rings/' . $p->ring_image),
    //             'hover_image' => asset('storage/images/rings/' . $p->ring_hover_img),
    //             // Add more fields as needed
    //         ];
    //     });

    //     $data = [
    //         'products' => $products
    //     ];

    //     return response()->json(['status' => true, 'message' => "Ring products fetched successfully.", 'data' => $data]);
    // }

    public function getRingFilterData(Request $request)
    {
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

        $data = [
            'style' => $style,
            'colors' => $colors,
            'width' => $width,
            'size' => $size,
            'karats' => $karats,
            'shapes' => $shapes
        ];


        return response()->json(['status' => true, 'message' => "Ring filter data fetched successfully.", 'data' => $data]);
    }

    public function ringProducts(Request $request)
    {
        $productQuery = Ring::query();

        // Filtering
        if ($request->filled('ring_style')) {
            $productQuery->where('ring_style', $request->ring_style);
        }

        if ($request->filled('ring_size')) {
            $productQuery->where('ring_size', $request->ring_size);
        }

        if ($request->filled('ring_color')) {
            $productQuery->where('ring_color', $request->ring_color); //
        }

        if ($request->filled('ring_width')) {
            $productQuery->where('ring_width', $request->ring_width); //
        }

        if ($request->filled('diamond_shape')) {
            $productQuery->where('diamond_shape', $request->diamond_shape);
        }

        if ($request->filled('price_from')) {
            $productQuery->where('ring_price', '>=', (float) $request->price_from);
        }

        if ($request->filled('price_to')) {
            $productQuery->where('ring_price', '<=', (float) $request->price_to);
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'PriceAsc':
                    $productQuery->orderBy('ring_price', 'asc');
                    break;
                case 'PriceDesc':
                    $productQuery->orderBy('ring_price', 'desc');
                    break;
                case 'NewArrivals':
                    $productQuery->orderBy('created_at', 'desc');
                    break;
                // case 'BestSeller':
                //     $productQuery->orderBy('sales_count', 'desc');
                //     break;
                default:
                    $productQuery->orderBy('id', 'desc');
            }
        } else {
            $productQuery->orderBy('id', 'desc');
        }

        // Fetch and transform
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
            ];
        });

        return response()->json([
            'status' => true,
            'message' => "Ring products fetched successfully.",
            'data' => ['products' => $products]
        ]);
    }

    public function ringProductDetails($id)
    {
        $product = Ring::find($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Ring product not found.',
            ], 404);
        }

        $data = [
            'id' => $product->id,
            'title' => $product->title,
            'slug' => $product->slug,
            'sku' => $product->sku,
            'ring_style' => $product->ring_style,
            'ring_head_style' => $product->ring_head_style,
            'ring_size' => $product->ring_size,
            'ring_karat' => $product->ring_karat,
            'ring_weight' => $product->ring_weight,
            'ring_color' => $product->ring_color,
            'ring_width' => $product->ring_width,
            'diamond_shape' => $product->diamond_shape,
            'ring_price' => $product->ring_price,
            'ring_image' => asset('storage/images/rings/' . $product->ring_image),
            'ring_hover_img' => asset('storage/images/rings/' . $product->ring_hover_img),
            'status' => $product->status,
        ];

        return response()->json([
            'status' => true,
            'message' => 'Ring product details fetched successfully.',
            'data' => $data
        ]);
    }

    public function getDiamondFilterData(Request $request)
    {
        $shapes = DiamondShape::orderBy('id')->get()->map(function ($sp) {
            return [
                'id' => $sp->id,
                'title' => $sp->title,
                'image' => asset('storage/images/shapes/' . $sp->shape_image),
            ];
        });

        $cuts = DiamondCut::orderBy('id')->get()->map(function ($ct) {
            return [
                'id' => $ct->id,
                'cut' => $ct->cut
            ];
        });

        $color = DiamondColor::orderBy('id')->get()->map(function ($dc) {
            return [
                'id' => $dc->id,
                'title' => $dc->title
            ];
        });

        $clarity = DiamondClarity::orderBy('id')->get()->map(function ($ct) {
            return [
                'id' => $ct->id,
                'clarity' => $ct->clarity
            ];
        });

        $data = [
            'shapes' => $shapes,
            'cuts' => $cuts,
            'color' => $color,
            'clarity' => $clarity
        ];

        return response()->json(['status' => true, 'message' => "Diamond filter data fetched successfully.", 'data' => $data]);
    }

    public function diamondProducts(Request $request)
    {
        $productQuery = DiamondProduct::query();

        // Filtering
        if ($request->filled('grown_type')) {
            $productQuery->where('grown_type', $request->grown_type);
        }

        if ($request->filled('shape')) {
            $productQuery->where('shape', $request->shape);
        }

        if ($request->filled('cut')) {
            $productQuery->where('cut', $request->cut);
        }

        if ($request->filled('color')) {
            $productQuery->where('color', $request->color);
        }

        if ($request->filled('carat_from')) {
            $productQuery->where('carat', '>=', (float) $request->carat_from);
        }

        if ($request->filled('carat_to')) {
            $productQuery->where('carat', '<=', (float) $request->carat_to);
        }

        if ($request->filled('clarity')) {
            $productQuery->where('clarity', $request->clarity);
        }

        if ($request->filled('price_from')) {
            $productQuery->where('price', '>=', (float) $request->price_from);
        }

        if ($request->filled('price_to')) {
            $productQuery->where('price', '<=', (float)$request->price_to);
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'DateSoonest':
                    $productQuery->orderBy('created_at', 'asc');
                    break;
                case 'DateLatest':
                    $productQuery->orderBy('created_at', 'desc');
                    break;
                case 'CaratAsc':
                    $productQuery->orderBy('carat', 'asc');
                    break;
                case 'CaratDesc':
                    $productQuery->orderBy('carat', 'desc');
                    break;
                case 'ColorAsc':
                    $productQuery->orderBy('color', 'asc');
                    break;
                case 'ColorDesc':
                    $productQuery->orderBy('color', 'desc');
                    break;
                case 'ClarityAsc':
                    $productQuery->orderBy('clarity', 'asc');
                    break;
                case 'ClarityDesc':
                    $productQuery->orderBy('clarity', 'desc');
                    break;
                case 'CutAsc':
                    $productQuery->orderBy('cut', 'asc');
                    break;
                case 'CutDesc':
                    $productQuery->orderBy('cut', 'desc');
                    break;
                case 'PriceAsc':
                    $productQuery->orderBy('price', 'asc');
                    break;
                case 'PriceDesc':
                    $productQuery->orderBy('price', 'desc');
                    break;
                default:
                    $productQuery->orderBy('id', 'desc'); // fallback
            }
        } else {
            $productQuery->orderBy('id', 'desc'); // default if no sort
        }

        $products = $productQuery->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'title' => $p->title,
                'slug' => $p->slug,
                'grown_type' => $p->grown_type,
                'shape' => $p->shape,
                'cut' => $p->cut,
                'color' => $p->color,
                'carat' => $p->carat,
                'clarity' => $p->clarity,
                'price' => $p->price,
                'img_one' => $p->img_one ? asset('storage/images/diamonds/' . $p->img_one) : null,
                'img_two' => $p->img_two ? asset('storage/images/diamonds/' . $p->img_two) : null,
                'img_three' => $p->img_three ? asset('storage/images/diamonds/' . $p->img_three) : null,
                'img_four' => $p->img_four ? asset('storage/images/diamonds/' . $p->img_four) : null,
                'status' => $p->status
            ];
        });

        $data = [
            'products' => $products
        ];

        return response()->json([
            'status' => true,
            'message' => "Diamond products fetched successfully.",
            'data' => $data
        ]);
    }

    public function diamondProductDetails($id)
    {
        $product = DiamondProduct::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Diamond product not found.',
            ], 404);
        }

        $data = [
            'id' => $product->id,
            'title' => $product->title,
            'slug' => $product->slug,
            'grown_type' => $product->grown_type,
            'shape' => $product->shape,
            'cut' => $product->cut,
            'color' => $product->color,
            'carat' => $product->carat,
            'clarity' => $product->clarity,
            'price' => $product->price,
            'img_one' => $product->img_one ? asset('storage/images/diamonds/' . $product->img_one) : null,
            'img_two' => $product->img_two ? asset('storage/images/diamonds/' . $product->img_two) : null,
            'img_three' => $product->img_three ? asset('storage/images/diamonds/' . $product->img_three) : null,
            'img_four' => $product->img_four ? asset('storage/images/diamonds/' . $product->img_four) : null,
            'status' => $product->status,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ];

        return response()->json([
            'status' => true,
            'message' => "Diamond product details fetched successfully.",
            'data' => $data
        ]);
    }
}
