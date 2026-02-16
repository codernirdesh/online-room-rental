<x-guest-layout>
    <div class="mb-4 text-center">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-primary-100 dark:bg-primary-900/30">
            <svg class="h-7 w-7 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Verify Your Email</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            We've sent a 6-digit verification code to
        </p>
        <p class="mt-1 text-sm font-semibold text-primary-600 dark:text-primary-400">{{ $email }}</p>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-lg bg-green-50 dark:bg-green-900/20 p-3 text-center text-sm font-medium text-green-600 dark:text-green-400">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf

        <div>
            <x-input-label for="otp" :value="__('Enter OTP Code')" class="text-center" />
            <x-text-input
                id="otp"
                class="block mt-2 w-full text-center text-2xl tracking-[0.5em] font-bold"
                type="text"
                name="otp"
                maxlength="6"
                pattern="[0-9]{6}"
                placeholder="------"
                required
                autofocus
                autocomplete="one-time-code"
                inputmode="numeric"
            />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('Verify Email') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-4 text-center">
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Didn't receive the code?</p>
        <form method="POST" action="{{ route('otp.resend') }}">
            @csrf
            <button type="submit" class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 underline">
                {{ __('Resend OTP') }}
            </button>
        </form>
    </div>

    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 text-center">
        <p class="text-xs text-gray-400 dark:text-gray-500">
            The code expires in 10 minutes.
        </p>
    </div>
</x-guest-layout>
