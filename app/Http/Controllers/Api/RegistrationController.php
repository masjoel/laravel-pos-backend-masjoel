<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Helpers\AutoNumberHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'marketing' => 'required|string|max:50',
            'password' => 'required|string|max:30',
        ]);
        $generateActivatingCode = Uuid::uuid1()->getHex();

        // Simpan ke database
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'marketing' => $validated['marketing'],
            'password' => bcrypt($validated['password']),
            'phone' => $generateActivatingCode,
            'email_verified_at' => now(),
            'roles' => 'kasir',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => $user,
        ], 201);
    }

    public function dataReseller(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);
        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Reseller tidak ditemukan.',
            ], 404);
        }

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Data Reseller ditemukan.',
        //     'data' => $user,
        // ], 200);
        return response()->json([
            'success' => true,
            'message' => 'Data Reseller ditemukan.',
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
                'telpon' => $user->telpon,
                'address' => $user->address,
                'bank' => $user->bank,
                'reseller_id' => $user->reseller_id,
            ],
        ], 200);
    }

    public function storeReseller(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'marketing' => 'required|string|max:50',
            'password' => 'required|string|max:30',
            'telpon' => 'required|string',
            'address' => 'nullable|string',
            'bank' => 'nullable|string',
        ]);
        $generateActivatingCode = Uuid::uuid1()->getHex();
        // Hash::make($request->password)
        // Simpan ke database
        $idReseller = AutoNumberHelper::initGenerateNumber('NOMOR');

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'marketing' => $validated['marketing'],
            'password' => bcrypt($validated['password']),
            'phone' => $generateActivatingCode,
            'email_verified_at' => now(),
            'roles' => 'reseller',
            'telpon' => $validated['telpon'],
            'address' => $validated['address'],
            'bank' => $validated['bank'],
            'reseller_id' => $idReseller,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => $user,
        ], 201);
    }
}
