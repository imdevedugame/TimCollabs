<div 
    x-cloak
    x-data="{ visible: @entangle('show') }"
    x-show="visible"
    x-init="
        $wire.on('toast-shown', () => {
            setTimeout(() => $wire.show = false, $wire.duration);
        })
    "
    class="fixed bottom-4 right-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg border-l-4 border-{{ $type }}-500 max-w-sm"
>
    <div class="flex items-center gap-3">
        <!-- Icon -->
        @if($type === 'success')
        <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">...</svg>
        @endif
        
        <!-- Content -->
        <div class="flex-1">
            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $message }}</p>
            @if($text)
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $text }}</p>
            @endif
        </div>
        
        <!-- Close Button -->
        <button @click="$wire.show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
            &times;
        </button>
    </div>
</div>