@extends('admin.layouts.app')
@section('title', 'Ring Styles')
@section('content')
    <!-- Breadcrumb Start -->
    @include('components.page-header', [
        'title' => 'Ring Style',
        'btnLink' => route('admin.ring-style.create'),
        'btnText' => 'Add',
        'breadcrumbs' => [
            ['name' => 'Ring Style'], // No URL = current page
        ],
    ])
    <!-- Breadcrumb End -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table id="styleTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.No</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#styleTable').DataTable({
                responsive: true,
                autoWidth: false
            });
        });
    </script>
@endpush
