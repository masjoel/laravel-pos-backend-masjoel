<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\KirimEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['email incorrect']
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['password incorrect']
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(
            [
                'token' => $token,
                'user' => $user,
                // 'user' => new UserResource($user),
            ]
        );
    }
    // public function login(Request $request)
    // {
    //     $loginData = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if (!$user) {
    //         return response([
    //             'message' => 'Email  found !',
    //             'errors' => ['email' => ['Email not found !']],
    //         ], 404);
    //     }

    //     if (!Hash::check($request->password, $user->password)) {
    //         return response([
    //             'message' => 'Password is wrong !',
    //             'errors' => ['password' => ['Password is wrong !']],
    //         ], 404);
    //     }

    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response([
    //         'user' => $user,
    //         'token' => $token,
    //     ], 200);
    // }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|string|email',
            'password' => 'required',
            'marketing' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'marketing' => $request->marketing,
            'password' => Hash::make($request->password),
            'roles' => 'kasir',
        ]);
        // Mail::to($request->email)->send(new KirimEmail());

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }
    // public function kirimEmail()
    // {
    //     Mail::to('tujuan@example.com')->send(new KirimEmail());

    //     return 'Email terkirim!';
    // }


    public function savedeviceid(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'deviceid' => 'required'
        ]);
        $user = User::where('email', $request->email)->where('device_id', '0')->first();
        if (!$user) {
            return response()->json(['message' => 'Oops...aplikasi sudah terinstal di gadget lain!']);
        }
        $user->device_id = $request->deviceid;
        $user->save();
        return response()->json(['message' => 'device id saved successfully']);
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'logout successfully',
        ]);
    }
}
