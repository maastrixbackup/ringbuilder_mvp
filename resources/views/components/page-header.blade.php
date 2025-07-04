<div class="page-header d-flex align-items-center justify-content-between flex-wrap mb-3">
    <div class="d-flex align-items-center gap-1">
        <a href="{{ route('admin.dashboard') }}">
            <h5 class="fw-bold mb-0">Dashboard</h5>
        </a> /
        <ul class="breadcrumbs d-flex align-items-center mb-0">
            @if (!empty($breadcrumbs))
                @foreach ($breadcrumbs as $breadcrumb)
                    <li class="separator me-1">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        @if (!empty($breadcrumb['url']))
                            <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['name'] }}</a>
                        @else
                            <span>{{ $breadcrumb['name'] }}</span>
                        @endif
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
    <a href="{{ $btnLink ?? 'javascript:;' }}" class="btn btn-primary  ms-auto text-right">{{ $btnText ?? '' }}</a>
</div>
