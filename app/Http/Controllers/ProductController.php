<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Product;

use OpenApi\Attributes as OA;

class ProductController extends Controller
{
    #[OA\Get(path: "/products", summary: "List all products with filtering and sorting", tags: ["Store - Products"])]
    #[OA\Parameter(name: "category", in: "query", required: false, description: "Filter by category name (e.g. Supplements, Gear)", schema: new OA\Schema(type: "string"))]
    #[OA\Parameter(name: "min_price", in: "query", required: false, description: "Minimum price", schema: new OA\Schema(type: "number"))]
    #[OA\Parameter(name: "max_price", in: "query", required: false, description: "Maximum price", schema: new OA\Schema(type: "number"))]
    #[OA\Parameter(name: "sort_by", in: "query", required: false, description: "Sort option (price_asc, price_desc)", schema: new OA\Schema(type: "string", enum: ["price_asc", "price_desc"]))]
    #[OA\Response(response: 200, description: "Successful operation")]
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter by Category Name (via relation)
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->category . '%');
            });
        }

        // Filter by Minimum Price
        if ($request->filled('min_price')) {
            $query->where('product_price', '>=', $request->min_price);
        }

        // Filter by Maximum Price
        if ($request->filled('max_price')) {
            $query->where('product_price', '<=', $request->max_price);
        }

        // Apply Sorting
        if ($request->filled('sort_by')) {
            switch ($request->sort_by) {
                case 'price_asc':
                    $query->orderBy('product_price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('product_price', 'desc');
                    break;
            }
        }

        return response()->json($query->get());
    }

    #[OA\Get(path: "/products/{id}", summary: "Get product details", tags: ["Store - Products"])]
    #[OA\Parameter(name: "id", in: "path", required: true, description: "Product ID", schema: new OA\Schema(type: "integer"))]
    #[OA\Response(response: 200, description: "Successful operation")]
    #[OA\Response(response: 404, description: "Product not found")]
    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }
}
