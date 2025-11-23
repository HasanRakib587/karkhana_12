<div class="container py-5 px-4">
    <div class="d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <main class="w-100" style="max-width: 420px;">
            <div class="card shadow border-0 rounded-4 mt-4">
                <div class="card-body p-4 p-sm-5">
                    <div class="text-center mb-4">
                        <h1 class="h3 fw-bold text-dark mb-2">Forgot password?</h1>
                        <p class="text-muted small">
                            Remember your password?
                            <a wire:navigate href="{{ route('login') }}"
                                class="text-primary fw-semibold text-decoration-underline">
                                Sign in here
                            </a>
                        </p>
                    </div>

                    <!-- Form -->
                    <form wire:submit.prevent="save">
                        @if (session('success'))
                            <div class="alert alert-success mb-4" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label small fw-semibold">Email address</label>
                            <input type="email" id="email" wire:model="email" class="form-control form-control-lg"
                                aria-describedby="email-error">
                            @error('email')
                                <div id="email-error" class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm">
                            Reset password
                        </button>
                    </form>
                    <!-- End Form -->
                </div>
            </div>
        </main>
    </div>
</div>