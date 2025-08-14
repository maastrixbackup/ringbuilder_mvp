@extends('admin.layouts.app')
@section('title', 'Diamond Add')
@section('content')
    @include('components.page-header', [
        'title' => 'Diamond Add',
        'btnLink' => route('admin.diamonds.index'),
        'btnText' => 'Back',
        'btnFunClick' => '',
        'breadcrumbs' => [
            ['name' => 'Diamonds'], //  'url' = 'javascript:;'
            ['name' => ' / Diamond Add'], // No URL = current page
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
                <div class="alert alert-danger m-2">
                    {{ session('error') }}
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.diamonds.update', $diamond->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-2">
                            <label for="title" class="col-md-3 my-2 d-flex justify-content-end ">Title*</label>
                            <div class="form-group col-md-8">
                                <input type="text" class="form-control" name="title" id="title" required
                                    placeholder="" value="{{ $diamond->title }}" autofocus>
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
                                    placeholder="Ex:- test-slug" value="{{ $diamond->slug }}">
                                @error('slug')
                                    <div class="alert alert-sm alert-danger my-2 py-1" id="auto-alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="shape" class="col-md-3 my-2 d-flex justify-content-end ">Shape*</label>
                            <div class="form-group col-md-8">
                                <select name="shape" id="shape" class="form-control" required>
                                    <option value="" selected disabled>Select Shape</option>
                                    @foreach ($dShapes as $shape)
                                        <option value="{{ $shape->title }}"
                                            {{ $diamond->shape == $shape->title ? 'selected' : '' }}>{{ $shape->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('shape')
                                    <div class="alert alert-sm alert-danger my-2 py-1" id="auto-alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="cut" class="col-md-3 my-2 d-flex justify-content-end ">Cut*</label>
                            <div class="form-group col-md-8">
                                {{-- @php
                                    $cuts = ['Good', 'Very-Good', 'Excellent'];
                                @endphp --}}
                                <select required name="cut" id="cut" class="form-control">
                                    <option value="" selected disabled>Select Size</option>
                                    @foreach ($dCuts as $dCut)
                                        <option value="{{ $dCut->cut }}"
                                            {{ $diamond->cut == $dCut->cut ? 'selected' : '' }}>
                                            {{ $dCut->cut }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cut')
                                    <div class="alert alert-sm alert-danger my-2 py-1" id="auto-alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="grown_type" class="col-md-3 my-2 d-flex justify-content-end ">Grown Type*</label>
                            <div class="form-group col-md-8">
                                <select required name="grown_type" id="grown_type" class="form-control">
                                    <option value="" selected disabled>Select Type</option>
                                    <option value="1"{{ $diamond->grown_type == 1 ? 'selected' : '' }}>Natural
                                    </option>
                                    <option value="2" {{ $diamond->grown_type == 2 ? 'selected' : '' }}>Lab-Grown
                                    </option>
                                </select>
                                @error('cut')
                                    <div class="alert alert-sm alert-danger my-2 py-1" id="auto-alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="color" class="col-md-3 my-2 d-flex justify-content-end ">Color*</label>
                            <div class="form-group col-md-8">
                                <select required name="color" id="color" class="form-control">
                                    {{-- @php
                                        $colors = ['M', 'L', 'K', 'J', 'I', 'H', 'G', 'F', 'E', 'D'];
                                    @endphp --}}
                                    <option value="" selected disabled>Select Color</option>
                                    @foreach ($dColors as $dColor)
                                        <option
                                            value="{{ $dColor->title }}"{{ $diamond->color == $dColor->title ? 'selected' : '' }}>
                                            {{ $dColor->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('color')
                                    <div class="alert alert-sm alert-danger my-2 py-1" id="auto-alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="carat" class="col-md-3 my-2 d-flex justify-content-end ">Carat*</label>
                            <div class="form-group col-md-8">
                                <input type="text" class="form-control" name="carat" id="carat"
                                    value="{{ $diamond->carat }}" placeholder="Ex:- 1.52">
                                @error('carat')
                                    <div class="alert alert-sm alert-danger my-2 py-1" id="auto-alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="clarity" class="col-md-3 my-2 d-flex justify-content-end ">Clarity*</label>
                            <div class="form-group col-md-8">
                                {{-- <input type="text" class="form-control" name="clarity" id="clarity"
                                    value="{{ $diamond->clarity }}" placeholder="Ex:- VS2"> --}}
                                <select required name="clarity" id="clarity" class="form-control">
                                    <option value="" selected disabled>Select Clarity</option>
                                    @foreach ($dClarity as $dc)
                                        <option value="{{ $dc->clarity }}"
                                            {{ $diamond->clarity == $dc->clarity ? 'selected' : '' }}>
                                            {{ $dc->clarity }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('clarity')
                                    <div class="alert alert-sm alert-danger my-2 py-1" id="auto-alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="price" class="col-md-3 my-2 d-flex justify-content-end ">Price*</label>
                            <div class="form-group col-md-8">
                                <input type="text" class="form-control" name="price" id="price" required
                                    placeholder="" value="{{ $diamond->price }}">
                                @error('price')
                                    <div class="alert alert-sm alert-danger my-2 py-1" id="auto-alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-2 ">
                            <label for="title" class="col-md-3 my-2 d-flex justify-content-end ">Main Image</label>
                            <div class="form-group col-md-4">
                                <input type="file" name="image_one" id="image" class="form-control"
                                    accept=".jpg,.png,.jpeg,.gif,.svg" onchange="previewImage(event, 'imagePreview')">
                            </div>
                            <div class="form-group col-md-4">
                                <img id="imagePreview" src="{{ asset('storage/images/diamonds/' . $diamond->img_one) }}"
                                    alt="Image Preview" @if (!isset($diamond->img_one)) style="display:none;" @endif
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
