@extends('admin.layouts.app')
@section('title', 'Ring Widths')
@section('content')
    @include('components.page-header', [
        'title' => '',
        'btnLink' => 'javascript:;',
        'btnFunClick' => 'addRingWidth()' ?? '',
        'btnText' => 'Add',
        'breadcrumbs' => [['name' => 'Ring Widths']],
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
                                <th>Width</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ringWidths as $k)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $k->width }}</td>
                                    <td>
                                        <a href="javascript:;" onclick="editRingWidth('{{ $k->id }}')"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="ti ti-pencil me-0"></i>
                                        </a>

                                        <form action="{{ route('admin.ring-width-delete', $k->id) }}" method="POST"
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
    <div class="modal fade" id="ringWidthsModal" tabindex="-1" aria-labelledby="ringWidthsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ringWidthsModalLabel">Ring Width</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="ringWidthsModalBody"></div>
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

        function addRingWidth() {
            let data = `
                <form action="{{ route('admin.ring-width-store') }}" method="POST">
                    @csrf
                    <div class="row mb-2">
                        <label class="col-md-3 my-2 d-flex justify-content-end">Width</label>
                        <div class="form-group col-md-8">
                            <input type="text" class="form-control" name="width" required placeholder="e.g. 1.00-2.00mm"
                                   value="{{ old('width') }}">
                        </div>
                    </div>
                    <div class="d-flex justify-content-center my-4 gap-3">
                        <button class="btn btn-secondary" type="submit">Submit</button>
                        <button type="reset" class="btn btn-light px-4">Reset</button>
                    </div>
                </form>`;

            document.getElementById('ringWidthsModalBody').innerHTML = data;

            const modal = new bootstrap.Modal(document.getElementById('ringWidthsModal'));
            modal.show();
        }

        function editRingWidth(id) {
            fetch(`/admin/ring-width-edit/${id}`)
                .then(response => response.json())
                .then(responseData => {
                    if (!responseData.status || !responseData.data) {
                        throw new Error("Invalid response.");
                    }

                    const width = responseData.data;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    let htmlData = `
                        <form action="/admin/ring-width-update/${id}" method="POST">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <div class="row mb-2">
                                <label class="col-md-3 my-2 d-flex justify-content-end">Width Range</label>
                                <div class="form-group col-md-8">
                                    <input type="text" class="form-control" name="width" required value="${width.width}">
                                </div>
                            </div>
                            <div class="d-flex justify-content-center my-4 gap-3">
                                <button class="btn btn-secondary" type="submit">Update</button>
                            </div>
                        </form>
                    `;

                    document.getElementById('ringWidthsModalBody').innerHTML = htmlData;

                    const modal = new bootstrap.Modal(document.getElementById('ringWidthsModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('ringWidthsModalBody').innerHTML = 'Error loading width.';
                });
        }
    </script>
@endpush
