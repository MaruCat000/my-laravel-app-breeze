<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
// 20261009 sha256を使わなくなったので不要になる
// use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // 20261009 Seederで解決してBcryptにしたのでここのsha256をもどす
        // pswに関して、ハッシュ化して保存する

        //$inputPassword = hash("sha256", $request->input('password'));

        $request->user()->update([
            // 20261007 ここを書き換える：hash256にあわせる
            // 20261009 Seederで解決してBcryptにしたのでここのsha256をもどす
            // 20261009 せっかくなのでヴァリデーション済みの値をつかう
            //'password' => $request -> password,
            'password' => $validated['password'],

        ]);

        return back()->with('status', 'password-updated');
    }
}
