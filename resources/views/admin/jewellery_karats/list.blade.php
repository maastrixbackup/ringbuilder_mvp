@extends('admin.layouts.app')
@section('title', 'Ring Karats')
@section('content')
    <!-- Breadcrumb Start -->
    @include('components.page-header', [
        'title' => '',
        'btnLink' => 'javascript:;',
        'btnFunClick' => 'addJewelleryKarat()' ?? '',
        'btnText' => 'Add',
        'breadcrumbs' => [
            ['name' => 'Ring Karats'], // No URL = current page
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
                                <th>Karat</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($karats as $k)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-capitalize">{{ $k->karat }}</td>
                                    <td>
                                        <a href="javascript:;"
                                            onclick="EditJewelleryKarat('{{ $k->id }}','{{ $loop->iteration }}')"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="ti ti-pencil me-0"></i>
                                        </a>

                                        <form action="{{ route('admin.jewellery-karat-delete', $k->id) }}" method="POST"
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

    <div class="modal fade" id="karatsModal" tabindex="-1" aria-labelledby="karatsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="karatsModalLabel">Ring Karats</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="karatsModalBody">

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

        function addJewelleryKarat() {
            var data = `<form action="{{ route('admin.jewellery-karat-store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-2">
                            <label for="title" class="col-md-3 my-2 d-flex justify-content-end ">Karat</label>
                            <div class="form-group col-md-8">
                                <input type="number" class="form-control" name="karat" required placeholder=""
                                    value="{{ old('karat') }}">
                            </div>
                        </div>
                        <div class="d-flex justify-content-center my-4  gap-3 ">
                            <button class="btn btn-secondary" type="submit">Submit</button>
                            <button type="reset" class="btn btn-light px-4">Reset</button>
                        </div>
                    </form>`;
            document.getElementById('karatsModalBody').innerHTML = data;
            new bootstrap.Modal(document.getElementById('karatsModal')).show();
        }

        function EditJewelleryKarat(id) {
            const fullUrl = `/admin/jewellery-karat-edit/${id}`;
            fetch(fullUrl)
                .then(response => response.json())
                .then(responseData => {
                    if (!responseData.status || !responseData.data) {
                        throw new Error("Invalid response structure.");
                    }

                    const ringKarat = responseData.data;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const formAction = `/admin/jewellery-karat-update/${id}`;

                    const htmlData = `
                <form action="${formAction}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <div class="row mb-2">
                        <label class="col-md-3 my-2 d-flex justify-content-end">Karat</label>
                        <div class="form-group col-md-8">
                            <input type="number" class="form-control" name="karat" required value="${ringKarat.karat}">
                            <div class="alert alert-sm alert-danger my-2 py-1 d-none" id="auto-alert"></div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center my-4 gap-3">
                        <button class="btn btn-secondary" type="submit">Update</button>
                    </div>
                </form>
            `;

                    document.getElementById('karatsModalBody').innerHTML = htmlData;
                    new bootstrap.Modal(document.getElementById('karatsModal')).show();
                })
                .catch(error => {
                    console.error(error);
                    document.getElementById('karatsModalBody').innerHTML = 'Error loading size.';
                });
        }
    </script>
@endpush
