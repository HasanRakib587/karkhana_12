<div class="mt-4 alert alert-warning">
    <div class="card shadow-sm border-2">
        <div class="card-body">
            <h5 class="fw-bold text-danger mb-3">Pay with bKash</h5>

            <div class="row align-items-center">
                <!-- Left: Payment Instructions -->
                <div class="col-md-7 mb-3 mb-md-0">
                    <p class="mb-2">Send money to the following bKash number:</p>

                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-danger me-2">bKash</span>
                        <span class="fs-5 fw-bold"> 01XXXXXXXXX </span>
                    </div>

                    <ul class="small text-muted ps-3 mb-0">
                        <li>Select <strong>Send Money</strong></li>
                        <li>Enter the bKash number above</li>
                        <li>Enter the exact amount</li>
                        <li>Complete the payment</li>
                    </ul>
                </div>

                <!-- Right: QR Code -->
                <div class="col-md-5 text-center">
                    <p class="mb-2 fw-semibold">Scan to Pay</p>

                    <div class="border rounded p-2 d-inline-block">
                        <img src="{{ asset('images/qr-code.png') }}" alt="bKash QR Code" width="160" />
                    </div>

                    <div class="small text-muted mt-2">Open bKash app → Scan QR</div>
                </div>
            </div>

            <hr class="my-4" />

            <!-- Confirmation Input -->
            <form>
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label">
                            Last 3 digits of your bKash number
                        </label>
                        <input type="text" class="form-control" maxlength="3" placeholder="e.g. 123" required />
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"> Transaction ID (optional) </label>
                        <input type="text" class="form-control" placeholder="e.g. A7B9C2D" />
                    </div>
                </div>

                <button class="btn btn-danger mt-3">I Have Paid</button>
                <p class="small text-muted mt-2">
                    Payment verification may take up to 1–2 hours.
                </p>
            </form>
        </div>
    </div>
</div>