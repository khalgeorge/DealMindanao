@extends('layouts.app')

@section('meta_title', 'My Account | DealMindanao')
@section('meta_description', 'Manage your orders and account information.')

@section('content')
<div class="page-shell py-8">

  {{-- Header --}}
  <div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">My Account</h1>
    <p class="text-gray-600 mt-2">Manage your orders and account information</p>
  </div>

  {{-- Account Info Card --}}
  <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-900 mb-4">Account Information</h2>

    {{-- Flash messages --}}
    @if(session('success_profile'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm font-medium" role="alert">{{ session('success_profile') }}</div>
    @endif
    @if(session('success_password'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm font-medium" role="alert">{{ session('success_password') }}</div>
    @endif
    @if(session('success_address'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm font-medium" role="alert">{{ session('success_address') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
        <p class="text-gray-900">{{ $user->name }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <p class="text-gray-900">{{ $user->email }}</p>
      </div>
      @if($user->phone)
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
        <p class="text-gray-900">{{ $user->phone }}</p>
      </div>
      @endif
    </div>

    <div class="mt-6 flex flex-wrap items-center gap-3">
      <button type="button" data-panel="profile"
              class="btn-secondary btn-sm"
              aria-expanded="false" aria-controls="panel-profile"
              aria-label="Edit your profile information">
        Edit Profile
      </button>
      <button type="button" data-panel="password"
              class="btn-secondary btn-sm"
              aria-expanded="false" aria-controls="panel-password"
              aria-label="Change your account password">
        Change Password
      </button>
      <button type="button" data-panel="addresses"
              class="btn-secondary btn-sm"
              aria-expanded="false" aria-controls="panel-addresses"
              aria-label="Manage your saved delivery addresses">
        Manage Addresses
      </button>
      <form method="POST" action="{{ route('logout') }}" class="sm:ml-auto">
        @csrf
        <button type="submit"
                class="text-sm text-gray-400 hover:text-red-500 transition-colors font-medium focus-visible:outline-none focus-visible:underline"
                aria-label="Log out of your account">
          Logout
        </button>
      </form>
    </div>

    {{-- Panel: Edit Profile --}}
    <div id="panel-profile" class="hidden mt-6 pt-6 border-t border-gray-100">
      <h3 class="text-sm font-bold text-gray-900 mb-4">Edit Profile</h3>
      <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="edit_name" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Full Name <span class="text-red-400">*</span></label>
            <input type="text" id="edit_name" name="name" required autocomplete="name"
                   value="{{ old('name', $user->name) }}"
                   class="input w-full rounded-lg border-transparent bg-gray-50 @error('name') ring-2 ring-red-400 @enderror">
            @error('name')<p class="mt-1 text-xs text-red-500 font-medium" role="alert">{{ $message }}</p>@enderror
          </div>
          <div>
            <label for="edit_email" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Email Address <span class="text-red-400">*</span></label>
            <input type="email" id="edit_email" name="email" required autocomplete="email"
                   value="{{ old('email', $user->email) }}"
                   class="input w-full rounded-lg border-transparent bg-gray-50 @error('email') ring-2 ring-red-400 @enderror">
            @error('email')<p class="mt-1 text-xs text-red-500 font-medium" role="alert">{{ $message }}</p>@enderror
          </div>
          <div>
            <label for="edit_phone" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Phone Number</label>
            <input type="tel" id="edit_phone" name="phone" autocomplete="tel"
                   value="{{ old('phone', $user->phone) }}"
                   placeholder="09XX XXX XXXX"
                   class="input w-full rounded-lg border-transparent bg-gray-50 @error('phone') ring-2 ring-red-400 @enderror">
            @error('phone')<p class="mt-1 text-xs text-red-500 font-medium" role="alert">{{ $message }}</p>@enderror
          </div>
        </div>
        <div id="current-pw-field" class="hidden">
          <label for="edit_current_password" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">
            Current Password <span class="text-red-400">*</span>
            <span class="normal-case font-normal text-gray-400 ml-1">(required when changing email)</span>
          </label>
          <input type="password" id="edit_current_password" name="current_password"
                 autocomplete="current-password"
                 class="input w-full rounded-lg border-transparent bg-gray-50 @error('current_password') ring-2 ring-red-400 @enderror">
          @error('current_password')<p class="mt-1 text-xs text-red-500 font-medium" role="alert">{{ $message }}</p>@enderror
        </div>
        <div class="flex gap-3 pt-1">
          <button type="submit" class="btn-primary btn-sm">Save Changes</button>
          <button type="button" data-close-panel="profile" class="btn-outline btn-sm">Cancel</button>
        </div>
      </form>
    </div>

    {{-- Panel: Change Password --}}
    <div id="panel-password" class="hidden mt-6 pt-6 border-t border-gray-100">
      <h3 class="text-sm font-bold text-gray-900 mb-4">Change Password</h3>
      <form method="POST" action="{{ route('account.password.update') }}" class="space-y-4">
        @csrf
        <div>
          <label for="current_password" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Current Password <span class="text-red-400">*</span></label>
          <input type="password" id="current_password" name="current_password" required
                 autocomplete="current-password"
                 class="input w-full rounded-lg border-transparent bg-gray-50 @error('current_password') ring-2 ring-red-400 @enderror">
          @error('current_password')<p class="mt-1 text-xs text-red-500 font-medium" role="alert">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="new_password" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">New Password <span class="text-red-400">*</span></label>
            <input type="password" id="new_password" name="new_password" required
                   autocomplete="new-password"
                   class="input w-full rounded-lg border-transparent bg-gray-50 @error('new_password') ring-2 ring-red-400 @enderror">
            <p class="mt-1 text-[10px] text-gray-400">Min. 8 characters with letters and numbers.</p>
            @error('new_password')<p class="mt-1 text-xs text-red-500 font-medium" role="alert">{{ $message }}</p>@enderror
          </div>
          <div>
            <label for="new_password_confirmation" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Confirm New Password <span class="text-red-400">*</span></label>
            <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                   autocomplete="new-password"
                   class="input w-full rounded-lg border-transparent bg-gray-50">
          </div>
        </div>
        <div class="flex gap-3 pt-1">
          <button type="submit" class="btn-primary btn-sm">Change Password</button>
          <button type="button" data-close-panel="password" class="btn-outline btn-sm">Cancel</button>
        </div>
      </form>
    </div>

    {{-- Panel: Manage Addresses --}}
    <div id="panel-addresses" class="hidden mt-6 pt-6 border-t border-gray-100">
      <h3 class="text-sm font-bold text-gray-900 mb-4">Saved Addresses</h3>

      @if($addresses->isEmpty())
        <p class="text-sm text-gray-400 mb-4">No saved addresses yet.</p>
      @else
        <div class="space-y-3 mb-5">
          @foreach($addresses as $addr)
            <div class="rounded-lg border {{ $addr->is_default ? 'border-brand-200 bg-brand-50' : 'border-gray-200 bg-white' }} p-4">
              <div class="flex items-start justify-between gap-3">
                <div class="text-sm">
                  <div class="flex items-center flex-wrap gap-2 mb-1">
                    @if($addr->label)<span class="text-[10px] font-bold uppercase tracking-widest {{ $addr->is_default ? 'text-brand-600' : 'text-gray-400' }}">{{ $addr->label }}</span>@endif
                    @if($addr->is_default)<span class="text-[10px] font-bold bg-brand-100 text-brand-700 px-2 py-0.5 rounded-full">Default</span>@endif
                  </div>
                  <p class="font-semibold text-gray-900">{{ $addr->full_name }} &middot; {{ $addr->phone }}</p>
                  <p class="text-gray-500 text-xs mt-0.5">{{ $addr->address_line }}{{ $addr->barangay ? ', ' . $addr->barangay : '' }}, {{ $addr->city }}, {{ $addr->province }}</p>
                </div>
                <div class="flex flex-wrap gap-3 shrink-0">
                  @if(!$addr->is_default)
                    <form method="POST" action="{{ route('account.addresses.setDefault', $addr) }}">
                      @csrf
                      <button type="submit" class="text-[10px] font-semibold text-brand-600 hover:underline focus-visible:underline" aria-label="Set as default address">Set Default</button>
                    </form>
                  @endif
                  <button type="button" data-edit-address="{{ $addr->id }}" class="text-[10px] font-semibold text-gray-500 hover:text-gray-900 hover:underline focus-visible:underline" aria-label="Edit address">Edit</button>
                  <form method="POST" action="{{ route('account.addresses.destroy', $addr) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-[10px] font-semibold text-red-400 hover:text-red-600 hover:underline" onclick="return confirm('Remove this address?')" aria-label="Remove address">Remove</button>
                  </form>
                </div>
              </div>
              {{-- Inline edit form --}}
              <div id="edit-address-{{ $addr->id }}" class="hidden mt-4 pt-4 border-t border-gray-200">
                <form method="POST" action="{{ route('account.addresses.update', $addr) }}" class="space-y-3">
                  @csrf @method('PUT')
                  <div class="grid grid-cols-2 gap-3">
                    <div>
                      <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Label</label>
                      <input type="text" name="label" value="{{ $addr->label }}" placeholder="e.g. Home" class="input w-full rounded-lg border-transparent bg-white shadow-sm text-sm">
                    </div>
                    <div>
                      <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Full Name <span class="text-red-400">*</span></label>
                      <input type="text" name="full_name" value="{{ $addr->full_name }}" required class="input w-full rounded-lg border-transparent bg-white shadow-sm text-sm">
                    </div>
                    <div>
                      <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Phone <span class="text-red-400">*</span></label>
                      <input type="tel" name="phone" value="{{ $addr->phone }}" required placeholder="09XXXXXXXXX" class="input w-full rounded-lg border-transparent bg-white shadow-sm text-sm">
                    </div>
                    <div>
                      <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Barangay</label>
                      <input type="text" name="barangay" value="{{ $addr->barangay }}" placeholder="Optional" class="input w-full rounded-lg border-transparent bg-white shadow-sm text-sm">
                    </div>
                  </div>
                  <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Street / Building <span class="text-red-400">*</span></label>
                    <input type="text" name="address_line" value="{{ $addr->address_line }}" required class="input w-full rounded-lg border-transparent bg-white shadow-sm text-sm">
                  </div>
                  <div class="grid grid-cols-2 gap-3">
                    <div>
                      <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">City <span class="text-red-400">*</span></label>
                      <input type="text" name="city" value="{{ $addr->city }}" required class="input w-full rounded-lg border-transparent bg-white shadow-sm text-sm">
                    </div>
                    <div>
                      <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Province <span class="text-red-400">*</span></label>
                      <input type="text" name="province" value="{{ $addr->province }}" required class="input w-full rounded-lg border-transparent bg-white shadow-sm text-sm">
                    </div>
                  </div>
                  <div class="flex items-center gap-2">
                    <input type="checkbox" id="edit_default_{{ $addr->id }}" name="is_default" value="1" {{ $addr->is_default ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                    <label for="edit_default_{{ $addr->id }}" class="text-xs text-gray-600 font-medium cursor-pointer">Set as default delivery address</label>
                  </div>
                  <div class="flex gap-2">
                    <button type="submit" class="btn-primary btn-sm">Save</button>
                    <button type="button" data-cancel-edit="{{ $addr->id }}" class="btn-outline btn-sm">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
          @endforeach
        </div>
      @endif

      <button type="button" id="show-add-address" class="btn-secondary btn-sm" aria-expanded="false" aria-controls="add-address-form">
        + Add New Address
      </button>
      <div id="add-address-form" class="hidden mt-4 p-4 rounded-lg border border-gray-200 bg-gray-50">
        <p class="text-xs font-bold text-gray-700 mb-3">New Address</p>
        <form method="POST" action="{{ route('account.addresses.store') }}" class="space-y-3">
          @csrf
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Label</label>
              <input type="text" name="label" value="{{ old('label') }}" placeholder="e.g. Home" class="input w-full rounded-lg border-transparent bg-white shadow-sm text-sm">
            </div>
            <div>
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Full Name <span class="text-red-400">*</span></label>
              <input type="text" name="full_name" value="{{ old('full_name') }}" required class="input w-full rounded-lg border-transparent bg-white shadow-sm text-sm">
            </div>
            <div>
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Phone <span class="text-red-400">*</span></label>
              <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="09XXXXXXXXX" class="input w-full rounded-lg border-transparent bg-white shadow-sm text-sm">
            </div>
            <div>
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Barangay</label>
              <input type="text" name="barangay" value="{{ old('barangay') }}" placeholder="Optional" class="input w-full rounded-lg border-transparent bg-white shadow-sm text-sm">
            </div>
          </div>
          <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Street / Building <span class="text-red-400">*</span></label>
            <input type="text" name="address_line" value="{{ old('address_line') }}" required class="input w-full rounded-lg border-transparent bg-white shadow-sm text-sm">
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">City <span class="text-red-400">*</span></label>
              <input type="text" name="city" value="{{ old('city') }}" required class="input w-full rounded-lg border-transparent bg-white shadow-sm text-sm">
            </div>
            <div>
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Province <span class="text-red-400">*</span></label>
              <input type="text" name="province" value="{{ old('province') }}" required class="input w-full rounded-lg border-transparent bg-white shadow-sm text-sm">
            </div>
          </div>
          <div class="flex items-center gap-2">
            <input type="checkbox" id="new-is-default" name="is_default" value="1" class="w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
            <label for="new-is-default" class="text-xs text-gray-600 font-medium cursor-pointer">Set as default delivery address</label>
          </div>
          <div class="flex gap-2">
            <button type="submit" class="btn-primary btn-sm">Save Address</button>
            <button type="button" id="cancel-add-address" class="btn-outline btn-sm">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Orders Section --}}
  <div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-xl font-bold text-gray-900 mb-1">My Orders @if($totalCount > 0)<span class="text-gray-400 font-normal text-base">({{ $totalCount }})</span>@endif</h2>

    {{-- Trust Microcopy --}}
    <p class="text-xs text-gray-500 mb-5 leading-relaxed">
      Orders are confirmed by our team before delivery. You'll be contacted for payment and delivery coordination.
    </p>

    {{-- Order action notices --}}
    @if(session('success_order'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm font-medium" role="alert">{{ session('success_order') }}</div>
    @endif
    {{-- Filter Tabs --}}
    @php
      $activeStatus = $status ?? '';
      $tabs = [
        ''           => 'All',
        'pending'    => 'Pending',
        'processing' => 'Processing',
        'shipped'    => 'Shipped',
        'completed'  => 'Completed',
        'cancelled'  => 'Cancelled',
      ];
      $tabCountMap = [
        ''           => $totalCount,
        'pending'    => $statusCounts['pending']    ?? 0,
        'processing' => $statusCounts['processing'] ?? 0,
        'shipped'    => $statusCounts['shipped']    ?? 0,
        'completed'  => ($statusCounts['completed'] ?? 0) + ($statusCounts['delivered'] ?? 0),
        'cancelled'  => $statusCounts['cancelled']  ?? 0,
      ];
    @endphp
    <div class="flex flex-wrap gap-2 mb-6 pb-4 border-b border-gray-100"
         role="tablist"
         aria-label="Filter orders by status">
      @foreach($tabs as $tabStatus => $tabLabel)
        @php $tabCount = $tabCountMap[$tabStatus] ?? 0; @endphp
        <a href="{{ url('/account') }}{{ $tabStatus ? '?status=' . $tabStatus : '' }}"
           role="tab"
           aria-selected="{{ $activeStatus === $tabStatus ? 'true' : 'false' }}"
           class="inline-flex items-center gap-1.5 h-8 px-4 rounded-full text-xs font-semibold whitespace-nowrap transition-colors
                  focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-1
                  {{ $activeStatus === $tabStatus
                    ? 'bg-brand-600 text-white shadow-sm'
                    : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
          {{ $tabLabel }}
          @if($tabCount > 0)
          <span class="inline-flex items-center justify-center min-w-[1.125rem] h-[1.125rem] px-1 rounded-full text-[10px] font-bold leading-none
                       {{ $activeStatus === $tabStatus ? 'bg-white/30 text-white' : 'bg-gray-200 text-gray-600' }}">
            {{ $tabCount }}
          </span>
          @endif
        </a>
      @endforeach
    </div>

    {{-- Skeleton Loader (shown until DOM ready) --}}
    <div id="orders-skeleton" class="space-y-4" aria-hidden="true" aria-label="Loading orders">
      @for ($i = 0; $i < 2; $i++)
      <div class="border border-gray-100 rounded-lg p-5 animate-pulse">
        <div class="flex justify-between items-start mb-4">
          <div class="space-y-2">
            <div class="h-3 w-28 bg-gray-200 rounded"></div>
            <div class="h-2.5 w-20 bg-gray-100 rounded"></div>
          </div>
          <div class="flex gap-2 items-center">
            <div class="h-5 w-16 bg-gray-200 rounded-full"></div>
            <div class="h-4 w-14 bg-gray-100 rounded"></div>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-gray-100 rounded-lg shrink-0"></div>
          <div class="flex-1 space-y-1.5">
            <div class="h-2.5 w-3/4 bg-gray-100 rounded"></div>
            <div class="h-2 w-1/2 bg-gray-100 rounded"></div>
          </div>
        </div>
      </div>
      @endfor
    </div>

    {{-- Orders Content (revealed on DOM load) --}}
    <div id="orders-content" class="hidden">

      @if($orders->isEmpty())
        {{-- Empty State --}}
        <div class="text-center py-16">
          <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
          </div>
          @if($activeStatus)
            <h3 class="text-lg font-medium text-gray-900 mb-2">No {{ $tabs[$activeStatus] ?? ucfirst($activeStatus) }} orders</h3>
            <p class="text-gray-500 mb-6 text-sm">You don't have any {{ strtolower($tabs[$activeStatus] ?? $activeStatus) }} orders right now.</p>
            <a href="{{ url('/account') }}" class="btn-secondary btn-sm inline-flex">← View All Orders</a>
          @else
            <h3 class="text-lg font-medium text-gray-900 mb-2">No orders yet</h3>
            <p class="text-gray-500 mb-6 text-sm">Start shopping to see your orders here.</p>
            <a href="{{ route('shop') }}" class="btn-primary inline-flex">Browse Products</a>
          @endif
        </div>

      @else
        {{-- Orders List --}}
        <div class="space-y-4">
          @foreach($orders as $order)
            @php
              $statusMap = [
                'pending'    => ['bg-yellow-50 text-yellow-700 ring-1 ring-yellow-200', 'Pending'],
                'processing' => ['bg-blue-50 text-blue-700 ring-1 ring-blue-200',       'Processing'],
                'shipped'    => ['bg-purple-50 text-purple-700 ring-1 ring-purple-200', 'Shipped'],
                'delivered'  => ['bg-green-50 text-green-700 ring-1 ring-green-200',    'Delivered'],
                'completed'  => ['bg-green-50 text-green-700 ring-1 ring-green-200',    'Completed'],
                'cancelled'  => ['bg-red-50 text-red-700 ring-1 ring-red-200',          'Cancelled'],
              ];
              [$badge, $badgeLabel] = $statusMap[$order->status] ?? ['bg-gray-50 text-gray-700 ring-1 ring-gray-200', ucfirst($order->status)];
            @endphp

            <div class="border border-gray-200 rounded-lg p-5 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                 role="article"
                 aria-label="Order {{ $order->order_number }}, {{ $badgeLabel }}, ₱{{ number_format($order->total, 2) }}">

              {{-- Order Header --}}
              <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
                <div>
                  <p class="text-sm font-bold text-gray-900">{{ $order->order_number }}</p>
                  <p class="text-xs text-gray-500 mt-0.5">
                    {{ $order->created_at->format('F j, Y') }}
                    <span class="text-gray-400">&middot; {{ $order->created_at->diffForHumans() }}</span>
                  </p>
                </div>
                <div class="flex items-center gap-3">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}"
                        role="status"
                        aria-label="Order status: {{ $badgeLabel }}">
                    {{ $badgeLabel }}
                  </span>
                  <span class="text-sm font-bold text-brand-600">₱{{ number_format($order->total, 2) }}</span>
                </div>
              </div>

              {{-- Order Items --}}
              <div class="space-y-2">
                @foreach($order->items as $item)
                  @php
                    $thumb = null;
                    if ($item->product && !empty($item->product->images)) {
                      $imgs  = is_array($item->product->images)
                               ? $item->product->images
                               : json_decode($item->product->images, true);
                      $thumb = $imgs[0] ?? null;
                    }
                  @endphp
                  <div class="flex items-center gap-3 text-sm">
                    @if($thumb)
                      <img src="{{ $thumb }}"
                           alt="{{ $item->product_name }}"
                           class="w-10 h-10 rounded-lg object-cover shrink-0 border border-gray-100"
                           loading="lazy">
                    @else
                      <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0" aria-hidden="true">
                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                      </div>
                    @endif
                    <span class="text-gray-700 flex-1 min-w-0">
                      <span class="truncate block">{{ $item->product_name }}</span>
                    </span>
                    <span class="text-gray-400 shrink-0">× {{ $item->quantity }}</span>
                    <span class="text-gray-900 font-medium shrink-0">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                  </div>
                @endforeach
              </div>

              {{-- Shipping --}}
              <div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-500">
                <span class="font-medium text-gray-700">Ship to:</span>
                {{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_province }}
              </div>

              {{-- Expandable Details --}}
              <div class="order-extra-details hidden mt-4 pt-4 border-t border-gray-100 space-y-1 text-xs text-gray-500">
                @if($order->tracking_number)
                <p><span class="font-medium text-gray-700">Tracking #:</span> {{ $order->tracking_number }}</p>
                @endif
                @if($order->payment_method)
                <p><span class="font-medium text-gray-700">Payment:</span> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                @endif
                @if($order->notes)
                <p><span class="font-medium text-gray-700">Notes:</span> {{ $order->notes }}</p>
                @endif
                <p><span class="font-medium text-gray-700">Items:</span> {{ $order->items->count() }}</p>
              </div>

              {{-- Per-order CTAs --}}
              <div class="mt-4 pt-4 border-t border-gray-100 flex flex-col sm:flex-row gap-2">
                <button type="button"
                        data-toggle-details
                        aria-expanded="false"
                        aria-label="View details for order {{ $order->order_number }}"
                        class="flex-1 text-xs font-semibold text-brand-600 hover:text-brand-700
                               border border-brand-200 hover:border-brand-400 hover:bg-brand-50
                               rounded-lg px-4 py-2 transition-colors
                               focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-500">
                  View Details
                </button>
                @if($order->status === 'pending')
                <form method="POST" action="{{ route('account.orders.cancel', $order) }}" class="flex-1"
                      onsubmit="return confirm('Cancel order {{ $order->order_number }}? This cannot be undone.')">
                  @csrf
                  <button type="submit"
                          aria-label="Cancel order {{ $order->order_number }}"
                          class="w-full text-xs font-semibold text-red-500 hover:text-red-700
                                 border border-red-200 hover:border-red-300 hover:bg-red-50
                                 rounded-lg px-4 py-2 transition-colors
                                 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-400">
                    Cancel Order
                  </button>
                </form>
                @elseif($order->tracking_number)
                <button type="button"
                        data-toggle-tracking
                        aria-expanded="false"
                        aria-label="View tracking for order {{ $order->order_number }}"
                        class="flex-1 text-xs font-semibold text-purple-600 hover:text-purple-700
                               border border-purple-200 hover:border-purple-400 hover:bg-purple-50
                               rounded-lg px-4 py-2 transition-colors
                               focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-purple-400">
                  Track Order
                </button>
                @else
                <button type="button"
                        disabled
                        title="Tracking will appear once your order is shipped."
                        aria-label="Tracking not yet available for order {{ $order->order_number }}"
                        class="flex-1 text-xs font-semibold text-gray-400
                               border border-gray-100 bg-gray-50 rounded-lg px-4 py-2
                               cursor-not-allowed">
                  Track Order
                </button>
                @endif
              </div>

            </div>
          @endforeach
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
          <div class="mt-6">
            {{ $orders->links() }}
          </div>
        @endif
      @endif

    </div>{{-- /orders-content --}}
  </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

  // ─── Skeleton swap ──────────────────────────────────────────────────────────
  const skeleton = document.getElementById('orders-skeleton');
  const content  = document.getElementById('orders-content');
  if (skeleton) skeleton.classList.add('hidden');
  if (content)  content.classList.remove('hidden');

  // ─── View Details toggle ────────────────────────────────────────────────────
  document.querySelectorAll('[data-toggle-details]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const card    = btn.closest('[role="article"]');
      const details = card.querySelector('.order-extra-details');
      if (!details) return;
      const isOpen = !details.classList.contains('hidden');
      details.classList.toggle('hidden', isOpen);
      btn.textContent = isOpen ? 'View Details' : 'Hide Details';
      btn.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
    });
  });

  // ─── Track Order toggle ───────────────────────────────────────────────────
  document.querySelectorAll('[data-toggle-tracking]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const card    = btn.closest('[role="article"]');
      const details = card.querySelector('.order-extra-details');
      if (!details) return;
      const isOpen = !details.classList.contains('hidden');
      details.classList.toggle('hidden', isOpen);
      btn.textContent = isOpen ? 'Track Order' : 'Hide Tracking';
      btn.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
    });
  });

  // ─── Account panel open/close ───────────────────────────────────────────────
  const allPanels = ['profile', 'password', 'addresses'];

  function openPanel(name) {
    allPanels.forEach(function (p) {
      const panel = document.getElementById('panel-' + p);
      const btn   = document.querySelector('[data-panel="' + p + '"]');
      if (p === name) {
        panel && panel.classList.remove('hidden');
        btn   && btn.setAttribute('aria-expanded', 'true');
        panel && panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      } else {
        panel && panel.classList.add('hidden');
        btn   && btn.setAttribute('aria-expanded', 'false');
      }
    });
  }

  function closePanel(name) {
    const panel = document.getElementById('panel-' + name);
    const btn   = document.querySelector('[data-panel="' + name + '"]');
    panel && panel.classList.add('hidden');
    btn   && btn.setAttribute('aria-expanded', 'false');
  }

  document.querySelectorAll('[data-panel]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const name   = btn.getAttribute('data-panel');
      const isOpen = btn.getAttribute('aria-expanded') === 'true';
      isOpen ? closePanel(name) : openPanel(name);
    });
  });

  document.querySelectorAll('[data-close-panel]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      closePanel(btn.getAttribute('data-close-panel'));
    });
  });

  // ─── Re-open panel on validation redirect ───────────────────────────────────
  var openPanelFlag = '{{ session("open_panel") }}';
  if (openPanelFlag && allPanels.includes(openPanelFlag)) {
    openPanel(openPanelFlag);
  }

  // ─── Show current-password field when email changes ─────────────────────────
  const emailInput    = document.getElementById('edit_email');
  const pwField       = document.getElementById('current-pw-field');
  const pwInput       = document.getElementById('edit_current_password');
  const originalEmail = '{{ addslashes($user->email) }}';

  if (emailInput && pwField) {
    function checkEmailChange() {
      const changed = emailInput.value.trim() !== originalEmail;
      pwField.classList.toggle('hidden', !changed);
      if (pwInput) pwInput.required = changed;
    }
    emailInput.addEventListener('input', checkEmailChange);
    checkEmailChange();
  }

  // ─── Inline address edit toggle ─────────────────────────────────────────────
  document.querySelectorAll('[data-edit-address]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const id   = btn.getAttribute('data-edit-address');
      const form = document.getElementById('edit-address-' + id);
      form && form.classList.toggle('hidden');
    });
  });

  document.querySelectorAll('[data-cancel-edit]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const form = document.getElementById('edit-address-' + btn.getAttribute('data-cancel-edit'));
      form && form.classList.add('hidden');
    });
  });

  // ─── Add address form toggle ─────────────────────────────────────────────────
  const showAddBtn   = document.getElementById('show-add-address');
  const addForm      = document.getElementById('add-address-form');
  const cancelAddBtn = document.getElementById('cancel-add-address');

  showAddBtn && showAddBtn.addEventListener('click', function () {
    const isOpen = addForm && !addForm.classList.contains('hidden');
    addForm && addForm.classList.toggle('hidden', isOpen);
    showAddBtn.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
  });

  cancelAddBtn && cancelAddBtn.addEventListener('click', function () {
    addForm && addForm.classList.add('hidden');
    showAddBtn && showAddBtn.setAttribute('aria-expanded', 'false');
  });

});
</script>
@endpush

