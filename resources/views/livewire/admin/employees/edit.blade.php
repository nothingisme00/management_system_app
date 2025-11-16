<div>
    <div class="mb-6">
        <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('employees.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Employee</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Employee ID: {{ $employee_display_id }}</p>
            </div>
        </div>
    </div>

    <form wire:submit="update">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name *</label>
                        <input type="text" wire:model="name" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                        <input type="email" wire:model="email" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                        <input type="text" wire:model="phone_number" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address</label>
                        <textarea wire:model="address" rows="2" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"></textarea>
                    </div>
                </div>
            </div>

            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Employment Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role *</label>
                        <flux:select wire:model="role_id">
                            @foreach($roles as $role)
                                <flux:option value="{{ $role->id }}">{{ $role->name }}</flux:option>
                            @endforeach
                        </flux:select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department</label>
                        <flux:select wire:model="department_id" placeholder="Select Department">
                            @foreach($departments as $dept)
                                <flux:option value="{{ $dept->id }}">{{ $dept->name }}</flux:option>
                            @endforeach
                        </flux:select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Position</label>
                        <flux:select wire:model="position_id" placeholder="Select Position">
                            @foreach($positions as $pos)
                                <flux:option value="{{ $pos->id }}">{{ $pos->name }}</flux:option>
                            @endforeach
                        </flux:select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Employment Status *</label>
                        <flux:select wire:model="employment_status">
                            <flux:option value="active">Active</flux:option>
                            <flux:option value="inactive">Inactive</flux:option>
                            <flux:option value="on_leave">On Leave</flux:option>
                            <flux:option value="terminated">Terminated</flux:option>
                        </flux:select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Join Date *</label>
                        <input type="date" wire:model="join_date" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Termination Date</label>
                        <input type="date" wire:model="termination_date" class="w-full px-4 py-2 border dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
            </div>

            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Reset Password to Default</h3>
                <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Reset to Default Password</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Set password to: <code class="bg-gray-200 dark:bg-gray-600 px-2 py-0.5 rounded">pass1234</code></p>
                    </div>
                    <button type="button" wire:click="resetPasswordToDefault" wire:confirm="Are you sure you want to reset this employee's password to 'pass1234'?"
                            class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition">
                        Reset Password
                    </button>
                </div>
            </div>

            <div class="p-6 bg-gray-50 dark:bg-gray-700/50 flex justify-between">
                <button type="button" wire:click="terminate" wire:confirm="Are you sure you want to terminate this employee?"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                    Terminate Employee
                </button>
                <div class="flex gap-3">
                    <a href="{{ route('employees.index') }}" class="px-4 py-2 border dark:border-gray-600 rounded-lg dark:text-gray-300">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Update Employee</button>
                </div>
            </div>
        </div>
    </form>
</div>
