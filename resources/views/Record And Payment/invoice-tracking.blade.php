@extends('layouts.maintenance')

@section('content')
<div class="container-fluid mt-4">

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 text-center p-3">
                <h6 class="text-muted">Total Invoices</h6>
                <h3 class="fw-bold text-dark">4</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 text-center p-3">
                <h6 class="text-muted">Paid</h6>
                <h3 class="fw-bold text-success">1</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 text-center p-3">
                <h6 class="text-muted">Pending</h6>
                <h3 class="fw-bold text-warning">2</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 text-center p-3">
                <h6 class="text-muted">Overdue</h6>
                <h3 class="fw-bold text-danger">1</h3>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow-lg border-0 rounded-4 w-100">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h4 class="mb-0"><i class="bi bi-receipt-cutoff me-2"></i> Invoice Tracking</h4>
        </div>

        <div class="card-body p-4">
            <!-- Search and Filter -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex gap-2">
                    <select class="form-select w-auto">
                        <option value="all">All Status</option>
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                        <option value="overdue">Overdue</option>
                    </select>
                    <select class="form-select w-auto">
                        <option value="all">All Methods</option>
                        <option value="gcash">GCash</option>
                        <option value="bank">Bank Transfer</option>
                        <option value="card">Credit Card</option>
                    </select>
                </div>
                <div class="input-group w-25">
                    <input type="text" class="form-control" placeholder="Search invoice...">
                    <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                </div>
            </div>

            <!-- Invoice Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Invoice No.</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date Issued</th>
                            <th>Due Date</th>
                            <th>Method</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dummy Data -->
                        <tr>
                            <td>1</td>
                            <td>INV-3001</td>
                            <td>Juan Dela Cruz</td>
                            <td>₱5,000.00</td>
                            <td><span class="badge bg-success">Paid</span></td>
                            <td>2025-09-01</td>
                            <td>2025-09-10</td>
                            <td>GCash</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>INV-3002</td>
                            <td>Maria Santos</td>
                            <td>₱3,200.00</td>
                            <td><span class="badge bg-warning text-dark">Pending</span></td>
                            <td>2025-09-05</td>
                            <td>2025-09-20</td>
                            <td>Bank Transfer</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>INV-3003</td>
                            <td>Carlos Reyes</td>
                            <td>₱7,800.00</td>
                            <td><span class="badge bg-danger">Overdue</span></td>
                            <td>2025-08-15</td>
                            <td>2025-08-30</td>
                            <td>gcash</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</button>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>INV-3004</td>
                            <td>Ana Cruz</td>
                            <td>₱6,500.00</td>
                            <td><span class="badge bg-warning text-dark">Pending</span></td>
                            <td>2025-09-12</td>
                            <td>2025-09-28</td>
                            <td>Bank Transfer</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between mt-3">
                <small class="text-muted">Showing 1 to 4 of 120 invoices</small>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

</div>
@endsection
