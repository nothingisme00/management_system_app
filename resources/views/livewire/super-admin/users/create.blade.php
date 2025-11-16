<div>
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('users.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Create New User</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Default password: <code class="bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded">pass1234</code></p>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form wire:submit="save">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            {{-- User Information --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">User Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="name"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" wire:model="email"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <flux:select wire:model="role_id" placeholder="Select Role">
                            @foreach($roles as $role)
                                <flux:option value="{{ $role->id }}">{{ $role->name }}</flux:option>
                            @endforeach
                        </flux:select>
                        @error('role_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password (Read-only default) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="password"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Default password can be changed by user after login</p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="p-6 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-3">
                <a href="{{ route('users.index') }}"
                   class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24" wire:loading>
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove>Create User</span>
                    <span wire:loading>Creating...</span>
                </button>
            </div>
        </div>
    </form>
</div>
