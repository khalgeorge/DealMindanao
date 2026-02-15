<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - DealMindanao</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-6">
    <div class="w-full max-w-md bg-white rounded-lg border border-gray-100 shadow-xl overflow-hidden">
        <div class="p-8 text-white text-center" style="background-color: #16a34a;">
            <h1 class="text-2xl font-black italic tracking-tighter">DEALMINDANAO</h1>
            <p class="text-xs font-bold uppercase tracking-widest opacity-70 mt-2">Administrative Access</p>
        </div>
        
        <form class="p-8 space-y-6" method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm font-bold">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm font-bold">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-bold">
                    {{ session('status') }}
                </div>
            @endif
            
            <!-- Email Field -->
            <div>
                <label for="email" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent @error('email') border-red-500 @enderror" 
                    style="--tw-ring-color: #16a34a;"
                    placeholder="Enter email" 
                    value="{{ old('email', 'admin@dealmindanao.ph') }}" 
                    required 
                    autofocus
                >
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent @error('password') border-red-500 @enderror" 
                    style="--tw-ring-color: #16a34a;"
                    placeholder="••••••••" 
                    required
                >
            </div>
            
            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full py-4 text-white font-black uppercase tracking-widest rounded-lg transition-all transform hover:scale-[1.02]"
                style="background-color: #16a34a;"
                onmouseover="this.style.backgroundColor='#15803d'" 
                onmouseout="this.style.backgroundColor='#16a34a'"
            >
                Sign In
            </button>
            
            <!-- Contact Support -->
            <p class="text-center text-xs text-gray-400 font-bold uppercase tracking-wider">
                Forgotten password? <a href="#" class="hover:underline" style="color: #16a34a;">Contact Support</a>
            </p>
        </form>
    </div>
</body>
</html>
