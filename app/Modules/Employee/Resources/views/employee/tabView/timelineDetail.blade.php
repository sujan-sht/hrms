<div class=" d-flex justify-content-end mr-5">
    <div style="margin-right: 20px;">
        <strong class="mt-1"> Switch : &nbsp;</strong>
        <input type="checkbox" checked data-toggle="toggle"data-onstyle="success" data-offstyle="danger" data-width="50"
            data-height="10" id="toggleCheckbox">
    </div>
</div>
<div id="tableDiv" style="display: none;">
    @include('employee::employee.tabView.table-list')
</div>

<div class="pt-3">
    <div class="timeline timeline-center" id="timelineDiv">
        @if ($menuRoles->assignedRoles('downloadTimelineReport'))
            <div style="display: flex; justify-content: center;">
                <a class="btn btn-outline-warning btn-icon mx-1"
                    href="{{ route('downloadTimelineReport', $employeeModel->id) }}" data-popup="tooltip"
                    data-placement="bottom" data-original-title="Download PDF"><i class="icon-download"></i>
                </a>
            </div>
        @endif
        <div class="timeline-container">
            <div class="timeline-date text-muted ">
                <h3 class="text-dark">My Timeline History</h3>
            </div>

            @if ($timelineModels->isNotEmpty())
                @foreach ($timelineModels as $key => $timelineModel)
                    @php
                        if ($key % 2 == 0) {
                            $align = 'timeline-row-left';
                            $textAlign = 'text-right';
                        } else {
                            $align = 'timeline-row-right';
                            $textAlign = 'text-left';
                        }
                    @endphp
                    <div class="timeline-row {{ $align }}">
                        <div class="timeline-icon">
                            <div class="bg-{{ $timelineModel['color'] ?: 'info' }} text-white">
                                <i class="{{ $timelineModel['icon'] ?: 'icon-pulse2' }}"></i>
                            </div>
                        </div>

                        <div class="timeline-time">
                            {{ $timelineModel['title'] }}
                            <div class="text-muted">{{ date('d M, Y', strtotime($timelineModel['date'])) }}
                                ({{ date_converter()->eng_to_nep_convert($timelineModel['date']) }})
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header {{ $textAlign }}">
                                <h6 class="card-title">
                                    @php
                                        $rawDescription = $timelineModel['description'] ?? null;
                                        $descriptions = [];

                                        if ($rawDescription) {
                                            // Try to decode as JSON
                                            $decoded = json_decode($rawDescription, true);
                                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                $descriptions = $decoded;
                                            } else {
                                                $descriptions = [$rawDescription]; // fallback to plain string
                                            }
                                        }
                                    @endphp

                                    @if (!empty($descriptions))
                                        <ul class="mb-0 list-disc">
                                            @foreach ($descriptions as $desc)
                                                @if (!empty($desc))
                                                    <li>{{ $desc }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif

                                </h6>
                            </div>
                        </div>
                    </div>

                    @if (isset($timelineModels[$key + 1]['date']))
                        @php
                            $currentMonth = date('M', strtotime($timelineModel['date']));
                            $currentYear = date('Y', strtotime($timelineModel['date']));
                            $nextMonth = date('M', strtotime($timelineModels[$key + 1]['date']));
                            $nextYear = date('Y', strtotime($timelineModels[$key + 1]['date']));
                        @endphp
                        @if ($nextMonth != $currentMonth)
                            <div class="timeline-date text-muted">
                                <i class="icon-history mr-2"></i> <span
                                    class="font-weight-semibold"></span>{{ $nextMonth }}, {{ $nextYear }}
                            </div>
                        @endif
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>
