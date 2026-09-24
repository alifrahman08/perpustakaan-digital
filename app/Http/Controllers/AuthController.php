<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class AuthController extends Controller { public function login(){return view('auth.login');} public function authenticate(Request $r){$data=$r->validate(['email'=>'required|email','password'=>'required']); if(Auth::attempt($data, $r->boolean('remember'))){$r->session()->regenerate(); return redirect()->intended($r->user()->isAdmin() ? route('admin.dashboard') : route('dashboard'));} return back()->withErrors(['email'=>'Email atau password tidak cocok.'])->onlyInput('email');} public function register(){return view('auth.register');} public function store(Request $r){$data=$r->validate(['name'=>'required|string|max:100','email'=>'required|email|unique:users','password'=>'required|string|min:8|confirmed']);$user=User::create($data);Auth::login($user);return redirect()->route('dashboard');} public function logout(Request $r){Auth::logout();$r->session()->invalidate();$r->session()->regenerateToken();return redirect()->route('home');} }
