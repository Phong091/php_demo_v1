<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use App\Services\AuthServiceInterface;
use App\Services\JwtService;

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
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.regex' => 'Mật khẩu phải có ít nhất 1 chữ hoa, 1 chữ thường và 1 ký tự đặc biệt.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
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
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $remember = $request->boolean('remember');
        $user = $this->auth->authenticate($request, $request->email, $request->password, $remember);
        if ($user) {
            $role = (int)($user->role ?? 1);
            $ttl = $remember ? (int)config('jwt.ttl_remember_minutes') : (int)config('jwt.ttl_minutes');
            $jwt = JwtService::fromConfig()->issueToken((int)$user->id, $role, $ttl);

            if ($remember) {
                Cookie::queue(Cookie::make('token', $jwt, $ttl, null, null, false, true, false, 'Lax'));
            } else {
                Cookie::queue(Cookie::make('token', $jwt, 0, null, null, false, true, false, 'Lax'));
            }
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => $role === 0 ? '/admin/profile' : '/profile',
                ]);
            }
            return $role === 0 ? redirect('/admin/profile') : redirect('/profile');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Email hoặc mật khẩu không đúng.',
            ], 422);
        }
        return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng.']);
    }

    public function showProfile(Request $request)
    {
        $userId = (int)($request->attributes->get('user_id') ?? 0);
        $user = $this->auth->getCurrentUser((int)$userId);
        return view('profile', ['user' => $user]);
    }

    public function updateProfile(Request $request)
    {
        $userId = (int)($request->attributes->get('user_id') ?? 0);
        $request->validate([
            'name' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
        ]);

        $this->auth->updateProfile((int)$userId, $request->name, $request->birthday);

        return back()->with('success', 'Cập nhật thông tin thành công');
    }

    public function showAdminProfile(Request $request)
    {
        $userId = (int)($request->attributes->get('user_id') ?? 0);
        $user = $this->auth->getCurrentUser((int)$userId);
        return view('profile_admin', ['user' => $user]);
    }

    // --- Logout ---
    public function logout(Request $request)
    {
        Cookie::queue(Cookie::forget('token'));
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
        $request->validate(['email' => 'required|email'], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
        ]);

        try {
            $this->auth->createResetTokenAndSendMail($request->email);
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['email' => 'Email không tồn tại.']);
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
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
                'confirmed'
            ]
        ], [
            'token.required' => 'Thiếu token đặt lại mật khẩu.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.regex' => 'Mật khẩu phải có đủ: ít nhất 1 chữ thường, 1 chữ hoa, 1 số và 1 ký tự đặc biệt (@$!%*?&).',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        try {
            $this->auth->resetPassword($request->token, $request->password);
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['token' => 'Link không hợp lệ.']);
        }

        return redirect('/login')->with('success', 'Reset password thành công, đăng nhập lại');
    }
}
