@extends('layouts.maintenance')

@section('content')
<div class="container-fluid mt-4">

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 text-center p-3">
                <h6 class="text-muted">Total Overdue Reminders</h6>
                <h3 class="fw-bold text-danger">4</h3>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 text-center p-3">
                <h6 class="text-muted">Unpaid Amount</h6>
                <h3 class="fw-bold text-dark">₱52,300.00</h3>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow-lg border-0 rounded-4 w-100">
        <div class="card-header bg-danger text-white rounded-top-4">
            <h4 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i> Overdue Payment Reminders</h4>
        </div>

        <div class="card-body p-4">
            <!-- Search -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="fw-bold text-danger">⚠ All reminders below are overdue payments.</span>
                </div>
                <div class="input-group w-25">
                    <input type="text" class="form-control" placeholder="Search customer...">
                    <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                </div>
            </div>

            <!-- Payment Reminders Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Invoice No.</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Contact</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dummy Overdue Data -->
                        <tr>
                            <td>1</td>
                            <td>INV-2001</td>
                            <td>Juan Dela Cruz</td>
                            <td>₱5,000.00</td>
                            <td>2025-09-10</td>
                            <td><span class="badge bg-danger">Overdue</span></td>
                            <td>juan@example.com</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-envelope"></i> Send Email
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>INV-2002</td>
                            <td>Maria Santos</td>
                            <td>₱3,200.00</td>
                            <td>2025-09-12</td>
                            <td><span class="badge bg-danger">Overdue</span></td>
                            <td>maria@example.com</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-envelope"></i> Send Email
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>INV-2003</td>
                            <td>Carlos Reyes</td>
                            <td>₱7,800.00</td>
                            <td>2025-09-05</td>
                            <td><span class="badge bg-danger">Overdue</span></td>
                            <td>carlos@example.com</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-envelope"></i> Send Email
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>INV-2004</td>
                            <td>Ana Cruz</td>
                            <td>₱6,500.00</td>
                            <td>2025-09-08</td>
                            <td><span class="badge bg-danger">Overdue</span></td>
                            <td>ana@example.com</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-envelope"></i> Send Email
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
@endsection
