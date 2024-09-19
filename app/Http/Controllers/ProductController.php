<?php

namespace App\Http\Controllers;

use Ramsey\Uuid\Uuid;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::orderBy('created_at', 'desc')
            ->when($request->input('name'), function ($query, $name) {
                return $query->where('name', 'like', '%' . $name . '%');
            })
            ->paginate(10);

        return view('pages.products.index', compact('products'));
    }

    public function create()
    {
        return view('pages.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|unique:products',
            'price' => 'required|integer',
            'hpp' => 'required|integer',
            'stock' => 'required|integer',
            'category' => 'required|in:food,drink,snack',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp'
        ]);

        $filename = Uuid::uuid1()->getHex() . '.' . $request->image->extension();
        $request->image->storeAs('public/products', $filename);
        $data = $request->all();
        $data['image'] = $filename;
        // $data['stock_min'] = 0;
        Product::create($data);
        return redirect()->route('product.index')->with('success', 'Product successfully created');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('pages.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required|min:3|unique:products,name,' . $id,
            'price' => 'required|integer',
            'hpp' => 'required|integer',
            'stock' => 'required|integer',
            'category' => 'required|in:food,drink,snack',
        ]);

        $imagePath = Product::find($id)->image;

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:png,jpg,jpeg,webp'
            ]);
            if (Storage::disk('public')->exists('products/' . $imagePath)) {
                Storage::disk('public')->delete('products/' . $imagePath);
            }
            $imagePath = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/products', $imagePath);
        }
        $data = $request->all();
        $product = Product::findOrFail($id);
        $data['image'] = $imagePath;
        $product->update($data);
        return redirect()->route('product.index')->with('success', 'Product successfully updated');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('product.index')->with('success', 'Product successfully deleted');
    }
}
