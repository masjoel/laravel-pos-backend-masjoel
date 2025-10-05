<?php

namespace App\Http\Controllers;

use App\Helpers\AutoNumberHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreUserReq;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('id', 'desc')
            ->where('email', '!=', 'owner@tokopojok.com')
            ->when($request->input('name'), function ($query, $name) {
                return $query->where('name', 'like', '%' . $name . '%')->orWhere('email', 'like', '%' . $name . '%')->orWhere('phone', 'like', '%' . $name . '%')->orWhere('roles', 'like', '%' . $name . '%');
            })
            ->paginate(10);
        return view('pages.users.index', compact('users'));
    }

    public function create()
    {
        return view('pages.users.create');
    }

    public function store(StoreUserReq $request)
    {
        $data = $request->all();
        if ($request->roles !== 'reseller') {
            $data['reseller_id'] = null;
        }
        $data['password'] = Hash::make($request->password);
        User::create($data);
        return redirect()->route('user.index')->with('success', 'User successfully created');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pages.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        if ($request->password !== null) {
            $data['password'] = Hash::make($request->password);
        }
        if ($request->roles == 'reseller' && $user->reseller_id == null) {
            $idReseller = AutoNumberHelper::initGenerateNumber('NOMOR');
            $data['reseller_id'] = $idReseller;
        }
        $user->update($data);
        return redirect()->route('user.index')->with('success', 'User successfully updated');
    }
    public function konfirmasi($confirmation_code)
    {
        $user = User::where('phone', $confirmation_code)->update(['booking_id' => $confirmation_code, 'device_id' => '0', 'email_verified_at' => now()]);
        return redirect()->route('register.success')->with('success', 'Akun Anda sudah aktif, silahkan login di aplikasi Kasir');
    }
    public function registerSuccess()
    {
        $title = 'Konfirmasi Sukses';
        return view('pages.users.register-success', compact('title'));
    }

    public function destroy(User $user)
    {
        DB::beginTransaction();
        $user->delete();
        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Succesfully Deleted Data'
        ]);
    }
}
