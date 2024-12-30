<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Mail\KirimEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProspectResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;

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
    public function prospect(string $id)
    {
        $idM = User::where('id', $id)->first()->reseller_id;
        $data = User::where('marketing', $idM)->orderBy('id', 'desc')->get()->map(function ($user) {
            return new ProspectResource($user);
        });
        return response()->json([
            'success' => true,
            'message' => 'List Data Prospect',
            'data' => $data,
        ]);
    }
    public function marketing()
    {
        $data = User::where('roles', 'reseller')->inRandomOrder()->first();
        return response()->json([
            'marketing' => $data->reseller_id,
        ]);
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
        // $cekDevice = User::where('two_factor_recovery_codes', $user->two_factor_recovery_codes)->count();
        // // dd($cekDevice);
        // if ($cekDevice > 0) {
        //     throw ValidationException::withMessages([
        //         'username' => ['Aplikasi sudah terinstal di perangkat ini! Silakan uninstal aplikasi dulu, lalu login kembali']
        //     ]);
        // }
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['email incorrect']
            ]);
        }
        // $booking_id = User::where('email', $request->email)->first();
        // if ($user->booking_id == $user->phone) {
        //     $updDevice['device_id'] = '0';
        //     $user->update($updDevice);
        // }
        $userActive = User::where('email', $request->email)->where('email_verified_at', null)->first();
        if ($userActive) {
            throw ValidationException::withMessages([
                'username' => ['Akun anda belum aktif, silakan akses link konfirmasi di email anda']
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
        DB::beginTransaction();
        $request->validate([
            'name' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required',
            'marketing' => 'required'
        ]);
        $generateActivatingCode = Uuid::uuid1()->getHex();
        // $data = [
        //     'message' => '<p>Berikut adalah link konfirmasi yang harus diakses setelah mendaftar di aplikasi Kasir, agar status akun anda aktif.</p> <p>Link Konfirmasi: <a href="' . route('konfirmasi', $generateActivatingCode) . '">https://kasir.tokopojok.com/konfirmasi/' . $generateActivatingCode . '</a></p><p>Terima kasih</p>',
        // ];
        // Mail::to($request->email)->send(new KirimEmail($data));
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'marketing' => $request->marketing,
            'password' => Hash::make($request->password),
            'phone' => $generateActivatingCode,
            'email_verified_at' => now(),
            'roles' => 'kasir',
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        // Without Mailable Class:
        // Mail::send('pages.emails.sendmail', $data, function ($message) use ($request) {
        //     $message->to($request->email, $request->name)
        //         ->subject('Link Konfirmasi Email');
        // });

        DB::commit();

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }
    public function changepassword(Request $request)
    {
        DB::beginTransaction();
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'newpassword' => 'required'
        ]);
        $user = User::where('email', $request->email)->update([
            'name' => $request->name,
            'password' => Hash::make($request->newpassword),
        ]);
        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;
        DB::commit();
        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function savedeviceid(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'deviceid' => 'required'
        ]);
        $updUser = User::where('email', $request->email)->first();
        $lifetime = 0;
        // if (TRIM($updUser->booking_id) == TRIM($updUser->phone) && $updUser->two_factor_recovery_codes == $request->deviceid) {
        if (TRIM($updUser->booking_id) == TRIM($updUser->phone)) {
            $lifetime = 1;
            $updDevice['two_factor_recovery_codes'] = $request->deviceid;
            $updDevice['device_id'] = '0';
            $updUser->update($updDevice);
            // two_factor_recovery_codes - TE1A.220922.021
        }
        if ($request->email !== 'owner@tokopojok.com') {
        // if ($lifetime < 1) {
            $cekUser = User::where('email', $request->email)->where('device_id', '0')->first();
            if (!$cekUser) {
                return response()->json(['message' => 'Oops... Aplikasi sudah terinstal di perangkat lain!']);
            }
            // $cekUser->device_id = $request->deviceid;
            $cekUser->device_id = $request->email;
            $cekUser->two_factor_recovery_codes = $request->deviceid;
            $cekUser->save();
        }
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
