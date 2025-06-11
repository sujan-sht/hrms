@extends('admin::layout')
@section('title') Warning @stop

@section('breadcrum')
    <a href="{{ route('warning.index') }}" class="breadcrumb-item">Warning</a>
    <a class="breadcrumb-item active">View</a>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
@stop

@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">Warning Detail</h6>
                {{ $warning->title }}
            </div>
            <div class="mt-1">
                <a href="{{ route('warning.index') }}" class="btn btn-success rounded-pill">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-body">

                Date:
                @if (setting('calendar_type') == 'BS')
                    {{ date_converter()->eng_to_nep_convert($warning->date) }}
                @else
                    {{ $warning->date }}
                @endif <br>
                Ref. No. : {{ $warning->ref_no }} <br>
                Reg. No. :{{ $warning->reg_no }}
                <div>

                    {!! $warning->description !!}
                </div>
            </div>
        </div>
        @if (auth()->user()->user_type=='super_admin' || auth()->user()->user_type=='hr' || auth()->user()->user_type=='division_hr' || auth()->user()->user_type=='supervisor')
        <div class="col-lg-4">
            <div class="card card-body">
                Employees: <br>
                @if (!is_null($warning->employee_id))
                    @if (count(json_decode($warning->employee_id)) > 0)
                        @foreach (json_decode($warning->employee_id) as $employee)
                            - {{ App\Modules\Employee\Entities\Employee::find($employee)->full_name }} <br>
                        @endforeach
                    @endif
                @endif

            </div>
        </div>
        @endif
        
    </div>



@endsection
