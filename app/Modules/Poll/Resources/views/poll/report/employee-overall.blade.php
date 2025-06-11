@extends('admin::layout')
@section('title') Polls @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Polls</a>
@stop

@section('content')
<div class="container center">
    <ul class="nav nav-pills d-flex justify-content-center">
        <li class="nav-item">
            <a class="nav-link text-center {{ request()->get('status') == 'active' ? 'active' : '' }}"
                href="{{ route('poll.viewEmployeeReport', ['status' => 'active']) }}">Active
                Polls</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-center {{ request()->get('status') == 'expired' ? 'active' : '' }}"
                href="{{ route('poll.viewEmployeeReport', ['status' => 'expired']) }}">Expired
                Polls</a>
        </li>
    </ul>
</div>

<div class="row mb-2">
    @foreach ($pollFinalReports as $poll_id => $pollFinalReport)
        @include('poll::poll.partial.poll-card')
    @endforeach
</div>

<script>
    $(document).ready(function() {
        $('.pollOption[type=radio]').on('click', function() {
            let poll_option_id = $(this).val()
            let poll_id = $(this).closest('.pollBody').find('.poll').val()

            let form_data = {
                poll_id,
                poll_option_id,
                "_token": "{{ csrf_token() }}"
            }
            $.ajax({
                type: "POST",
                url: "{{ route('poll.storePollResponse') }}",
                dataType: 'json',
                data: form_data,
                success: function(resp) {
                    if(resp){
                        toastr.success('Poll Submitted Successfully !!!')
                        location.reload()
                    }else{
                        toastr.eroor('Poll could not be submitted !!!')
                    }
                },
            })
        })
    })
</script>

@endSection
