@extends('admin.layouts.app')
@section('title', 'Diamond Clarities')
@section('content')
    @include('components.page-header', [
        'title' => '',
        'btnLink' => 'javascript:;',
        'btnFunClick' => 'addDiamondClarity()' ?? '',
        'btnText' => 'Add',
        'breadcrumbs' => [['name' => 'Diamond Clarities']],
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
                    <table id="styleTable" class="table table-bordered table-stripped">
                        <thead>
                            <tr>
                                <th>Sl.No</th>
                                <th>Clarity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clarities as $clarity)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $clarity->clarity }}</td>
                                    <td>
                                        <a href="javascript:;" onclick="editDiamondClarity('{{ $clarity->id }}')"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="ti ti-pencil me-0"></i>
                                        </a>

                                        <form action="{{ route('admin.diamond-clarity-delete', $clarity->id) }}" method="POST"
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
    <div class="modal fade" id="diamondClarityModal" tabindex="-1" aria-labelledby="diamondClarityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="diamondClarityModalLabel">Diamond Clarity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="diamondClarityModalBody"></div>
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

        function addDiamondClarity() {
            let data = `
                <form action="{{ route('admin.diamond-clarity-store') }}" method="POST">
                    @csrf
                    <div class="row mb-2">
                        <label class="col-md-3 my-2 d-flex justify-content-end">Clarity</label>
                        <div class="form-group col-md-8">
                            <input type="text" class="form-control" name="clarity" required placeholder="e.g. VVS1, SI2"
                                   value="{{ old('clarity') }}">
                        </div>
                    </div>
                    <div class="d-flex justify-content-center my-4 gap-3">
                        <button class="btn btn-secondary" type="submit">Submit</button>
                        <button type="reset" class="btn btn-light px-4">Reset</button>
                    </div>
                </form>`;

            document.getElementById('diamondClarityModalBody').innerHTML = data;

            const modal = new bootstrap.Modal(document.getElementById('diamondClarityModal'));
            modal.show();
        }

        function editDiamondClarity(id) {
            fetch(`/admin/diamond-clarity-edit/${id}`)
                .then(response => response.json())
                .then(responseData => {
                    if (!responseData.status || !responseData.data) {
                        throw new Error("Invalid response.");
                    }

                    const clarity = responseData.data;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    let htmlData = `
                        <form action="/admin/diamond-clarity-update/${id}" method="POST">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <div class="row mb-2">
                                <label class="col-md-3 my-2 d-flex justify-content-end">Clarity</label>
                                <div class="form-group col-md-8">
                                    <input type="text" class="form-control" name="clarity" required value="${clarity.clarity}">
                                </div>
                            </div>
                            <div class="d-flex justify-content-center my-4 gap-3">
                                <button class="btn btn-secondary" type="submit">Update</button>
                            </div>
                        </form>
                    `;

                    document.getElementById('diamondClarityModalBody').innerHTML = htmlData;

                    const modal = new bootstrap.Modal(document.getElementById('diamondClarityModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('diamondClarityModalBody').innerHTML = 'Error loading clarity.';
                });
        }
    </script>
@endpush
