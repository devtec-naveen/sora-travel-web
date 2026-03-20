<?php
 
namespace App\Http\Controllers\Frontend;
 
use App\Http\Controllers\Controller;
use App\Services\Frontend\AuthService;
use Illuminate\Http\Request;
 
class AuthController extends Controller
{
    public function __construct(protected AuthService $auth) {}
 
    public function logout(Request $request)
    {
        $this->auth->logout(); 
        return redirect()->route('home');
    }
}
 