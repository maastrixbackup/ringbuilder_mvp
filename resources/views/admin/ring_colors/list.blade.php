@extends('admin.layouts.app')
@section('title', 'Ring Colors')
@section('content')
    <!-- Breadcrumb Start -->
    @include('components.page-header', [
        'title' => '',
        'btnLink' => 'javascript:;',
        'btnFunClick' => 'addRingColor()' ?? '',
        'btnText' => 'Add',
        'breadcrumbs' => [
            ['name' => 'Ring Colors'], // No URL = current page
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
                    <table id="styleTabl" class="table table-bordered  table-stripped">
                        <thead>
                            <tr>
                                <th>Sl.No</th>
                                <th>Color Name</th>
                                <th>Color Code</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ringColors as $k)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-capitalize">{{ $k->color_name }}</td>
                                    <td class="text-capitalize">{{ $k->color_code }}
                                        <span class="badge" style="background-color: {{ $k->color_code }}; color: #fff;">
                                            {{ $k->color_name }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="javascript:;"
                                            onclick="editRingColor('{{ $k->id }}','{{ $loop->iteration }}')"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="ti ti-pencil me-0"></i>
                                        </a>

                                        <form action="{{ route('admin.ring-color-delete', $k->id) }}" method="POST"
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

    <div class="modal fade" id="ringColorsModal" tabindex="-1" aria-labelledby="ringColorsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ringColorsModalLabel">Ring Color</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="ringColorsModalBody">

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

        function addRingColor() {
            var data = `<form action="{{ route('admin.ring-color-store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-2">
                            <label for="title" class="col-md-3 my-2 d-flex justify-content-end "> Color Name</label>
                            <div class="form-group col-md-8">
                                <input type="text" class="form-control" name="color_name" required placeholder=""
                                    value="{{ old('color_name') }}">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="title" class="col-md-3 my-2 d-flex justify-content-end "> Color Code</label>
                            <div class="form-group col-md-8">
                                <input type="text" class="form-control" name="color_code"  id="color_code" required placeholder=""
                                    value="{{ old('color_code') }}">
                            </div>
                        </div>
                        <div class="d-flex justify-content-center my-4  gap-3 ">
                            <button class="btn btn-secondary" type="submit">Submit</button>
                            <button type="reset" class="btn btn-light px-4">Reset</button>
                        </div>
                    </form>`;

            document.getElementById('ringColorsModalBody').innerHTML = data;

            const modalEl = document.getElementById('ringColorsModal');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();

            modalEl.addEventListener('shown.bs.modal', () => {
                const colorInput = document.getElementById('color_code');

                function ensureHashPrefix() {
                    colorInput.value = '#' + colorInput.value.replace(/#/g, '').trim();
                }

                colorInput.addEventListener('keydown', function(e) {
                    if (
                        (colorInput.selectionStart === 0 && e.key === 'Backspace') ||
                        (colorInput.selectionStart === 0 && e.key.length === 1)
                    ) {
                        e.preventDefault();
                    }
                });

                colorInput.addEventListener('input', ensureHashPrefix);
                colorInput.addEventListener('focus', ensureHashPrefix);

                ensureHashPrefix();
            }, {
                once: true
            });


        }

        function editRingColor(id) {
            const fullUrl = `/admin/ring-color-edit/${id}`;

            fetch(fullUrl)
                .then(response => response.json())
                .then(responseData => {
                    if (!responseData.status || !responseData.data) {
                        throw new Error("Invalid response structure.");
                    }

                    const ringColor = responseData.data;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const formAction = `/admin/ring-color-update/${id}`;

                    const htmlData = `
                <form action="${formAction}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="${csrfToken}">

                    <div class="row mb-2">
                        <label class="col-md-3 my-2 d-flex justify-content-end">Ring Color</label>
                        <div class="form-group col-md-8">
                            <input type="text" class="form-control" name="color_name" required value="${ringColor.color_name}">
                            <div class="alert alert-sm alert-danger my-2 py-1 d-none" id="auto-alert"></div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-md-3 my-2 d-flex justify-content-end">Color Code</label>
                        <div class="form-group col-md-8">
                            <input type="text" class="form-control" name="color_code" id="color_code" required value="${ringColor.color_code}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center my-4 gap-3">
                        <button class="btn btn-secondary" type="submit">Update</button>
                    </div>
                </form>
            `;

                    document.getElementById('ringColorsModalBody').innerHTML = htmlData;

                    const modalEl = document.getElementById('ringColorsModal');
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();

                    modalEl.addEventListener('shown.bs.modal', () => {
                        const colorInput = document.getElementById('color_code');

                        function ensureHashPrefix() {
                            colorInput.value = '#' + colorInput.value.replace(/#/g, '').trim();
                        }

                        colorInput.addEventListener('keydown', function(e) {
                            if (
                                (colorInput.selectionStart === 0 && e.key === 'Backspace') ||
                                (colorInput.selectionStart === 0 && e.key.length === 1)
                            ) {
                                e.preventDefault();
                            }
                        });

                        colorInput.addEventListener('input', ensureHashPrefix);
                        colorInput.addEventListener('focus', ensureHashPrefix);

                        ensureHashPrefix();
                    }, {
                        once: true
                    });

                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('ringColorsModalBody').innerHTML = 'Error loading ring color.';
                });
        }
    </script>
@endpush
