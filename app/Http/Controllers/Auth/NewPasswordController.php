<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
// 20261009 sha256を使わなくなったので不要になる
// use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 20261019 $validatedに受け取らないと下で使えない
        $validated = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            // 20261019 クロージャの中で$validatedを使うのでuseに追加する
            function (User $user) use ($validated) {

                // 20261009 Seederで解決してBcryptにしたのでここのsha256をもどす
                // pswに関して、ハッシュ化して保存する
                //$inputPassword = hash("sha256", $request->input('password'));
            
                $user->forceFill([
                    // 20261007 ここを書き換える：hash256にあわせる
                    // 20261009 Seederで解決してBcryptにしたのでここのsha256をもどす
                    //'password' => $request -> password,
                    // 20261009 せっかくなのでヴァリデーション済みの値をつかう
                    'password' => $validated['password'],
                    // 20261019 usersテーブルにremember_tokenカラムがないのでコメントアウト（あるとSQLエラーになる）
                    //'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
