<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('id', 'desc')
            ->where('email', '!=', 'owner@tokopojok.com')->where('booking_id', NULL)
            ->when($request->input('name'), function ($query, $name) {
                return $query->where('name', 'like', '%' . $name . '%')->orWhere('email', 'like', '%' . $name . '%')->orWhere('phone', 'like', '%' . $name . '%');
            })
            ->paginate(10);
        $title = 'Dashboard';
        return view('pages.dboard', compact('title', 'users'));
    }
    // public function index(Request $request)
    // {
    //     $search = $request->input('search', date('m'));
    //     $year = $request->input('year', date('Y'));
    //     $terlaris = $request->input('terlaris', date('m'));
    //     $qry = Order::select('orders.id as id', 'orders.payment_method', 'orders.total_price',  'orders.created_at');
    //     $qry2 = Order::select(DB::raw('SUM(total_hpp) as total_hpp'));
    //     $qry3 = Order::select(DB::raw('SUM(total_price) as total_price'));
    //     $qry4 = Order::select(DB::raw('COUNT(id) as total_order'));
    //     $qry5 = Order::select(DB::raw('SUM(total_price) as total_order'));
    //     $qry6 = Order::select(DB::raw('SUM(total_price) as total_order'));
    //     // $qry5 = Order::select(DB::raw('COUNT(id) as total_order'));
    //     // $qry6 = Order::select(DB::raw('COUNT(id) as total_order'));
    //     $qry7 = Order::select(DB::raw('COUNT(id) as total_order'));
    //     $qry8 = OrderItem::leftJoin('orders', 'orders.id', '=', 'order_items.order_id');
    //     $qry9 = Product::select('products.id', 'products.name', 'products.image', 'products.hpp', 'products.price', DB::raw('SUM(order_items.quantity) as total_sales'))
    //         ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
    //         ->leftJoin('orders', 'orders.id', '=', 'order_items.order_id');
    //     $qry10 = Order::select(DB::raw('distinct DATE(created_at) as tgl'), DB::raw('SUM(total_hpp) as total_hpp'), DB::raw('SUM(total_price) as total_price'));
    //     $qry11 = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id');
    //     $qry12 = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id');
    //     $qry13 = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id');
    //     $order =  $qry->orderBy('orders.id', 'desc')->limit(6)->get();
    //     $tot_budget = $qry2->when($search, function ($query, $search) use ($year) {
    //         $query->whereMonth('created_at', '=', $search)
    //             ->whereYear('created_at', '=', $year);
    //     })
    //         ->first()->total_hpp ?? 0;
    //     $tot_balance = $qry3->when($search, function ($query, $search) use ($year) {
    //         $query->whereMonth('created_at', '=', $search)
    //             ->whereYear('created_at', '=', $year);
    //     })->first()->total_price ?? 0;
    //     $tot_proses = $qry5->where('payment_method', 'QRIS')
    //         ->when($search, function ($query, $search) use ($year) {
    //             $query->whereMonth('created_at', '=', $search)
    //                 ->whereYear('created_at', '=', $year);
    //         })->first()->total_order ?? 0;
    //     $tot_finish = $qry6->where('payment_method', 'Tunai')
    //         ->when($search, function ($query, $search) use ($year) {
    //             $query->whereMonth('created_at', '=', $search)
    //                 ->whereYear('created_at', '=', $year);
    //         })->first()->total_order ?? 0;
    //     $tot_order = $qry7->when($search, function ($query, $search) use ($year) {
    //         $query->whereMonth('created_at', '=', $search)
    //             ->whereYear('created_at', '=', $year);
    //     })->first()->total_order ?? 0;
    //     $tot_sales = $qry8->select(DB::raw('SUM(quantity) as total_qty'))
    //         ->when($search, function ($query, $search) use ($year) {
    //             $query->whereMonth('orders.created_at', '=', $search)
    //                 ->whereYear('orders.created_at', '=', $year);
    //         })->first()->total_qty ?? 0;

    //     $qry_terlaris = $qry9->where('payment_method', 'Tunai');
    //     if ($terlaris == date('W')) {
    //         $qry_terlaris->when($terlaris, function ($query, $terlaris) {
    //             $query->where(DB::raw("WEEK(order_items.created_at, 3)"), $terlaris);
    //         });
    //     } elseif ($terlaris == date('m')) {
    //         $qry_terlaris->when($terlaris, function ($query, $terlaris) use ($year) {
    //             $query->whereMonth('order_items.created_at', '=', $terlaris)
    //                 ->whereYear('order_items.created_at', '=', $year);
    //         });
    //     } elseif ($terlaris == $year) {
    //         $qry_terlaris->when($terlaris, function ($query, $terlaris) {
    //             $query->whereYear('order_items.created_at', '=', $terlaris);
    //         });
    //     } else {
    //         $qry_terlaris->when($terlaris, function ($query, $terlaris) {
    //             $query->whereDate('order_items.created_at', '=', $terlaris);
    //         });
    //     }
    //     $sqlBalance = $qry10->when($search, function ($query, $search) use ($year) {
    //         $query->whereMonth('created_at', '=', $search)
    //             ->whereYear('created_at', '=', $year);
    //     })
    //         ->groupBy('tgl')
    //         ->get();
    //     $tglBalance = $sqlBalance->pluck('tgl')->toArray();
    //     $totalBudget = $sqlBalance->pluck('total_hpp')->toArray();
    //     $totalBalance = $sqlBalance->pluck('total_price')->toArray();
    //     $bestproducts = $qry_terlaris->groupBy('products.id', 'products.name', 'products.image', 'products.hpp', 'products.price')
    //         ->orderBy('total_sales', 'desc')
    //         ->limit(5)->get();

    //     $title = 'Dashboard';
    //     return view('pages.dashboard', compact('title', 'search', 'tot_proses', 'tot_finish', 'tot_order', 'tot_balance', 'tot_budget', 'tot_sales', 'terlaris', 'order', 'tglBalance', 'totalBudget', 'totalBalance','bestproducts'));
    // }
}
