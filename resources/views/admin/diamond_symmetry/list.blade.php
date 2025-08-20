@extends('admin.layouts.app')
@section('title', 'Diamond Symmetries')
@section('content')
    @include('components.page-header', [
        'title' => '',
        'btnLink' => 'javascript:;',
        'btnFunClick' => 'addDiamondSymmetry()' ?? '',
        'btnText' => 'Add',
        'breadcrumbs' => [['name' => 'Diamond Symmetries']],
    ])

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
                    <table id="diamondSymmetryTable" class="table table-bordered table-stripped">
                        <thead>
                            <tr>
                                <th>Sl.No</th>
                                <th>Symmetry</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($symmetries as $symmetry)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $symmetry->symmetry }}</td>
                                    <td>
                                        <a href="javascript:;" onclick="editDiamondSymmetry('{{ $symmetry->id }}')"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="ti ti-pencil me-0"></i>
                                        </a>

                                        <form action="{{ route('admin.delete-d-symmetry', $symmetry->id) }}" method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Are you sure to delete this?');">
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

    {{-- Modal --}}
    <div class="modal fade" id="diamondSymmetryModal" tabindex="-1" aria-labelledby="diamondSymmetryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="diamondSymmetryModalLabel">Diamond Symmetry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="diamondSymmetryModalBody"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#diamondSymmetryTable').DataTable({
                responsive: true,
                autoWidth: false
            });
        });

        function addDiamondSymmetry() {
            let data = `
                <form action="{{ route('admin.store-d-symmetry') }}" method="POST">
                    @csrf
                    <div class="row mb-2">
                        <label class="col-md-3 my-2 d-flex justify-content-end">Symmetry</label>
                        <div class="form-group col-md-8">
                            <input type="text" class="form-control" name="symmetry" required placeholder="e.g. Excellent, Very Good"
                                   value="{{ old('symmetry') }}">
                        </div>
                    </div>
                    <div class="d-flex justify-content-center my-4 gap-3">
                        <button class="btn btn-secondary" type="submit">Submit</button>
                        <button type="reset" class="btn btn-light px-4">Reset</button>
                    </div>
                </form>`;

            document.getElementById('diamondSymmetryModalBody').innerHTML = data;

            const modal = new bootstrap.Modal(document.getElementById('diamondSymmetryModal'));
            modal.show();
        }

        function editDiamondSymmetry(id) {
            fetch(`/admin/edit-symmetry/${id}`)
                .then(response => response.json())
                .then(responseData => {
                    if (!responseData.status || !responseData.data) {
                        throw new Error("Invalid response.");
                    }

                    const symmetry = responseData.data;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    let htmlData = `
                        <form action="/admin/update-symmetry/${id}" method="POST">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <div class="row mb-2">
                                <label class="col-md-3 my-2 d-flex justify-content-end">Symmetry</label>
                                <div class="form-group col-md-8">
                                    <input type="text" class="form-control" name="symmetry" required value="${symmetry.symmetry}">
                                </div>
                            </div>
                            <div class="d-flex justify-content-center my-4 gap-3">
                                <button class="btn btn-secondary" type="submit">Update</button>
                            </div>
                        </form>
                    `;

                    document.getElementById('diamondSymmetryModalBody').innerHTML = htmlData;

                    const modal = new bootstrap.Modal(document.getElementById('diamondSymmetryModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('diamondSymmetryModalBody').innerHTML = 'Error loading symmetry.';
                });
        }
    </script>
@endpush
