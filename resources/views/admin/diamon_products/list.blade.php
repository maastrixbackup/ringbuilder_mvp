@extends('admin.layouts.app')
@section('title', 'Diamonds')
@section('content')
    <!-- Breadcrumb Start -->
    @include('components.page-header', [
        'title' => 'Diamonds',
        'btnLink' => route('admin.diamonds.create'),
        'btnText' => 'Add',
        'btnFunClick' => '',
        'breadcrumbs' => [
            ['name' => 'Dimaonds'], // No URL = current page
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
                {{-- <div class="card-header py-1"><h4>Rings</h4></div> --}}
                <div class="card-body align-items-center">
                    <div class="table-responsive">
                        <table id="ringsTable" class="table table-bordered  table-stripped">
                            <thead>
                                <tr>
                                    <th>Sl.No</th>
                                    <th>Title</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($diamonds as $diamond)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-capitalize">{{ $diamond->title }}</td>
                                        <td class="text-capitalize">{{ $diamond->sku }}</td>
                                        <td class="text-capitalize"><b>${{ $diamond->price }}</b></td>
                                        <td>
                                            @isset($diamond->img_one)
                                                <img src="{{ asset('storage/images/diamonds/' . $diamond->img_one) }}"
                                                    alt="Image Preview" width="60" class="mt-2 rounded">
                                            @endisset
                                        </td>
                                        <td>
                                            {{-- <a href="{{ route('admin.diamonds.edit', $diamond->id) }}"
                                                class="btn btn-sm mt-1 btn-outline-primary" title="Clone">
                                                <i class="ti ti-files me-0"></i>
                                            </a> --}}
                                            <a href="{{ route('admin.diamonds.edit', $diamond->id) }}"
                                                class="btn btn-sm mt-1 btn-outline-primary" title="Edit">
                                                <i class="ti ti-pencil me-0"></i>
                                            </a>

                                            <form action="{{ route('admin.diamonds.destroy', $diamond->id) }}"
                                                method="POST" style="display:inline;"
                                                onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm mt-1 btn-outline-danger" title="Delete">
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
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#ringsTable').DataTable({
                responsive: true,
                autoWidth: false
            });
        });
    </script>
@endpush
