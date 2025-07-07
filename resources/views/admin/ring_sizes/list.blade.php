@extends('admin.layouts.app')
@section('title', 'Ring Sizes')
@section('content')
    <!-- Breadcrumb Start -->
    @include('components.page-header', [
        'title' => 'Ring Size',
        'btnLink' => 'javascript:;',
        'btnFunClick' => 'addSize()' ?? '',
        'btnText' => 'Add',
        'breadcrumbs' => [
            ['name' => 'Ring Sizes'], // No URL = current page
        ],
    ])
    <!-- Breadcrumb End -->
    <div class="row">
        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success m-2" id="auto-alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger m-2" id="auto-alert">
                    {{ session('error') }}
                </div>
            @endif
            <div class="card">
                <div class="card-body align-items-center">
                    <table id="styleTable" class="table table-bordered  table-stripped">
                        <thead>
                            <tr>
                                <th>Sl.No</th>
                                <th>Size</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rSizes as $size)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-capitalize">{{ $size->size }}</td>
                                    <td>
                                        <a href="javascript:;"
                                            onclick="EditSize('{{ $size->id }}','{{ $loop->iteration }}')"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="ti ti-pencil me-0"></i>
                                        </a>

                                        <form action="{{ route('admin.ring-size-delete', $size->id) }}" method="POST"
                                            style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="ti ti-trash me-0"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sizesModal" tabindex="-1" aria-labelledby="sizesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sizesModalLabel">Ring Size</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="sizesModalBody">

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

        function addSize() {
            var data = `<form action="{{ route('admin.ring-size-store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-2">
                            <label for="title" class="col-md-3 my-2 d-flex justify-content-end ">Ring Size</label>
                            <div class="form-group col-md-8">
                                <input type="text" class="form-control" name="size" required placeholder=""
                                    value="{{ old('size') }}" autofocus>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center my-4  gap-3 ">
                            <button class="btn btn-secondary" type="submit">Submit</button>
                            <button type="reset" class="btn btn-light px-4">Reset</button>
                        </div>
                    </form>`;

            document.getElementById('sizesModalBody').innerHTML = data;
            new bootstrap.Modal(document.getElementById('sizesModal')).show();
            // Give the browser a tick to render before focusing
            // setTimeout(() => {
            //     document.querySelector('input[name="size"]').focus();
            // }, 300);
        }

        function EditSize(id) {
            const fullUrl = `/admin/ring-size-edit/${id}`;
            fetch(fullUrl)
                .then(response => response.json())
                .then(responseData => {
                    if (!responseData.status || !responseData.data) {
                        throw new Error("Invalid response structure.");
                    }

                    const ringSize = responseData.data;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const formAction = `/admin/ring-size-update/${id}`;

                    const htmlData = `
                <form action="${formAction}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <div class="row mb-2">
                        <label class="col-md-3 my-2 d-flex justify-content-end">Ring Size</label>
                        <div class="form-group col-md-8">
                            <input type="text" class="form-control" name="size" required value="${ringSize.size}">
                            <div class="alert alert-sm alert-danger my-2 py-1 d-none" id="auto-alert"></div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center my-4 gap-3">
                        <button class="btn btn-secondary" type="submit">Update</button>
                    </div>
                </form>
            `;

                    document.getElementById('sizesModalBody').innerHTML = htmlData;
                    new bootstrap.Modal(document.getElementById('sizesModal')).show();
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('sizesModalBody').innerHTML = 'Error loading size.';
                });
        }
    </script>
@endpush
