<div>
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Employees</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage employee records and information</p>
            </div>
            <a href="{{ route('employees.create') }}"
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Employee
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <p class="text-green-800 dark:text-green-200">{{ session('success') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <p class="text-red-800 dark:text-red-200">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Filters --}}
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="grid grid-cols-1 gap-4">
            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Search by name, email, ID, department, position..."
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
            </div>

            {{-- Filter Row --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Status Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <flux:select wire:model.live="statusFilter">
                        <option value="all">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="on_leave">On Leave</option>
                        <option value="terminated">Terminated</option>
                    </flux:select>
                </div>

                {{-- Department Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department</label>
                    <flux:select wire:model.live="departmentFilter">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </flux:select>
                </div>

                {{-- Position Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Position</label>
                    <flux:select wire:model.live="positionFilter">
                        <option value="">All Positions</option>
                        @foreach($positions as $pos)
                            <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                        @endforeach
                    </flux:select>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th wire:click="sortBy('userName')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <div class="flex items-center gap-1">
                                <span>Employee</span>
                                @if($sortField === 'userName')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('employeeId')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <div class="flex items-center gap-1">
                                <span>Employee ID</span>
                                @if($sortField === 'employeeId')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('departmentName')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <div class="flex items-center gap-1">
                                <span>Department</span>
                                @if($sortField === 'departmentName')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('positionName')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <div class="flex items-center gap-1">
                                <span>Position</span>
                                @if($sortField === 'positionName')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('employmentStatus')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <div class="flex items-center gap-1">
                                <span>Status</span>
                                @if($sortField === 'employmentStatus')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('joinDate')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <div class="flex items-center gap-1">
                                <span>Join Date</span>
                                @if($sortField === 'joinDate')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($employees as $employee)
                        <tr wire:key="employee-{{ $employee->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $employee->userName }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $employee->userEmail }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-mono text-gray-900 dark:text-white">{{ $employee->employeeId }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $employee->departmentName ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $employee->positionName ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($employee->employmentStatus === 'active') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($employee->employmentStatus === 'inactive') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                    @elseif($employee->employmentStatus === 'on_leave') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                    @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                    @endif">
                                    {{ $employee->statusLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($employee->joinDate)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    {{-- Edit Button with Tooltip --}}
                                    <flux:tooltip content="Edit Employee" position="left">
                                        <a href="{{ route('employees.edit', $employee->id) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-700 hover:bg-blue-50 dark:text-blue-400 dark:hover:text-blue-300 dark:hover:bg-blue-900/20 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                    </flux:tooltip>

                                    {{-- Delete Button with Tooltip --}}
                                    <flux:tooltip content="Delete Employee" position="left">
                                        <button wire:click="confirmDelete({{ $employee->id }})"
                                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-700 hover:bg-red-50 dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-900/20 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </flux:tooltip>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <p class="text-lg font-medium">No employees found</p>
                                    <p class="text-sm mt-1">Try adjusting your search or filter criteria</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Info --}}
        @if ($total > 0)
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        Showing {{ $employees->count() }} of {{ $total }} employees
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    @if ($deleteId)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" wire:click="cancelDelete">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4" wire:click.stop>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Confirm Deletion</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Are you sure you want to delete this employee? This action cannot be undone and will also delete the associated user account.
                </p>
                <div class="flex justify-end gap-3">
                    <button wire:click="cancelDelete"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Cancel
                    </button>
                    <button wire:click="delete"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                        Delete Employee
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
