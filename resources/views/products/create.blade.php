@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Generate Sales Page</h1>
        <p class="text-gray-500 text-sm mt-1">
            Isi informasi produkmu, AI akan generate sales page secara otomatis.
        </p>
    </div>

    <form method="POST" action="{{ route('product.store') }}" id="generateForm">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">

            {{-- Product Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Produk / Layanan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="product_name"
                       value="{{ old('product_name') }}"
                       placeholder="e.g. SuperBoost Protein Shake"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('product_name') border-red-400 @enderror">
                @error('product_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Deskripsi Produk <span class="text-red-500">*</span>
                </label>
                <textarea name="description" rows="3"
                          placeholder="Jelaskan produkmu secara singkat..."
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Key Features --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Fitur Utama <span class="text-red-500">*</span>
                </label>
                <textarea name="key_features" rows="3"
                          placeholder="e.g. 30g protein per serving, bebas gula, rasa cokelat premium, mudah larut"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('key_features') border-red-400 @enderror">{{ old('key_features') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Pisahkan dengan koma</p>
                @error('key_features')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Target Audience + Price --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Target Audience <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="target_audience"
                           value="{{ old('target_audience') }}"
                           placeholder="e.g. Gym-goers usia 18-35"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('target_audience') border-red-400 @enderror">
                    @error('target_audience')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Harga <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="price"
                           value="{{ old('price') }}"
                           placeholder="e.g. Rp 299.000"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('price') border-red-400 @enderror">
                    @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- USP --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Unique Selling Points
                    <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <textarea name="unique_selling_points" rows="2"
                          placeholder="e.g. Satu-satunya protein shake dengan teknologi nano-protein di Indonesia"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('unique_selling_points') }}</textarea>
            </div>

            {{-- Template --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Pilih Template
                </label>
                <div class="grid grid-cols-3 gap-3">
                    @foreach(['modern' => ['🌊', 'Modern', 'Blue/purple gradient, professional'], 'minimal' => ['⬜', 'Minimal', 'Clean, whitespace, B&W'], 'bold' => ['🔥', 'Bold', 'High-contrast, energetic']] as $value => [$icon, $label, $desc])
                        <label class="cursor-pointer">
                            <input type="radio" name="template" value="{{ $value }}"
                                   {{ old('template', 'modern') === $value ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="border-2 border-gray-200 rounded-xl p-3 text-center peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-indigo-300 transition">
                                <div class="text-2xl mb-1">{{ $icon }}</div>
                                <div class="text-sm font-medium text-gray-700">{{ $label }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $desc }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Submit --}}
        <button type="submit" id="submitBtn"
                class="w-full mt-6 bg-indigo-600 text-white font-semibold py-3 rounded-xl hover:bg-indigo-700 transition flex items-center justify-center gap-2">
            <span id="btnText">⚡ Generate Sales Page</span>
            <span id="btnLoading" class="hidden">
                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                Generating... (10-20 detik)
            </span>
        </button>

    </form>

</div>

<script>
    document.getElementById('generateForm').addEventListener('submit', function() {
        document.getElementById('btnText').classList.add('hidden');
        document.getElementById('btnLoading').classList.remove('hidden');
        document.getElementById('submitBtn').disabled = true;
    });
</script>

@endsection