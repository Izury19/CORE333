@extends('layouts.maintenance')

@section('content')
<div class="container-fluid mt-4">

    <div class="card shadow-lg border-0 rounded-4 w-100">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h4 class="mb-0"><i class="bi bi-clock-history me-2"></i> Payment History</h4>
        </div>

        <div class="card-body p-4">
            <!-- Filter and Search -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <label for="filter" class="me-2 fw-bold">Filter:</label>
                    <select id="filter" class="form-select d-inline-block w-auto">
                        <option value="all">All</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="input-group w-25">
                    <input type="text" class="form-control" placeholder="Search...">
                    <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                </div>
            </div>

            <!-- Payment History Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Invoice No.</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Method</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dummy Data (All Completed) -->
                        <tr>
                            <td>1</td>
                            <td>INV-001</td>
                            <td>Juan Dela Cruz</td>
                            <td>₱5,000.00</td>
                            <td><span class="badge bg-primary">Completed</span></td>
                            <td>2025-09-20</td>
                            <td>GCash</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>INV-002</td>
                            <td>Maria Santos</td>
                            <td>₱3,200.00</td>
                            <td><span class="badge bg-primary">Completed</span></td>
                            <td>2025-09-22</td>
                            <td>Bank Transfer</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>INV-003</td>
                            <td>Carlos Reyes</td>
                            <td>₱7,800.00</td>
                            <td><span class="badge bg-primary">Completed</span></td>
                            <td>2025-09-25</td>
                            <td>Credit Card</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View
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
