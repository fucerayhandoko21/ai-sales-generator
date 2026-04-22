@extends('layouts.app')

@section('content')

{{-- Toolbar --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-400 hover:text-gray-600">
            ← Dashboard
        </a>
        <h1 class="text-xl font-bold text-gray-900 mt-1">{{ $salesPage->product_name }}</h1>
    </div>
    <div class="flex items-center gap-3">
        {{-- Export HTML (Bonus) --}}
        <a href="{{ route('pages.export', $salesPage) }}"
           class="text-sm border border-gray-300 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-50 transition">
            ⬇️ Export HTML
        </a>
        <a href="{{ route('pages.edit', $salesPage) }}"
           class="text-sm bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            ✏️ Re-generate
        </a>
    </div>
</div>

{{-- Preview Frame --}}
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

    {{-- Browser Mock Bar --}}
    <div class="bg-gray-100 border-b border-gray-200 px-4 py-3 flex items-center gap-3">
        <div class="flex gap-1.5">
            <div class="w-3 h-3 rounded-full bg-red-400"></div>
            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
            <div class="w-3 h-3 rounded-full bg-green-400"></div>
        </div>
        <div class="flex-1 bg-white rounded-md px-3 py-1 text-xs text-gray-400 border border-gray-200">
            🔒 preview — {{ $salesPage->product_name }}
        </div>
    </div>

    {{-- Rendered Sales Page --}}
    <div class="w-full overflow-auto">
        {!! $salesPage->generated_html !!}
    </div>

</div>

@endsection