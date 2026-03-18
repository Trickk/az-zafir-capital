<x-layouts::auth :title="__('Log in')">
    <div class="w-full max-w-md mx-auto">
        <div class="az-card p-8 md:p-10">
            <div class="flex flex-col items-center text-center mb-8">
                <img
                    src="{{ asset('images/al-zafir-logo.png') }}"
                    alt="Al-Zafir Capital"
                    class="h-20 w-auto az-logo-glow mb-5"
                >

                <p class="az-eyebrow mb-3">Private Access</p>

                <h1 class="az-title text-4xl font-bold leading-tight">
                    {{ __('Log in to your account') }}
                </h1>

                <p class="mt-3 az-muted text-sm leading-6 max-w-sm">
                    {{ __('Enter your email and password below to access the private operations panel.') }}
                </p>
            </div>

            <x-auth-session-status class="mb-6 text-center az-muted" :status="session('status')" />

            <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
                @csrf

                <flux:input
                    name="email"
                    :label="__('Email address')"
                    :value="old('email')"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="email@example.com"
                />

                <div class="relative">
                    <flux:input
                        name="password"
                        :label="__('Password')"
                        type="password"
                        required
                        autocomplete="current-password"
                        :placeholder="__('Password')"
                        viewable
                    />

                    @if (Route::has('password.request'))
                        <flux:link class="absolute top-0 text-sm end-0 !text-[var(--az-muted)] hover:!text-[var(--az-gold)]" :href="route('password.request')" wire:navigate>
                            {{ __('Forgot your password?') }}
                        </flux:link>
                    @endif
                </div>

                <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

                <div class="flex items-center justify-end">
                    <flux:button
                        variant="primary"
                        type="submit"
                        class="w-full !bg-[var(--az-gold)] !text-black hover:!bg-[var(--az-gold-light)] !border-0 !rounded-[14px] !font-semibold !py-3"
                        data-test="login-button"
                    >
                        {{ __('Log in') }}
                    </flux:button>
                </div>
            </form>

            @if (Route::has('register'))
                <div class="mt-8 space-x-1 text-sm text-center az-muted rtl:space-x-reverse">
                    <span>{{ __('Don\'t have an account?') }}</span>
                    <flux:link :href="route('register')" wire:navigate class="!text-[var(--az-gold)] hover:!text-[var(--az-gold-light)]">
                        {{ __('Sign up') }}
                    </flux:link>
                </div>
            @endif
        </div>
    </div>
</x-layouts::auth>
