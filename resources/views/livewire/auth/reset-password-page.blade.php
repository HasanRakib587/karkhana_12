<div class="container py-5 px-4">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <main class="w-100" style="max-width: 400px;">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h1 class="h4 fw-bold">Reset password</h1>
                    </div>

                    <!-- Form -->
                    <form wire:submit.prevent="save">
                        @if (session('error'))
                            <div class="alert alert-danger mb-4" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="password" wire:model="password"
                                    aria-describedby="password-error">
                                <div class="position-absolute top-50 end-0 translate-middle-y pe-3 d-none"
                                    id="password-icon">
                                    <svg class="text-danger" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                                    </svg>
                                </div>
                            </div>
                            @error('password')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="password_confirmation"
                                    wire:model="password_confirmation" aria-describedby="password_confirmation-error">
                                <div class="position-absolute top-50 end-0 translate-middle-y pe-3 d-none"
                                    id="password_confirmation-icon">
                                    <svg class="text-danger" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                                    </svg>
                                </div>
                            </div>
                            @error('password_confirmation')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            Save password
                        </button>
                    </form>
                    <!-- End Form -->
                </div>
            </div>
        </main>
    </div>
</div>