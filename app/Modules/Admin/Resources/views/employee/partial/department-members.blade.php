<div class="card" style="height: 540px;">
    <div class="card-header bg-transparent header-elements-inline">
        <h4 class="card-title font-weight-semibold">
            Sub-Function Members
        </h4>
        <div class="header-elements">
            <div class="list-icons ml-3">
                <span class="pending-req float-right">{{ $dept_employees->total() }}</span>
            </div>
        </div>
    </div>

    <div class="card-body" style="overflow-y: scroll">
        <ul class="media-list">
            @if ($dept_employees->total() > 0)
                @foreach ($dept_employees as $dvalue)
                    @php
                        if ($dvalue->profile_pic != '') {
                            $imagePath = asset($dvalue->file_full_path) . '/' . $dvalue->profile_pic;
                        } else {
                            $imagePath = asset('admin/default.png');
                        }
                    @endphp
                    <li class="media">
                        <div class="media-body">
                            <a>
                                <span class="media-title d-block font-weight-semibold">
                                    {{ !empty($dvalue->middle_name) ? $dvalue->first_name . ' ' . $dvalue->middle_name . ' ' . $dvalue->last_name : $dvalue->first_name . ' ' . $dvalue->last_name }}
                                </span>
                            </a>
                            <i class="icon-envelop text-info font-size-sm mr-1"></i>
                            {{ $dvalue->personal_email ? $dvalue->personal_email : $dvalue->official_email }}
                            <br>
                            <i class="icon-phone text-teal font-size-sm mr-1"></i>
                            {{ $dvalue->cug_number ? $dvalue->cug_number : $dvalue->mobile }}
                        </div>

                        <div class="ml-3">
                            <a href="{{ route('employment.view', $dvalue->id) }}">
                                <img src="{{ $imagePath }}" class="rounded-circle" width="50" height="50"
                                    alt="">
                            </a>
                        </div>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>
