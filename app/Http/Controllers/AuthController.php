<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use App\Services\AuthServiceInterface;

class AuthController
{
    public function __construct(private readonly AuthServiceInterface $auth)
    {
    }
    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => ['required','min:8','regex:/[A-Z]/','regex:/[a-z]/','regex:/[\W]/', 'confirmed'],
        ]);

        $this->auth->register($request->email, $request->password);

        return redirect('/login')->with('success', 'Đăng ký thành công, mời đăng nhập');
    }

    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $ok = $this->auth->authenticate($request, $request->email, $request->password, $request->boolean('remember'));
        if ($ok) return redirect('/profile');

        return back()->withErrors(['email' => 'Email hoặc password không đúng']);
    }

    public function showProfile(Request $request)
    {
        $userId = session('user_id');
        $user = $this->auth->getCurrentUser((int)$userId);
        return view('profile', ['user' => $user]);
    }

    public function updateProfile(Request $request)
    {
        $userId = session('user_id');
        $request->validate([
            'name' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
        ]);

        $this->auth->updateProfile((int)$userId, $request->name, $request->birthday);

        return back()->with('success', 'Cập nhật thông tin thành công');
    }

    // --- Logout ---
    public function logout(Request $request)
    {
        session()->forget('user_id');
        Cookie::queue(Cookie::forget('remember_user'));
        return redirect('/login');
    }

    // --- Show reset password request form ---
    public function showForgotPassword()
    {
        return view('forgot_password');
    }

    // --- Handle sending reset email ---
    public function sendResetPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            $this->auth->createResetTokenAndSendMail($request->email);
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['email' => 'Email không tồn tại']);
        }

        return redirect('/login')->with('success', 'Link reset password đã được gửi đến email.');
    }

    // --- Show reset password form ---
    public function showResetPassword($token)
    {
        $user = DB::table('users')->where('token', $token)->first();
        if (!$user) return redirect('/login')->withErrors(['token' => 'Link không hợp lệ hoặc đã hết hạn']);

        return view('reset_password', ['token' => $token]);
    }

    // --- Handle reset password ---
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',      // ít nhất 1 chữ thường
                'regex:/[A-Z]/',      // ít nhất 1 chữ hoa
                'regex:/[0-9]/',      // ít nhất 1 số
                'regex:/[@$!%*?&]/',
                'confirmed'   
            ]
        ]);

        try {
            $this->auth->resetPassword($request->token, $request->password);
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['token' => 'Link không hợp lệ']);
        }

        return redirect('/login')->with('success', 'Reset password thành công, đăng nhập lại');
    }
}
