<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} — AI Sales Page Generator</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-600">
                ⚡ SalesAI
            </a>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
                <a href="{{ route('product.create') }}"
                   class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                    + Generate Page
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="text-sm text-gray-500 hover:text-red-500 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="max-w-6xl mx-auto mt-4 px-6">
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($errors->has('api'))
        <div class="max-w-6xl mx-auto mt-4 px-6">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                {{ $errors->first('api') }}
            </div>
        </div>
    @endif

    {{-- Content --}}
    <main class="max-w-6xl mx-auto px-6 py-8">
        @yield('content')
    </main>

</body>
</html>