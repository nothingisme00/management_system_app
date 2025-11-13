<div class="space-y-6">
    <flux:heading size="xl">Dashboard HRD</flux:heading>

    <flux:separator />

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <flux:card>
            <div class="space-y-2">
                <flux:heading size="lg">Total Employees</flux:heading>
                <div class="text-3xl font-bold">{{ \App\Models\User::whereHas('role', fn($q) => $q->where('name', 'Karyawan'))->count() }}</div>
                <flux:text>Active employees</flux:text>
            </div>
        </flux:card>

        <flux:card>
            <div class="space-y-2">
                <flux:heading size="lg">Pending Tasks</flux:heading>
                <div class="text-3xl font-bold">0</div>
                <flux:text>Tasks to review</flux:text>
            </div>
        </flux:card>

        <flux:card>
            <div class="space-y-2">
                <flux:heading size="lg">Welcome</flux:heading>
                <flux:text>{{ auth()->user()->name }}</flux:text>
                <flux:text>You are logged in as HRD</flux:text>
            </div>
        </flux:card>
    </div>

    <flux:card>
        <flux:heading size="lg" class="mb-4">HRD Actions</flux:heading>
        <div class="flex gap-4 flex-wrap">
            <flux:button variant="primary">Manage Employees</flux:button>
            <flux:button variant="primary">Attendance</flux:button>
            <flux:button>Leave Requests</flux:button>
            <flux:button>Performance</flux:button>
        </div>
    </flux:card>
</div>
