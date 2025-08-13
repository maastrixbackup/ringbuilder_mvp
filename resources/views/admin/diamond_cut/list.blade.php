@extends('admin.layouts.app')
@section('title', 'Diamond Cuts')
@section('content')
    @include('components.page-header', [
        'title' => '',
        'btnLink' => 'javascript:;',
        'btnFunClick' => 'addDiamondCut()' ?? '',
        'btnText' => 'Add',
        'breadcrumbs' => [['name' => 'Diamond Cuts']],
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
                    <table id="diamondCutTable" class="table table-bordered table-stripped">
                        <thead>
                            <tr>
                                <th>Sl.No</th>
                                <th>Cuts</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cuts as $cut)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $cut->cut }}</td>
                                    <td>
                                        <a href="javascript:;" onclick="editDiamondCut('{{ $cut->id }}')"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="ti ti-pencil me-0"></i>
                                        </a>

                                        <form action="{{ route('admin.delete-d-cut', $cut->id) }}" method="POST"
                                            style="display:inline;" onsubmit="return confirm('Are you sure to delete this?');">
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
    <div class="modal fade" id="diamondCutsModal" tabindex="-1" aria-labelledby="diamondCutsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="diamondCutsModalLabel">Diamond Cut</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="diamondCutsModalBody"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#diamondCutTable').DataTable({
                responsive: true,
                autoWidth: false
            });
        });

        function addDiamondCut() {
            let data = `
                <form action="{{ route('admin.store-d-cut') }}" method="POST">
                    @csrf
                    <div class="row mb-2">
                        <label class="col-md-3 my-2 d-flex justify-content-end">Cut</label>
                        <div class="form-group col-md-8">
                            <input type="text" class="form-control" name="cut" required placeholder="e.g. Good, Very Good"
                                   value="{{ old('cut') }}">
                        </div>
                    </div>
                    <div class="d-flex justify-content-center my-4 gap-3">
                        <button class="btn btn-secondary" type="submit">Submit</button>
                        <button type="reset" class="btn btn-light px-4">Reset</button>
                    </div>
                </form>`;

            document.getElementById('diamondCutsModalBody').innerHTML = data;

            const modal = new bootstrap.Modal(document.getElementById('diamondCutsModal'));
            modal.show();
        }

        function editDiamondCut(id) {
            fetch(`/admin/edit-cut/${id}`)
                .then(response => response.json())
                .then(responseData => {
                    if (!responseData.status || !responseData.data) {
                        throw new Error("Invalid response.");
                    }

                    const cut = responseData.data;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    let htmlData = `
                        <form action="/admin/update-cut/${id}" method="POST">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <div class="row mb-2">
                                <label class="col-md-3 my-2 d-flex justify-content-end">Cut</label>
                                <div class="form-group col-md-8">
                                    <input type="text" class="form-control" name="cut" required value="${cut.cut}">
                                </div>
                            </div>
                            <div class="d-flex justify-content-center my-4 gap-3">
                                <button class="btn btn-secondary" type="submit">Update</button>
                            </div>
                        </form>
                    `;

                    document.getElementById('diamondCutsModalBody').innerHTML = htmlData;

                    const modal = new bootstrap.Modal(document.getElementById('diamondCutsModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('diamondCutsModalBody').innerHTML = 'Error loading cut.';
                });
        }
    </script>
@endpush
