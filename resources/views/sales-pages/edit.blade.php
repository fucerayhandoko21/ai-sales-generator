@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('pages.show', $salesPage) }}" class="text-sm text-gray-400 hover:text-gray-600">← Kembali ke Preview</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Edit & Re-generate</h1>
        <p class="text-gray-500 text-sm mt-1">Ubah informasi produk lalu re-generate salespage-nya.</p>
    </div>

    <form method="POST" action="{{ route('pages.update', $salesPage) }}" id="editForm">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                <input type="text" name="product_name" value="{{ old('product_name', $salesPage->product_name) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description', $salesPage->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fitur Utama</label>
                <textarea name="key_features" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('key_features', $salesPage->key_features) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Target Audience</label>
                    <input type="text" name="target_audience" value="{{ old('target_audience', $salesPage->target_audience) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                    <input type="text" name="price" value="{{ old('price', $salesPage->price) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unique Selling Points</label>
                <textarea name="unique_selling_points" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('unique_selling_points', $salesPage->unique_selling_points) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Template</label>
                <div class="grid grid-cols-3 gap-3">
                    @foreach(['modern' => ['🌊', 'Modern'], 'minimal' => ['⬜', 'Minimal'], 'bold' => ['🔥', 'Bold']] as $value => [$icon, $label])
                        <label class="cursor-pointer">
                            <input type="radio" name="template" value="{{ $value }}"
                                   {{ old('template', $salesPage->template) === $value ? 'checked' : '' }} class="sr-only peer">
                            <div class="border-2 border-gray-200 rounded-xl p-3 text-center peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-indigo-300 transition">
                                <div class="text-2xl mb-1">{{ $icon }}</div>
                                <div class="text-sm font-medium text-gray-700">{{ $label }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

        </div>

        <button type="submit" id="submitBtn"
                class="w-full mt-6 bg-indigo-600 text-white font-semibold py-3 rounded-xl hover:bg-indigo-700 transition flex items-center justify-center gap-2">
            <span id="btnText">🔄 Re-generate Sales Page</span>
            <span id="btnLoading" class="hidden">
                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                Re-generating...
            </span>
        </button>
    </form>
</div>

<script>
    document.getElementById('editForm').addEventListener('submit', function() {
        document.getElementById('btnText').classList.add('hidden');
        document.getElementById('btnLoading').classList.remove('hidden');
        document.getElementById('submitBtn').disabled = true;
    });
</script>

@endsection