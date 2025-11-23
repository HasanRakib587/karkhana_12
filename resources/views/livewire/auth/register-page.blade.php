<div class="container py-5 px-4">
    <div class="d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <main class="w-100" style="max-width: 420px;">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-4 p-sm-5">
                    <div class="text-center mb-4">
                        <h1 class="h3 fw-bold text-dark mb-2">Sign up</h1>
                        <p class="text-muted small">
                            Already have an account?
                            <a wire:navigate href="{{ route('login') }}"
                                class="text-primary fw-semibold text-decoration-underline">
                                Sign in here
                            </a>
                        </p>
                    </div>

                    <hr class="my-4">

                    <!-- Start Form -->
                    <form wire:submit.prevent="save">
                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label small fw-semibold">Name</label>
                            <input type="text" id="name" wire:model="name" class="form-control form-control-lg"
                                aria-describedby="name-error">
                            @error('name')
                                <div id="name-error" class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

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

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label small fw-semibold">Password</label>
                            <input type="password" id="password" wire:model="password"
                                class="form-control form-control-lg" aria-describedby="password-error">
                            @error('password')
                                <div id="password-error" class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm">
                            Sign up
                        </button>
                    </form>
                    <!-- End Form -->
                </div>
            </div>
        </main>
    </div>
</div>