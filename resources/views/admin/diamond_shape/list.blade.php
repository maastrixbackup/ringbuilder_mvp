@extends('admin.layouts.app')
@section('title', 'Diamond Shapes')
@section('content')
    <!-- Breadcrumb Start -->
    @include('components.page-header', [
        'title' => 'Diamond Shape',
        'btnLink' => route('admin.create-shape'),
        'btnText' => 'Add',
        'btnFunClick' => '',
        'breadcrumbs' => [
            ['name' => 'Diamond Shape'], // No URL = current page
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
                    <table id="shapeTable" class="table table-bordered  table-stripped">
                        <thead>
                            <tr>
                                <th>Sl.No</th>
                                <th>Title</th>
                                {{-- <th>Image</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shapes as $shape)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-capitalize">{{ $shape->title }}</td>
                                    {{-- <td>
                                        @isset($shape->style_image)
                                            <img src="{{ asset('storage/images/ring_styles/' . $shape->style_image) }}"
                                                alt="Image Preview" width="60" class="mt-2 rounded">
                                        @endisset
                                    </td> --}}
                                    <td>
                                        <a href="{{ route('admin.ring-style.edit', $shape->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="ti ti-pencil me-0"></i>
                                        </a>

                                        <form action="{{ route('admin.ring-style.destroy', $shape->id) }}" method="POST"
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
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#shapeTable').DataTable({
                responsive: true,
                autoWidth: false
            });
        });
    </script>
@endpush
