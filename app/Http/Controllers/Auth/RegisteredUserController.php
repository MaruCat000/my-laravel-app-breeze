<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// 20261009 sha256を使わなくなったので不要になる
// use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 20261019 $validatedに受け取らないと下で使えない
        $validated = $request->validate([
            // 20261019 ここの項目を合わせる：register.blade.phpのname属性とusersテーブルのカラム名にそろえる
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 20261009 Seederで解決してBcryptにしたのでここのsha256をもどす
        // pswに関して、ハッシュ化して保存する
        //$inputPassword = hash("sha256", $request->input('password'));

        $user = User::create([
            // 20261019 ここの項目を合わせる
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            // 20261007 ここを書き換える：hash256にあわせる
            // 20261009 Seederで解決してBcryptにしたのでここのsha256をもどす
            //'password' => $inputPassword,
            // 20261009 せっかくなのでヴァリデーション済みの値をつかう
            //'password' => $request -> password,
            'password' => $validated['password'],
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
