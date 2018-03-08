<?php

namespace App\Http\Controllers\Auth;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        /*DB::table('admin_user')->insert([
                'email'      => 'admin@szs.com',
                'password'   => Hash::make('admin123szs'),
                'admin_role' => 'admin'
            ]);*/
    }

    public function showLoginForm(){
        return view('login');
    }

    public function login(Request $data){
        $user = DB::table('admin_user')->where('email', $data['email'])->first();
        if($user != null){
            if(Hash::check($data['password'], $user->password)){
                Auth::loginUsingId($user->id);
                return redirect('/dashboard');
            }
        }

        return redirect('/login');
    }

    public function logout(){
        Auth::logout();
        return redirect('/login');
    }
}
