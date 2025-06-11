<div class="card">
    <div class="card-body bg-secondary text-white" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
        <div class="row">
            <div class="col-md-7">
                <h4 class="pb-4">Leave Summary</h4>
                <h1 class="mb-0">{{ $remaining_leave }}
                    {{-- <small>/ {{ $total_leave }}</small> --}}
                </h1>
                <span class="text-uppercase font-size-xs">Remaining
                    {{-- /Total</span> --}}
            </div>
            <div class="col-md-5">
                <img src="{{ asset('admin/leave.png') }}" width="100%">
            </div>
        </div>
    </div>
</div>
