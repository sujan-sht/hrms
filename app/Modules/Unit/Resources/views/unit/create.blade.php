@extends('admin::layout')
@section('title') Unit @endSection
@section('breadcrum')
    <a href="{{ route('unit.index') }}" class="breadcrumb-item">Unites</a>
    <a class="breadcrumb-item active">Create</a>
@endSection

@section('script')
<script>
    

    $(document).ready(function(){
            var branches=@json($branches) ?? null;
            $('.organization_id').change(function(){
                var organizationId=$(this).val() ??  "{{@$unit->organization_id}}";
                var branchId="{{@$unit->branch_id}}" ?? null;
                var branchHtml='';
                branchHtml+='<option value="">Select Branch</option>';
                $.each(branches,function(index,value){
                    if(value.organization_id==organizationId){
                        branchHtml+=`<option value="${value.id}" ${branchId==value.id ? 'selected':''}>${value.name}</option>`;
                    }
                });
                $('.branch_id').html(branchHtml);
            });

            @isset($unit)
                $('.organization_id').change();
            @endisset
        });

    </script>
@endSection

@section('content')
    @isset($unit)
    {!! Form::open(['route'=>['unit.update',$unit->id],'method'=>'PUT','class'=>'form-horizontal','id'=>'unitFormSubmit','role'=>'form','files' => true]) !!}
    @else
    {!! Form::open(['route'=>'unit.store','method'=>'POST','class'=>'form-horizontal','id'=>'unitFormSubmit','role'=>'form','files' => true]) !!}
    @endif
        @include('unit::unit.partial.action',['btnType'=>@$unit ? 'Update Record':'Save Record'])

    {!! Form::close() !!}

@endSection
