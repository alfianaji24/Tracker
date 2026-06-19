<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" style="color: var(--success); margin-bottom: 16px; font-size: 14px; text-align: center;" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Enter your email">
            <x-input-error :messages="$errors->get('email')" style="color: var(--danger); font-size: 12px; margin-top: 4px; list-style: none; padding: 0;" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
            <x-input-error :messages="$errors->get('password')" style="color: var(--danger); font-size: 12px; margin-top: 4px; list-style: none; padding: 0;" />
        </div>

        <!-- Remember Me -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
            <label for="remember_me" style="display: flex; align-items: center; cursor: pointer;">
                <input id="remember_me" type="checkbox" name="remember" style="margin-right: 8px;">
                <span style="font-size: 14px; color: var(--text-muted);">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size: 14px; color: var(--primary); text-decoration: none; font-weight: 500;">
                    Forgot password?
                </a>
            @endif
        </div>

        <button class="btn btn-primary" style="width: 100%; justify-content: center; padding: 12px; font-size: 16px;">
            Sign In
        </button>

        @if (Route::has('register'))
            <div style="text-align: center; margin-top: 24px; font-size: 14px; color: var(--text-muted);">
                Don't have an account? 
                <a href="{{ route('register') }}" style="color: var(--primary); text-decoration: none; font-weight: 500;">Sign Up</a>
            </div>
        @endif
    </form>
</x-guest-layout>
