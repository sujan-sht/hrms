@extends('admin::layout')
@section('title') View Report @stop
@section('breadcrum')
    <a class="breadcrumb-item active">View Report</a>
@endSection

@section('content')
    <form action="{{ route('target.updateAchievement') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card card-body">
            <table class="table table-bordered table-responsive">
                <thead class="bg-slate text-center text-white">
                    <tr>
                        <th rowspan="3">S.N</th>
                        <th rowspan="3">KRA</th>
                        <th rowspan="3">KPIs</th>
                        <th rowspan="3">Target</th>
                        <th rowspan="3">Frequency/Age</th>
                        <th rowspan="3">Weightage (%)</th>
                        <th rowspan="3">Eligibility (%)</th>
                        <th colspan="8">TARGET VS ACHIEVEMENT</th>
                        <th rowspan="3">Remarks</th>
                        <th rowspan="3">Attachment</th>
                    </tr>
                    <tr>
                        <th colspan="2">Q1</th>
                        <th colspan="2">Q2</th>
                        <th colspan="2">Q3</th>
                        <th colspan="2">Q4</th>
                    </tr>
                    <tr>
                        <th>TGT</th>
                        <th style="padding: 0px 30px;">ACH</th>

                        <th>TGT</th>
                        <th style="padding: 0px 30px;">ACH</th>

                        <th>TGT</th>
                        <th style="padding: 0px 30px;">ACH</th>

                        <th>TGT</th>
                        <th style="padding: 0px 30px;">ACH</th>
                    </tr>
                </thead>
                <tbody>
                    {!! Form::hidden('employee_id', $employee_id, ['class' => 'form-control numeric']) !!}
                    @php
                        $count = 1;
                    @endphp
                    @foreach ($targetAchievementModel as $key => $targetAchievement)
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td>{{ optional($targetAchievement->first()->kraInfo)->title }}</td>
                            <td>{{ optional($targetAchievement->first()->kpiInfo)->title }}</td>
                            <td>{{ optional($targetAchievement->first()->targetInfo)->title }}</td>
                            <td>{{ optional($targetAchievement->first()->targetInfo)->frequency }}</td>
                            <td>{{ optional($targetAchievement->first()->targetInfo)->weightage }}</td>
                            <td>{{ optional($targetAchievement->first()->targetInfo)->eligibility }}</td>

                            @for ($i = 0; $i < 4; $i++)
                                @php
                                    $target_value = $targetAchievement[$i]->target_value ?? '';
                                    $achieved_value = $targetAchievement[$i]->achieved_value ?? '';
                                    $remarks = $targetAchievement[$i]->remarks ?? '';
                                @endphp
                                <td>{{ $target_value}}</td>
                                @if($target_value)
                                 <td>{!! Form::text('achieved_value[' . $key . ']['. $i.']', $achieved_value, ['class' => 'form-control numeric']) !!}</td>
                                 {!! Form::hidden('target_value[' . $key . ']['. $i.']', $target_value, ['class' => 'form-control numeric']) !!}
                                 {!! Form::hidden('weightage[' . $key . ']['. $i.']', optional($targetAchievement->first()->targetInfo)->weightage, ['class' => 'form-control numeric']) !!}
                                 {!! Form::hidden('eligibility[' . $key . ']['. $i.']', optional($targetAchievement->first()->targetInfo)->eligibility, ['class' => 'form-control numeric']) !!}
                                @else
                                <td></td>
                                @endif

                            @endfor
                           
                            <td>{!! Form::text('remarks['. $key . ']', $remarks, ['class' => 'form-control']) !!}</td>

                            <td>{!! Form::file('attachments['. $key . '][]', ['class' => 'form-control', 'accept' => ".jpg, .png, .doc, .pdf", 'multiple']) !!}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            {{-- <div class="form-group row">
                <div class="col-lg-6 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-2">Attachment : </label>
                        <div class="col-lg-10">
                            <input type="file" name="attachments[]" class="form-control h-auto" accept=".jpg, .png, .doc, .pdf" multiple>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-6 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-2">Remarks : </label>
                        <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::text('remarks', null, [
                                    'placeholder' => 'Write Remarks here..',
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                        class="icon-database-insert"></i></b>Save Changes</button>
        </div>
    </form

@endSection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
@endSection
