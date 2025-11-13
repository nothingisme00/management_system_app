<div class="space-y-6">
    <flux:heading size="xl">Dashboard Manager</flux:heading>

    <flux:separator />

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <flux:card>
            <div class="space-y-2">
                <flux:heading size="lg">Team Members</flux:heading>
                <div class="text-3xl font-bold">0</div>
                <flux:text>Members in your team</flux:text>
            </div>
        </flux:card>

        <flux:card>
            <div class="space-y-2">
                <flux:heading size="lg">Active Projects</flux:heading>
                <div class="text-3xl font-bold">0</div>
                <flux:text>Ongoing projects</flux:text>
            </div>
        </flux:card>

        <flux:card>
            <div class="space-y-2">
                <flux:heading size="lg">Welcome</flux:heading>
                <flux:text>{{ auth()->user()->name }}</flux:text>
                <flux:text>You are logged in as Manager</flux:text>
            </div>
        </flux:card>
    </div>

    <flux:card>
        <flux:heading size="lg" class="mb-4">Manager Actions</flux:heading>
        <div class="flex gap-4 flex-wrap">
            <flux:button variant="primary">Team Management</flux:button>
            <flux:button variant="primary">Project Tracking</flux:button>
            <flux:button>Approvals</flux:button>
            <flux:button>Reports</flux:button>
        </div>
    </flux:card>
</div>
