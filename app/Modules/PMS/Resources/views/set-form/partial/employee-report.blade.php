@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@php
    $final_weightage = 0;

    $final_achieved_perc_Q1 = 0;
    $final_achieved_perc_Q2 = 0;
    $final_achieved_perc_Q3 = 0;
    $final_achieved_perc_Q4 = 0;

    $final_score_Q1 = 0;
    $final_score_Q2 = 0;
    $final_score_Q3 = 0;
    $final_score_Q4 = 0;

    $average_YTD = 0;
    $final_YTD = 0;
    $count = 1;
@endphp
@if (!$targetAchievementModel->isEmpty())
    @foreach ($targetAchievementModel as $key => $targetAchievement)
        <tr>
            <td>{{ $count++ }}</td>
            <td>{{ optional($targetAchievement->first()->kraInfo)->title }}</td>
            <td>{{ optional($targetAchievement->first()->kpiInfo)->title }}</td>
            <td>{{ optional($targetAchievement->first()->targetInfo)->title }}</td>
            <td>{{ optional($targetAchievement->first()->targetInfo)->frequency }}</td>
            <td>{{ isset(optional($targetAchievement->first()->targetInfo)->weightage) ? optional($targetAchievement->first()->targetInfo)->weightage . '%' : '' }}
            <td>{{ isset(optional($targetAchievement->first()->targetInfo)->eligibility) ? optional($targetAchievement->first()->targetInfo)->eligibility . '%' : '' }}
            </td>
            @php
                $final_weightage += optional($targetAchievement->first()->targetInfo)->weightage;
            @endphp
            @for ($i = 0; $i < 4; $i++)
                @php
                    $target_value = $targetAchievement[$i]->target_value ?? '';
                    $target_id = $targetAchievement[$i]->id ?? '';
                    $achieved_value = $targetAchievement[$i]->achieved_value ?? '';
                    $achieved_percent = $targetAchievement[$i]->achieved_percent ?? '';
                    $score = $scorearray[$i] = $targetAchievement[$i]->score ?? '';
                    $kpi_id = $targetAchievement[$i]->kpi_id ?? '';
                    $remarks = $targetAchievement[$i]->remarks ?? '';

                    if ($i == 0) {
                        if ($achieved_percent != '') {
                            $final_achieved_perc_Q1 += $achieved_percent;
                        }
                        if ($score != '') {
                            $final_score_Q1 += $score;
                        }
                    } elseif ($i == 1) {
                        if ($achieved_percent != '') {
                            $final_achieved_perc_Q2 += $achieved_percent;
                        }
                        if ($score != '') {
                            $final_score_Q2 += $score;
                        }
                    } elseif ($i == 2) {
                        if ($achieved_percent != '') {
                            $final_achieved_perc_Q3 += $achieved_percent;
                        }
                        if ($score != '') {
                            $final_score_Q3 += $score;
                        }
                    } elseif ($i == 3) {
                        if ($achieved_percent != '') {
                            $final_achieved_perc_Q4 += $achieved_percent;
                        }
                        if ($score != '') {
                            $final_score_Q4 += $score;
                        }
                    }
                @endphp
                <td>
                    {!! Form::text($target_id, $target_value, ['class' => 'form-control numeric targetValues']) !!}
                </td>

                <td>{{ $achieved_value }}</td>
                <td>{{ $achieved_percent }}</td>
                <td>{{ $score }}</td>
            @endfor
            @php

                $scorearr = array_filter($scorearray, function ($value) {
                    return $value != 0;
                });
                if (count($scorearr) > 0) {
                    $average_YTD = number_format(array_sum($scorearr) / count($scorearr), 2);
                } else {
                    $average_YTD = 0;
                }

                $final_YTD += $average_YTD;
            @endphp

            <td>{{ $average_YTD . ' %' }}</td>
            <td>{{ $remarks }}</td>
            <td>
                <div class="d-flex">

                    @if($menuRoles->assignedRoles('set-form.editTargetValues'))
                        <a class="btn btn-outline-primary btn-icon mx-1 editValues" href="javascript:void(0)" data-popup="tooltip" data-placement="top" data-original-title="Update">
                            <i class="icon-database-insert"></i>
                        </a>
                    @endif

                    @if($menuRoles->assignedRoles('set-form.deleteKpi'))
                        <a class="btn btn-outline-danger btn-icon confirmKpiDelete" link="{{route('set-form.deleteKpi', $kpi_id)}}" data-popup="tooltip" data-placement="top" data-original-title="Delete">
                            <i class="icon-trash-alt"></i>
                        </a>
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
    <tr>
        <td colspan="5"><b>Final Score</b></td>
        <td><b>{{ $final_weightage . ' %' }}</b></td>

        <td colspan="3">&nbsp;</td>
        <td><b>{{ $final_achieved_perc_Q1 }}%</b></td>
        <td><b>{{ $final_score_Q1 . ' %' }}</b></td>

        <td colspan="2">&nbsp;</td>
        <td><b>{{ $final_achieved_perc_Q2 }}%</b></td>
        <td><b>{{ $final_score_Q2 . ' %' }}</b></td>

        <td colspan="2">&nbsp;</td>
        <td><b>{{ $final_achieved_perc_Q3 }}%</b></td>
        <td><b>{{ $final_score_Q3 . ' %' }}</b></td>

        <td colspan="2">&nbsp;</td>
        <td><b>{{ $final_achieved_perc_Q4 }}%</b></td>
        <td><b>{{ $final_score_Q4 . ' %' }}</b></td>

        <td><b>{{ $final_YTD . '%' }}</b></td>
        <td></td>
        <td></td>
    </tr>
@else
    <tr>
        <td colspan="28">No Records Found !!!</td>
    </tr>
@endif

<script>
    $(document).ready(function () {
        $('.numeric').keyup(function() {
            if (this.value.match(/[^0-9.]/g)) {
                this.value = this.value.replace(/[^0-9.]/g, '');
            }
        });
        //Update target values
        $('.editValues').on('click', function() {
            let target_val = $(this).closest('tr').find('input').serializeArray()
            let form_data ={
                target_val,
                '_token': "{{ csrf_token() }}"
            }

            $.ajax({
                type: "POST",
                url: "{{ route('set-form.editTargetValues') }}",
                dataType: 'json',
                data: form_data,
                success: function (response) {
                    if(response.status == true){
                        toastr.success(response.message)
                        var emp_id = $('.employeeId').val()
                        if(emp_id != ''){
                            fetchEmployeeReport(emp_id)
                        }
                    }
                }
            })
        })
        //

        //Fetch employee report
        function fetchEmployeeReport(emp_id) {
            let formData = {
                emp_id,
                "_token": "{{ csrf_token() }}"
            };
            $.ajax({
                type: "GET",
                url: "{{ route('set-form.view') }}",
                data: formData,
                success: function(resp) {
                    $('#employeeTargetReport').html(resp.view)
                }
            })
        }
        //

        //delete target achievement data
        $('.confirmKpiDelete').on('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let row_link = $(this).attr('link')
                    var employee_id = $('.employeeId').val()
                    if(employee_id != ''){
                        $.ajax({
                            type: "GET",
                            url: row_link,
                            data: {
                                employee_id: employee_id,
                            },
                            success: function (response) {
                                if(response.status == true){
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: response.message,
                                        icon:'success',
                                        showCancelButton: false,
                                        showConfirmButton: false,
                                    })
                                    fetchEmployeeReport(employee_id)
                                }
                            }
                        })
                    }
                }
            })
        })
        //

    })
</script>

