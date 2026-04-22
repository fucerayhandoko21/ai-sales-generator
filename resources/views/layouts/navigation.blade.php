<nav class="bg-white border-b border-gray-200 px-6 py-4">
    <div class="max-w-6xl mx-auto flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-600">
            ⚡ SalesAI
        </a>

        {{-- Nav Links --}}
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-500">
                {{ Auth::user()->name }}
            </span>

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