<div class="space-y-6">
    <flux:heading size="xl">Dashboard Karyawan</flux:heading>

    <flux:separator />

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <flux:card>
            <div class="space-y-2">
                <flux:heading size="lg">My Tasks</flux:heading>
                <div class="text-3xl font-bold">0</div>
                <flux:text>Assigned tasks</flux:text>
            </div>
        </flux:card>

        <flux:card>
            <div class="space-y-2">
                <flux:heading size="lg">Attendance</flux:heading>
                <div class="text-3xl font-bold">Present</div>
                <flux:text>Today's status</flux:text>
            </div>
        </flux:card>

        <flux:card>
            <div class="space-y-2">
                <flux:heading size="lg">Welcome</flux:heading>
                <flux:text>{{ auth()->user()->name }}</flux:text>
                <flux:text>You are logged in as Karyawan</flux:text>
            </div>
        </flux:card>
    </div>

    <flux:card>
        <flux:heading size="lg" class="mb-4">Quick Actions</flux:heading>
        <div class="flex gap-4 flex-wrap">
            <flux:button variant="primary">My Profile</flux:button>
            <flux:button variant="primary">Time Sheet</flux:button>
            <flux:button>Leave Request</flux:button>
            <flux:button>Payslips</flux:button>
        </div>
    </flux:card>
</div>
