<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    // ─── Dashboard ─────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $user    = Auth::user();
        $status  = $request->query('status');
        $allowed = ['pending', 'processing', 'shipped', 'delivered', 'completed', 'cancelled'];

        $query = Order::with(['items.product'])
            ->where('user_id', $user->id)
            ->latest();

        if ($status && in_array($status, $allowed)) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(10)->withQueryString();

        // Per-status counts for filter tab badges
        $statusCounts = Order::where('user_id', $user->id)
            ->selectRaw('status, count(*) as cnt')
            ->groupBy('status')
            ->pluck('cnt', 'status')
            ->toArray();

        $totalCount = array_sum($statusCounts);

        // Saved addresses (default first)
        $addresses = Address::where('user_id', $user->id)
            ->orderByDesc('is_default')
            ->orderBy('created_at')
            ->get();

        return view('account', compact('user', 'orders', 'status', 'statusCounts', 'totalCount', 'addresses'));
    }

    // ─── Edit Profile ──────────────────────────────────────────────────────────

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user  = Auth::user();
        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
        ];

        // Current password required when changing email
        if ($request->input('email') !== $user->email) {
            $rules['current_password'] = ['required', 'string'];
        }

        $validated = $request->validate($rules, [
            'name.required'             => 'Please enter your name.',
            'email.required'            => 'Please enter your email address.',
            'email.email'               => 'Please enter a valid email address.',
            'email.unique'              => 'That email address is already taken.',
            'current_password.required' => 'Please enter your current password to change your email.',
        ]);

        if (isset($validated['current_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Your current password is incorrect.'])
                    ->withInput()
                    ->with('open_panel', 'profile');
            }
        }

        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ]);

        return back()->with([
            'success_profile' => 'Your profile has been updated.',
            'open_panel'      => 'profile',
        ]);
    }

    // ─── Change Password ───────────────────────────────────────────────────────

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password'     => ['required', 'string', Password::min(8)->letters()->numbers(), 'confirmed'],
        ], [
            'current_password.required' => 'Please enter your current password.',
            'new_password.required'     => 'Please enter a new password.',
            'new_password.confirmed'    => 'New password confirmation does not match.',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Your current password is incorrect.'])
                ->with('open_panel', 'password');
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with([
            'success_password' => 'Password changed successfully.',
            'open_panel'       => 'password',
        ]);
    }

    // ─── Addresses ─────────────────────────────────────────────────────────────

    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'label'        => ['nullable', 'string', 'max:50'],
            'full_name'    => ['required', 'string', 'max:255'],
            'phone'        => ['required', 'string', 'max:30'],
            'address_line' => ['required', 'string', 'max:500'],
            'barangay'     => ['nullable', 'string', 'max:100'],
            'city'         => ['required', 'string', 'max:100'],
            'province'     => ['required', 'string', 'max:100'],
            'is_default'   => ['sometimes', 'boolean'],
        ]);

        $user = Auth::user();

        if (!empty($validated['is_default'])) {
            Address::where('user_id', $user->id)->update(['is_default' => false]);
        }

        // First address auto-becomes default
        if (!Address::where('user_id', $user->id)->exists()) {
            $validated['is_default'] = true;
        }

        Address::create(array_merge($validated, ['user_id' => $user->id]));

        return back()->with([
            'success_address' => 'Address saved successfully.',
            'open_panel'      => 'addresses',
        ]);
    }

    public function updateAddress(Request $request, Address $address)
    {
        abort_if($address->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'label'        => ['nullable', 'string', 'max:50'],
            'full_name'    => ['required', 'string', 'max:255'],
            'phone'        => ['required', 'string', 'max:30'],
            'address_line' => ['required', 'string', 'max:500'],
            'barangay'     => ['nullable', 'string', 'max:100'],
            'city'         => ['required', 'string', 'max:100'],
            'province'     => ['required', 'string', 'max:100'],
            'is_default'   => ['sometimes', 'boolean'],
        ]);

        if (!empty($validated['is_default'])) {
            Address::where('user_id', Auth::id())
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update($validated);

        return back()->with([
            'success_address' => 'Address updated.',
            'open_panel'      => 'addresses',
        ]);
    }

    public function destroyAddress(Address $address)
    {
        abort_if($address->user_id !== Auth::id(), 403);
        $address->delete();

        return back()->with([
            'success_address' => 'Address removed.',
            'open_panel'      => 'addresses',
        ]);
    }

    public function setDefaultAddress(Address $address)
    {
        abort_if($address->user_id !== Auth::id(), 403);

        Address::where('user_id', Auth::id())->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with([
            'success_address' => 'Default address updated.',
            'open_panel'      => 'addresses',
        ]);
    }

    // ─── Cancel Order ──────────────────────────────────────────────────────────

    public function cancelOrder(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        abort_if($order->status !== 'pending', 422, 'Only pending orders can be cancelled.');

        $order->update(['status' => 'cancelled']);

        return back()->with('success_order', 'Order ' . $order->order_number . ' has been cancelled.');
    }
}

