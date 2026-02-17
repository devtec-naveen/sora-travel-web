<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Services\AdminAuthService;
use Illuminate\Http\Request;
use App\Traits\Toast;

class AuthController extends Controller
{
    use Toast;
    protected $service;

    public function __construct(AdminAuthService $service)
    {
        $this->service = $service;
    }

    public function index(){
        return view('admin.index');
    }

    public function logout()
    {
        $this->service->logout();
        $this->SessionToast('success', 'Logout successful!'); 
        return redirect()->route('admin.login');
    }
}
