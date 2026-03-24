@extends('layouts.admin')

@section('sidebar-nav')
    @include('tenant.admin.partials.sidebar')
@endsection

@section('content')
<div class="container-fluid mt-2">
    <h4 class="mb-4">Student Management</h4>
    <div class="card shadow-sm p-4">
        <div class="table-responsive">
            <table id="studentTable" class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>S/N</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $i => $student)
                    <tr>
                        <td>{{ $students->firstItem() + $i }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>
                            @if($student->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($student->status === 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>{{ $student->created_at->format('d M Y') }}</td>
                        <td>
                            @if($student->status !== 'approved')
                            <form method="POST" action="{{ route('tenant.admin.students.approve', ['tenant' => $tenant->subdomain, 'user' => $student]) }}" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-success btn-sm me-1"><i class="bi bi-check-circle"></i> Approve</button>
                            </form>
                            @endif
                            @if($student->status !== 'rejected')
                            <form method="POST" action="{{ route('tenant.admin.students.reject', ['tenant' => $tenant->subdomain, 'user' => $student]) }}" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-danger btn-sm"><i class="bi bi-x-circle"></i> Reject</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#studentTable').DataTable({ destroy: true, pageLength: 10, lengthMenu: [10, 25, 50], ordering: true });
    });
</script>
@endpush
