<div class="flex flex-col items-center pt-5 h-screen">
    <div>
        <input type="file" id="affiliateFile" wire:model="affiliateFile" class="hidden" />

        <label for="affiliateFile"
               class="cursor-pointer inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
            Upload File
        </label>
    </div>

    @error('affiliateFile')
        <div class="block mt-4 text-red-600">{{ $message }}</div>
    @enderror

    @if($errorMessage)
        <div class="block mt-4 text-red-600">{{ $errorMessage }}</div>
    @endif

    @if ($affiliateCollection)
        <div class="mt-4 text-green-600 font-semibold text-center w-full max-w-2xl px-4 pb-6">
            @foreach ($affiliateCollection as $affiliate)
                <div class="p-4 border rounded shadow-sm bg-white">
                    <p>ID: {{ $affiliate['affiliate_id'] }}</p>
                    <p>Name: {{ $affiliate['name'] }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>