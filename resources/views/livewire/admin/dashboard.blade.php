<div class="space-y-6">
    <flux:heading size="xl">Dashboard Admin</flux:heading>

    <flux:separator />

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <flux:card>
            <div class="space-y-2">
                <flux:heading size="lg">Total Users</flux:heading>
                <div class="text-3xl font-bold">{{ \App\Models\User::count() }}</div>
                <flux:text>Registered users in the system</flux:text>
            </div>
        </flux:card>

        <flux:card>
            <div class="space-y-2">
                <flux:heading size="lg">Total Roles</flux:heading>
                <div class="text-3xl font-bold">{{ \App\Models\Role::count() }}</div>
                <flux:text>Available roles</flux:text>
            </div>
        </flux:card>

        <flux:card>
            <div class="space-y-2">
                <flux:heading size="lg">Welcome</flux:heading>
                <flux:text>{{ auth()->user()->name }}</flux:text>
                <flux:text>You are logged in as Admin</flux:text>
            </div>
        </flux:card>
    </div>

    <flux:card>
        <flux:heading size="lg" class="mb-4">Quick Actions</flux:heading>
        <div class="flex gap-4 flex-wrap">
            <flux:button variant="primary">Manage Users</flux:button>
            <flux:button variant="primary">Manage Roles</flux:button>
            <flux:button>View Reports</flux:button>
            <flux:button>System Settings</flux:button>
        </div>
    </flux:card>
</div>
