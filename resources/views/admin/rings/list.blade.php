@extends('admin.layouts.app')
@section('title', 'Rings')
@section('content')
    <!-- Breadcrumb Start -->
    @include('components.page-header', [
        'title' => 'Rings',
        'btnLink' => route('admin.rings.create'),
        'btnText' => 'Add',
        'btnFunClick' => '',
        'breadcrumbs' => [
            ['name' => 'Rings'], // No URL = current page
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
                                    <th> Style</th>
                                    <th> Size</th>
                                    <th> Karat</th>
                                    <th> Color</th>
                                    <th>Price</th>
                                    <th class="d-none">Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rings as $ring)
                                    @php
                                        $color = \App\Models\RingColor::find($ring->ring_color);
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-capitalize">{{ $ring->title }}</td>
                                        <td class="text-capitalize">{{ $ring->sku }}</td>
                                        <td class="text-capitalize">{{ $ring->ring_style }}</td>
                                        <td>{{ $ring->ring_size }}cm</td>
                                        <td class="text-capitalize">{{ $ring->ring_karat }}K</td>
                                        <td class="text-capitalize">{{ $color->color_name ?? 'N/A' }}</td>
                                        <td class="text-capitalize"><b>${{ $ring->ring_price }}</b></td>
                                        <td class="d-none">
                                            @isset($ring->ring_image)
                                                <img src="{{ asset('storage/images/ring_styles/' . $ring->ring_image) }}"
                                                    alt="Image Preview" width="60" class="mt-2 rounded">
                                            @endisset
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.rings.edit', $ring->id) }}"
                                                class="btn btn-sm mt-1 btn-outline-primary" title="Edit">
                                                <i class="ti ti-pencil me-0"></i>
                                            </a>

                                            <form action="{{ route('admin.rings.destroy', $ring->id) }}" method="POST"
                                                style="display:inline;" onsubmit="return confirm('Are you sure?');">
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
