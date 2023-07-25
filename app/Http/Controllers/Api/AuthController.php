<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utilities\ResponseUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\IsNull;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'phone' => 'required',
            'completed_address' => 'required',
            'province' => 'required',
            'city' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseUtility::makeResponse($validator->errors(), 400);
        }

        $email_exist = User::where('email', $request->email)->exists();
        if ($email_exist) {
            return ResponseUtility::makeResponse('Email sudah terdaftar.', 400);
        }

        $phone_exist = User::where('phone', $request->phone)->exists();
        if ($phone_exist) {
            return ResponseUtility::makeResponse('Nomor HP sudah terdaftar.', 400);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'completed_address' => $request->completed_address,
            'province' => $request->province,
            'city' => $request->city,
            'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSIaDVphQLEDiL6PDlQULiIyHHt_s8eeBdCiw&usqp=CAU',
            'is_admin' => $request->is_admin,
        ]);

        return ResponseUtility::makeResponse('Akun berhasil didaftarkan.', 200);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return ResponseUtility::makeResponse('Email atau password salah.', 400);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return ResponseUtility::makeResponse($token, 200);
    }

    public function logout(User $user)
    {
        $user->tokens()->delete();
        return ResponseUtility::makeResponse('Akun berhasil logout.', 200);
    }

    public function current_user(Request $request)
    {
        return ResponseUtility::makeResponse($request->user(), 200);
    }

    public function user_by_id($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return ResponseUtility::makeResponse('Akun tidak ditemukan', 404);
        }

        return ResponseUtility::makeResponse($user, 200);
    }

    public function all_users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return ResponseUtility::makeResponse($users, 200);
    }

    public function delete_user(Request $request, $id)
    {
        $user = $request->user();
        if (!$user->is_admin) {
            return ResponseUtility::makeResponse('Anda bukan admin.', 400);
        };

        User::destroy($id);

        return ResponseUtility::makeResponse('Berhasil hapus akun.', 200);
    }

    public function update_user(Request $request, $id)
    {
        $user = $request->user();
        if (!$user->is_admin && $user->id != $id) {
            return ResponseUtility::makeResponse('Anda tidak bisa update akun ini.', 400);
        }

        $found_user_email = User::where('email', $request->email)->first();
        if ($found_user_email && $found_user_email->id != $user->id) {
            return ResponseUtility::makeResponse('Email telah digunakan akun lain.', 400);
        }

        $found_user_phone = User::where('phone', $request->phone)->first();
        if ($found_user_phone && $found_user_phone->id != $user->id) {
            return ResponseUtility::makeResponse('Nomor HP telah digunakan akun lain.', 400);
        }

        User::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'completed_address' => $request->completed_address,
            'province' => $request->province,
            'city' => $request->city,
            'image' => $request->image,
            'is_admin' => $request->is_admin,
        ]);

        return ResponseUtility::makeResponse('Berhasil update akun.', 200);
    }
}
