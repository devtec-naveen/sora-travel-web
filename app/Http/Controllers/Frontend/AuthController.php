<?php
 
namespace App\Http\Controllers\Frontend;
 
use App\Http\Controllers\Controller;
use App\Services\Frontend\AuthService;
use Illuminate\Http\Request;
use App\Traits\Toast;
 
class AuthController extends Controller
{
    use Toast;

    public function __construct(protected AuthService $auth) {}
 
    public function logout(Request $request)
    {
        $this->auth->logout(); 
        $this->SessionToast('success', 'Logout successfully!');
        return redirect()->route('home');
    }
}
 