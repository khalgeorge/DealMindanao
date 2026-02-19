<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::with(['items.product'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);
        
        return view('account', compact('user', 'orders'));
    }
}
