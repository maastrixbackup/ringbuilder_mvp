@extends('admin.layouts.app')
@section('title', 'Ring Add')
@section('content')
    @include('components.page-header', [
        'title' => 'Ring Edit',
        'btnLink' => route('admin.rings.index'),
        'btnText' => 'Back',
        'btnFunClick' => '',
        'breadcrumbs' => [
            ['name' => 'Rings'], //  'url' = 'javascript:;'
            ['name' => ' / Ring Edit'], // No URL = current page
        ],
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
                <div class="card-body">
                    <form action="{{ route('admin.rings.update', $ring->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-2">
                            <label for="title" class="col-md-3 my-2 d-flex justify-content-end ">Title*</label>
                            <div class="form-group col-md-8">
                                <input type="text" class="form-control" name="title" id="title" required
                                    placeholder="" value="{{ $ring->title }}" autofocus>
                                @error('title')
                                    <div class="alert alert-sm alert-danger my-2 py-1" id="auto-alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="slug" class="col-md-3 my-2 d-flex justify-content-end ">Slug*</label>
                            <div class="form-group col-md-8">
                                <input type="text" class="form-control" name="slug" id="slug" required readonly
                                    placeholder="Ex:- gold-ring" value="{{ $ring->slug }}">
                                @error('slug')
                                    <div class="alert alert-sm alert-danger my-2 py-1" id="auto-alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="sku" class="col-md-3 my-2 d-flex justify-content-end ">SKU</label>
                            <div class="form-group col-md-8">
                                <input type="text" class="form-control" name="sku" id="sku" required readonly
                                    placeholder="Ex:- gold-ring" value="{{ $ring->sku }}">
                                @error('sku')
                                    <div class="alert alert-sm alert-danger my-2 py-1" id="auto-alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="ring_style" class="col-md-3 my-2 d-flex justify-content-end ">Ring Style*</label>
                            <div class="form-group col-md-8">
                                <select required name="ring_style" id="ring_style" class="form-control">
                                    <option value="" selected disabled>Select Style</option>
                                    @foreach ($style as $s)
                                        <option value="{{ $s->title }}"
                                            {{ $ring->ring_style == $s->title ? 'selected' : '' }}>{{ $s->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ring_style')
                                    <div class="alert alert-sm alert-danger my-2 py-1" id="auto-alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="ring_size" class="col-md-3 my-2 d-flex justify-content-end ">Ring Size*</label>
                            <div class="form-group col-md-8">
                                <select required name="ring_size" id="ring_size" class="form-control">
                                    <option value="" selected disabled>Select Size</option>
                                    @foreach ($size as $sz)
                                        <option
                                            value="{{ $sz->size }}"{{ $ring->ring_size == $sz->size ? 'selected' : '' }}>
                                            {{ $sz->size }} cm
                                        </option>
                                    @endforeach
                                </select>
                                @error('ring_size')
                                    <div class="alert alert-sm alert-danger my-2 py-1" id="auto-alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="ring_color" class="col-md-3 my-2 d-flex justify-content-end ">Ring Color*</label>
                            <div class="form-group col-md-8">
                                <select required name="ring_color" id="ring_color" class="form-control">
                                    <option value="" selected disabled>Select Color</option>
                                    @foreach ($colors as $c)
                                        <option
                                            value="{{ $c->id }}"{{ $ring->ring_color == $c->id ? 'selected' : '' }}>
                                            {{ $c->color_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ring_color')
                                    <div class="alert alert-sm alert-danger my-2 py-1" id="auto-alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="ring_width" class="col-md-3 my-2 d-flex justify-content-end ">Ring Width*</label>
                            <div class="form-group col-md-8">
                                <select required name="ring_width" id="ring_width" class="form-control">
                                    <option value="" selected disabled>Select Width</option>
                                    @foreach ($widths as $w)
                                        <option
                                            value="{{ $w->id }}"{{ $ring->ring_width == $w->id ? 'selected' : '' }}>
                                            {{ $w->width }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ring_width')
                                    <div class="alert alert-sm alert-danger my-2 py-1" id="auto-alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="ring_karat" class="col-md-3 my-2 d-flex justify-content-end ">Ring Karat*</label>
                            <div class="form-group col-md-8">
                                <select required name="ring_karat" id="ring_karat" class="form-control">
                                    <option value="" selected disabled>Select Karat</option>
                                    @foreach ($karats as $k)
                                        <option
                                            value="{{ $k->karat }}"{{ $ring->ring_karat == $k->karat ? 'selected' : '' }}>
                                            {{ $k->karat }}K
                                        </option>
                                    @endforeach
                                </select>
                                @error('ring_karat')
                                    <div class="alert alert-sm alert-danger my-2 py-1" id="auto-alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="ring_karat" class="col-md-3 my-2 d-flex justify-content-end ">Diamond Shape*</label>
                            <div class="form-group col-md-8">
                                <select name="d_shape" id="d_shape" class="form-control" required>
                                    <option value="" selected disabled>Select Shape</option>
                                    @foreach ($shapes as $shape)
                                        <option value="{{ $shape->title }}"
                                            {{ $ring->diamond_shape == $shape->title ? 'selected' : '' }}>
                                            {{ $shape->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('d_shape')
                                    <div class="alert alert-sm alert-danger my-2 py-1" id="auto-alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="ring_price" class="col-md-3 my-2 d-flex justify-content-end ">Price*</label>
                            <div class="form-group col-md-8">
                                <input type="text" class="form-control" name="ring_price" id="ring_price" required
                                    placeholder="" value="{{ $ring->ring_price }}">
                                @error('ring_price')
                                    <div class="alert alert-sm alert-danger my-2 py-1" id="auto-alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-2 ">
                            <label for="title" class="col-md-3 my-2 d-flex justify-content-end ">Ring Image</label>
                            <div class="form-group col-md-4">
                                <input type="file" name="ring_image" id="image" class="form-control"
                                    accept=".jpg,.png,.jpeg,.gif,.svg" onchange="previewImage(event, 'imagePreview')">
                            </div>
                            <div class="form-group col-md-4">
                                <img id="imagePreview" src="{{ asset('storage/images/rings/' . $ring->ring_image) }}"
                                    alt="Image Preview" @if (!isset($ring->ring_image)) style="display:none;" @endif
                                    width="150" class="mt-2 rounded">
                            </div>
                        </div>
                        <div class="row mb-2 ">
                            <label for="title" class="col-md-3 my-2 d-flex justify-content-end ">Ring Hover
                                Image</label>
                            <div class="form-group col-md-4">
                                <input type="file" name="ring_hover_img" id="image" class="form-control"
                                    accept=".jpg,.png,.jpeg,.gif,.svg" onchange="previewImage(event, 'imagePreview1')">
                            </div>
                            <div class="form-group col-md-4">
                                <img id="imagePreview1"
                                    src="{{ asset('storage/images/rings/' . $ring->ring_hover_img) }}"
                                    alt="Image Preview" @if (!isset($ring->ring_hover_img)) style="display:none;" @endif
                                    width="150" class="mt-2 rounded">
                            </div>
                        </div>
                        <div class="d-flex justify-content-center my-4  gap-3 ">
                            <button class="btn btn-secondary" type="submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function previewImage(event, previewId) {
            const input = event.target;
            const preview = document.getElementById(previewId);

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                preview.style.display = 'none';
            }
        }


        function generateSlug(text) {
            return text
                .toLowerCase() // Convert to lowercase
                .trim() // Remove leading and trailing spaces
                .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                .replace(/\s+/g, '-') // Replace spaces with dashes
                .replace(/-+/g, '-'); // Remove multiple dashes
        }

        document.getElementById("title").addEventListener("input", function() {
            document.getElementById("slug").value = generateSlug(this.value);
        });
    </script>
@endpush
