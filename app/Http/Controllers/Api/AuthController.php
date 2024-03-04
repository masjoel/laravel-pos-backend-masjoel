<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Mail\KirimEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
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
        $userActive = User::where('email_verified_at', null)->first();

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
        $generateActivatingCode = rand(100000, 999999);
        $data = [
            'message' => '<p>Berikut adalah link konfirmasi yang harus diakses setelah mendaftar di aplikasi Kasir, agar status akun anda aktif.</p> <p>Link Konfirmasi: <a href="' . route('konfirmasi', $generateActivatingCode) . '">' . route('konfirmasi', $generateActivatingCode) . '</a></p><p>Terima kasih</p>',
        ];
        // With Mailable Class:
        // $email = Mail::to($request->email)->send(new KirimEmail($data));
        // if ($email->sent()) {
        //     echo "Email berhasil terkirim!";
        // } else {
        // }
        try {
            Mail::to($request->email)->send(new KirimEmail($data));
            // echo "Email berhasil terkirim!";
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'marketing' => $request->marketing,
                'password' => Hash::make($request->password),
                'phone' => $generateActivatingCode,
                'roles' => 'kasir',
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'email' => ['Terjadi error saat mengirim email. Pastikan email anda valid']
            ]);
            // echo "Terjadi kesalahan saat mengirim email: " . $e->getMessage();
        }



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
