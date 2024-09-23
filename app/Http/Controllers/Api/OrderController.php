<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable',
            'invoice' => 'nullable',
            'customer' => 'nullable',
            'kembali' => 'nullable',
            'nama_kasir' => 'nullable',
            'transaction_time' => 'required',
            'kasir_id' => 'required|exists:users,id',
            'total_price' => 'required|numeric',
            'total_item' => 'required|numeric',
            'order_items' => 'required|array',
            'order_items.*.product_id' => 'required|exists:products,id',
            'order_items.*.quantity' => 'required|numeric',
            // 'order_items.*.total_price' => 'required|numeric',
        ]);

        $order = Order::create([
            'user_id' => $request->user_id,
            'invoice' => $request->invoice,
            'customer' => $request->customer,
            'kembali' => $request->kembali,
            'nama_kasir' => $request->nama_kasir,
            'transaction_time' => $request->transaction_time,
            'kasir_id' => $request->kasir_id,
            'total_price' => $request->total_price,
            'total_item' => $request->total_item,
            'payment_method' => $request->payment_method,
        ]);
        $total_hpp = 0;
        foreach ($request->order_items as $item) {
            $price = Product::find($item['product_id'])->price;
            $hpp = Product::find($item['product_id'])->hpp;
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'total_price' => $price,
                'hpp' => $hpp,
            ]);
            $total_hpp += $item['quantity'] * $hpp;
            // $total_hpp += $item['quantity'] * $item['hpp'];
        }
        Order::find($order->id)->update(['total_hpp' => $total_hpp]);

        return response()->json([
            'success' => true,
            'message' => 'Order Created'
        ], 201);
    }
}
