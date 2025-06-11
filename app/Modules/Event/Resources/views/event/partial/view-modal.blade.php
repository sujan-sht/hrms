<div class="card">
    <div class="card-header d-flex justify-content-between border-bottom-0 pb-0">
        <h6 class="card-title">{{ $result->title }}</h6>

    </div>
    <div class="card-body">
        <span class="text-muted">
            {{ getStandardDateFormat($result->start_date) }}
            {{ !empty($result->end_date) ? '-' . getStandardDateFormat($result->end_date) : '' }}
        </span>
        <p class="card-text">{!! $result->description !!}</p>
    </div>

</div>
