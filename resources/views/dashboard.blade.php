@extends('layouts.app')

@section('content')

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">My Sales Pages</h1>
        <p class="text-gray-500 text-sm mt-1">{{ $pages->count() }} page(s) generated</p>
    </div>
    <a href="{{ route('product.create') }}"
       class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 transition font-medium">
        + New Page
    </a>
</div>

@if($pages->isEmpty())
    <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-gray-300">
        <div class="text-5xl mb-4">🚀</div>
        <h2 class="text-lg font-semibold text-gray-700">Belum ada sales page</h2>
        <p class="text-gray-400 text-sm mt-1 mb-6">Generate sales page pertamamu sekarang!</p>
        <a href="{{ route('product.create') }}"
           class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700 transition">
            Generate Sekarang
        </a>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($pages as $page)
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-5 py-4">
                    <span class="text-xs text-indigo-200 uppercase tracking-wider font-medium">
                        {{ ucfirst($page->template) }} Template
                    </span>
                    <h3 class="text-white font-bold text-lg mt-1 truncate">
                        {{ $page->product_name }}
                    </h3>
                </div>
                <div class="px-5 py-4">
                    <p class="text-gray-500 text-sm line-clamp-2">{{ $page->description }}</p>
                    <div class="mt-3 flex items-center gap-3 text-xs text-gray-400">
                        <span>🎯 {{ $page->target_audience }}</span>
                        <span>💰 {{ $page->price }}</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">{{ $page->created_at->diffForHumans() }}</p>
                </div>
                <div class="px-5 py-3 border-t border-gray-100 flex items-center gap-2">
                    <a href="{{ route('pages.show', $page) }}"
                       class="flex-1 text-center text-sm bg-indigo-50 text-indigo-600 px-3 py-2 rounded-lg hover:bg-indigo-100 transition font-medium">
                        👁 Preview
                    </a>
                    <a href="{{ route('pages.edit', $page) }}"
                       class="flex-1 text-center text-sm bg-gray-50 text-gray-600 px-3 py-2 rounded-lg hover:bg-gray-100 transition font-medium">
                        ✏️ Edit
                    </a>
                    <form method="POST" action="{{ route('pages.destroy', $page) }}"
                          onsubmit="return confirm('Hapus sales page ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-sm bg-red-50 text-red-500 px-3 py-2 rounded-lg hover:bg-red-100 transition font-medium">
                            🗑
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection