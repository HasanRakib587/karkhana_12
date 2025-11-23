<div class="container py-5 px-4 mt-5">
    <div class="d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <main class="w-100" style="max-width: 480px;">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-4 p-sm-5">
                    <div class="text-center mb-4">
                        <h1 class="font-primary h3 fw-bold text-dark mb-2">Sign in</h1>
                        <p class="font-secondary text-muted small">
                            Don't have an account yet?
                            <a wire:navigate href="{{ route('user.register') }}"
                                class="text-primary font-primary fw-semibold text-decoration-underline">
                                Sign up here
                            </a>
                        </p>
                    </div>

                    <hr class="my-4">

                    <!-- Form -->
                    <form wire:submit.prevent="save">

                        @if (session('error'))
                            <div class="alert alert-danger mb-4" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="mb-4">
                            <label for="email" class="font-secondary form-label small fw-semibold">Email address</label>
                            <input type="email" id="email" wire:model="email" class="form-control form-control-lg"
                                aria-describedby="email-error">
                            @error('email')
                                <div id="email-error" class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="password"
                                    class="font-secondary form-label small fw-semibold mb-0">Password</label>
                                <a wire:navigate href="{{ route('password.request') }}"
                                    class="font-primary small text-primary text-decoration-underline fw-semibold">Forgot
                                    password?</a>
                            </div>
                            <input type="password" id="password" wire:model="password"
                                class="form-control form-control-lg" aria-describedby="password-error">
                            @error('password')
                                <div id="password-error" class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="font-primary btn btn-primary w-100 py-2 fw-semibold shadow-sm">
                            Sign in
                        </button>
                        <p class="divider-text">
                            <span class="bg-light">OR</span>
                        </p>
                        <div class="d-grid gap-2 mb-3">
                            <a href="{{ route('auth.redirection', 'google') }}" class="btn btn-social btn-google">
                                <i class="bi bi-google me-2"></i> Sign in with Google
                            </a>
                            <a href="{{ route('auth.redirection', 'facebook') }}" class="btn btn-social btn-facebook">
                                <i class="bi bi-facebook me-2"></i> Sign in with Facebook
                            </a>
                        </div>
                    </form>
                    <!-- End Form -->
                </div>
            </div>
        </main>
    </div>
</div>