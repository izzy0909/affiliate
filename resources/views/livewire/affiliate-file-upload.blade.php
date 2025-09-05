<div class="flex flex-col items-center pt-5 h-screen"
     x-data="{ uploading: false, progress: 0 }"
     x-on:livewire-upload-start="uploading = true"
     x-on:livewire-upload-finish="uploading = false; progress = 0"
     x-on:livewire-upload-error="uploading = false; progress = 0"
     x-on:livewire-upload-progress="progress = $event.detail.progress">

    <div>
        <input type="file"
               id="affiliateFile"
               wire:model="affiliateFile"
               class="hidden"
               accept=".txt"
        />

        <label for="affiliateFile"
               :class="{
                   'opacity-50 cursor-not-allowed pointer-events-none': uploading,
                   'cursor-pointer': !uploading
               }"
               :aria-disabled="uploading.toString()"
               class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
            Upload File
        </label>
    </div>

    <template x-if="uploading">
        <div class="w-full max-w-md mt-4">
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div class="bg-blue-600 h-4 rounded-full transition-all"
                     :style="`width: ${progress}%`"></div>
            </div>
            <p class="text-center mt-1 text-sm text-gray-700" x-text="progress + '%'"></p>
        </div>
    </template>

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