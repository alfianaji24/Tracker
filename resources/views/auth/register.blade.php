<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label for="name" class="form-label">Full Name</label>
            <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Enter your full name">
            <x-input-error :messages="$errors->get('name')" style="color: var(--danger); font-size: 12px; margin-top: 4px; list-style: none; padding: 0;" />
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Enter your email">
            <x-input-error :messages="$errors->get('email')" style="color: var(--danger); font-size: 12px; margin-top: 4px; list-style: none; padding: 0;" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" placeholder="Create a password">
            <x-input-error :messages="$errors->get('password')" style="color: var(--danger); font-size: 12px; margin-top: 4px; list-style: none; padding: 0;" />
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password">
            <x-input-error :messages="$errors->get('password_confirmation')" style="color: var(--danger); font-size: 12px; margin-top: 4px; list-style: none; padding: 0;" />
        </div>

        <button class="btn btn-primary" style="width: 100%; justify-content: center; padding: 12px; font-size: 16px; margin-top: 8px;">
            Sign Up
        </button>

        <div style="text-align: center; margin-top: 24px; font-size: 14px; color: var(--text-muted);">
            Already have an account? 
            <a href="{{ route('login') }}" style="color: var(--primary); text-decoration: none; font-weight: 500;">Sign In</a>
        </div>
    </form>
</x-guest-layout>
