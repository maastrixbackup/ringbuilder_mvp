@extends('admin.layouts.app')
@section('title', 'Diamond Polishes')
@section('content')
    @include('components.page-header', [
        'title' => '',
        'btnLink' => 'javascript:;',
        'btnFunClick' => 'addDiamondPolish()' ?? '',
        'btnText' => 'Add',
        'breadcrumbs' => [['name' => 'Diamond Polishes']],
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
                    <table id="diamondPolishTable" class="table table-bordered table-stripped">
                        <thead>
                            <tr>
                                <th>Sl.No</th>
                                <th>Polish</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($polishes as $polish)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $polish->polish }}</td>
                                    <td>
                                        <a href="javascript:;" onclick="editDiamondPolish('{{ $polish->id }}')"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="ti ti-pencil me-0"></i>
                                        </a>

                                        <form action="{{ route('admin.delete-d-polish', $polish->id) }}" method="POST"
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
    <div class="modal fade" id="diamondPolishModal" tabindex="-1" aria-labelledby="diamondPolishModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="diamondPolishModalLabel">Diamond Polish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="diamondPolishModalBody"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#diamondPolishTable').DataTable({
                responsive: true,
                autoWidth: false
            });
        });

        function addDiamondPolish() {
            let data = `
                <form action="{{ route('admin.store-d-polish') }}" method="POST">
                    @csrf
                    <div class="row mb-2">
                        <label class="col-md-3 my-2 d-flex justify-content-end">Polish</label>
                        <div class="form-group col-md-8">
                            <input type="text" class="form-control" name="polish" required placeholder="e.g. Good, Very Good"
                                   value="{{ old('polish') }}">
                        </div>
                    </div>
                    <div class="d-flex justify-content-center my-4 gap-3">
                        <button class="btn btn-secondary" type="submit">Submit</button>
                        <button type="reset" class="btn btn-light px-4">Reset</button>
                    </div>
                </form>`;

            document.getElementById('diamondPolishModalBody').innerHTML = data;

            const modal = new bootstrap.Modal(document.getElementById('diamondPolishModal'));
            modal.show();
        }

        function editDiamondPolish(id) {
            fetch(`/admin/edit-polish/${id}`)
                .then(response => response.json())
                .then(responseData => {
                    if (!responseData.status || !responseData.data) {
                        throw new Error("Invalid response.");
                    }

                    const polish = responseData.data;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    let htmlData = `
                        <form action="/admin/update-polish/${id}" method="POST">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <div class="row mb-2">
                                <label class="col-md-3 my-2 d-flex justify-content-end">Polish</label>
                                <div class="form-group col-md-8">
                                    <input type="text" class="form-control" name="polish" required value="${polish.polish}">
                                </div>
                            </div>
                            <div class="d-flex justify-content-center my-4 gap-3">
                                <button class="btn btn-secondary" type="submit">Update</button>
                            </div>
                        </form>
                    `;

                    document.getElementById('diamondPolishModalBody').innerHTML = htmlData;

                    const modal = new bootstrap.Modal(document.getElementById('diamondPolishModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('diamondPolishModalBody').innerHTML = 'Error loading polish.';
                });
        }
    </script>
@endpush
