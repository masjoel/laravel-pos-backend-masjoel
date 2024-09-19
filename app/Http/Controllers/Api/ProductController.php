<?php

namespace App\Http\Controllers\Api;

use Ramsey\Uuid\Uuid;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //all products
        $products = Product::orderBy('id', 'desc')->get();
        return response()->json([
            'success' => true,
            'message' => 'List Data Product',
            'data' => $products
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'price' => 'required|integer',
            'hpp' => 'nullable',
            'stock' => 'required|integer',
            'category' => 'required',
            'image' => 'required|image|mimes:png,jpg,jpeg'
        ]);

        $firstProduct = Product::where('user_id', $request->userId)->where('product_id', $request->productId)->first();
        if ($firstProduct) {
            $imagePath = $firstProduct->image;
            if ($imagePath && Storage::disk('public')->exists('products/' . $imagePath)) {
                Storage::disk('public')->delete('products/' . $imagePath);
            }
            $firstProduct->delete();
        }
        Product::where('user_id', $request->userId)->where('product_id', $request->productId)->delete();
        $filename = Uuid::uuid1()->getHex() . '.' . $request->image->extension();
        $request->image->storeAs('public/products', $filename);
        $product = Product::create([
            'user_id' => $request->userId,
            'product_id' => $request->productId,
            'name' => $request->name,
            'price' => (int) $request->price,
            'hpp' => (int) $request->hpp,
            'stock' => (int) $request->stock,
            'category' => $request->category,
            'image' => $filename,
            'is_best_seller' => $request->isBestSeller
        ]);
        // Categories
        $fristCategory = Category::where('user_id', $request->userId)->where('name', $request->category)->first();
        if ($fristCategory) {
            $imagePath = $fristCategory->icon_path;
            if ($imagePath && Storage::disk('public')->exists('categories/' . $imagePath)) {
                Storage::disk('public')->delete('categories/' . $imagePath);
            }
            $fristCategory->delete();
        }
        Category::where('user_id', $request->userId)->where('name', $request->category)->delete();
        $category = Category::create([
            'user_id' => $request->userId,
            'name' => $request->category,
            // 'icon_path' => $filename
        ]);

        if ($product) {
            return response()->json([
                'success' => true,
                'message' => 'Product Created',
                'data' => $product
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product Failed to Save',
            ], 409);
        }
    }
    // public function syncProducts(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'price' => 'required',
    //         'hpp' => 'required',
    //         'stock' => 'required',
    //         'image' => 'nullable'
    //     ]);
    //     // Product::where('user_id', $request->userId)->delete();
    //     $product = Product::create([
    //         'user_id' => $request->userId,
    //         'name' => $request->name,
    //         'price' => (int) $request->price,
    //         'hpp' => (int) $request->hpp,
    //         'stock' => (int) $request->stock,
    //         'category' => $request->category,
    //         'image' => $request->image,
    //         'is_best_seller' => $request->isBestSeller
    //     ]);

    //     if ($product) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Product Sync Success',
    //             'data' => $product
    //         ], 201);
    //     } else {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Product Sync Failed to Save',
    //         ], 409);
    //     }
    // }
    // public function syncProductsX(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'price' => 'required',
    //         'stock' => 'required',
    //         'image' => 'nullable'
    //     ]);

    //     $product = Product::create([
    //         'user_id' => $request->user_id,
    //         'name' => $request->name,
    //         'price' => (int) $request->price,
    //         'stock' => (int) $request->stock,
    //         'category' => $request->category,
    //         'image' => $request->image,
    //         'is_best_seller' => $request->isBestSeller
    //     ]);

    //     if ($product) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Product Sync Success',
    //             'data' => $product
    //         ], 201);
    //     } else {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Product Sync Failed to Save',
    //         ], 409);
    //     }
    // }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // $product->load('category');
        return new ProductResource($product);
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
}
