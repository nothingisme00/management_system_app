<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="w-full max-w-md px-6">
        <flux:card class="shadow-2xl">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-blue-600 to-purple-600 mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <flux:heading size="xl" class="mb-2">Welcome Back!</flux:heading>
                <flux:text>Sign in to your account to continue</flux:text>
            </div>

            <form wire:submit="login" class="space-y-6">
                {{-- Email Field --}}
                <flux:field>
                    <flux:label>Email Address</flux:label>
                    <flux:input
                        type="email"
                        wire:model="email"
                        placeholder="admin@example.com"
                        icon="envelope"
                    />
                    <flux:error name="email" />
                </flux:field>

                {{-- Password Field --}}
                <flux:field>
                    <flux:label>Password</flux:label>
                    <flux:input
                        type="password"
                        wire:model="password"
                        placeholder="••••••••"
                        icon="lock-closed"
                    />
                    <flux:error name="password" />
                </flux:field>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between">
                    <flux:checkbox wire:model="remember" label="Remember me" />

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            Forgot password?
                        </a>
                    @endif
                </div>

                {{-- Submit Button --}}
                <flux:button
                    type="submit"
                    variant="primary"
                    class="w-full"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>Sign In</span>
                    <span wire:loading>
                        <svg class="animate-spin h-5 w-5 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Signing in...
                    </span>
                </flux:button>

                {{-- Register Link --}}
                @if (Route::has('register'))
                    <div class="text-center">
                        <flux:text>
                            Don't have an account?
                            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                Register here
                            </a>
                        </flux:text>
                    </div>
                @endif
            </form>

            {{-- Demo Credentials Info --}}
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <flux:heading size="sm" class="mb-2 text-blue-900 dark:text-blue-100">Demo Credentials</flux:heading>
                <div class="text-sm space-y-1 text-blue-800 dark:text-blue-200">
                    <div><strong>Email:</strong> admin@example.com</div>
                    <div><strong>Password:</strong> password</div>
                </div>
            </div>
        </flux:card>

        {{-- Footer --}}
        <div class="text-center mt-6">
            <flux:text class="text-gray-600 dark:text-gray-400">
                Management System &copy; {{ date('Y') }}
            </flux:text>
        </div>
    </div>
</div>
