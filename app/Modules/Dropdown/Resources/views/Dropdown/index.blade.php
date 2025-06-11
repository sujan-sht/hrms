@extends('admin::layout')
@section('title') DropDowns @stop
@section('breadcrum')
<a class="breadcrumb-item active">DropDowns</a>
@endsection

@section('script')
<script src="{{asset('admin/global/js/plugins/tables/datatables/datatables.min.js')}}"></script>
<script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
@stop

@section('content')


<div class="card">
    <div class="card-body d-sm-flex align-items-sm-center justify-content-sm-between flex-sm-wrap">
        <a href="{{ route('dropdown.create') }}" class="btn btn-pink btn-labeled btn-labeled-left"><b><i class="icon-add-to-list"></i></b>Add DropDown Value </a>

        <a href="{{ route('dropdown.createField') }}" class="btn btn-yellow btn-labeled btn-labeled-left"><b><i class="icon-add-to-list"></i></b>Add DropDown Field </a>
    </div>
</div>

<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">List of DropDown</h5>
    </div>

    <div class="table-responsive">
        <table class="table table-striped" id="table1" cellspacing="0" width="100%">
            <thead>
                <tr class="bg-secondary text-white">
                    <th>List of Field (Note: Click Field To View Dropdown Value) </th>
                </tr>
            </thead>
            <tbody>
                @if ($field->total() > 0)
                @foreach ($field as $key)

                <tr>
                    <td><a href="javascript:;" class="toggle_sku"><i rel="allfield" class="icon-plus-circle2"></i>&nbsp;&nbsp;&nbsp; Field: {{ $key->title }} :: Slug [ {{ $key->slug }} ]</a></td>
                </tr>

                @php
                $field_value = $key->dropdownValue;
                @endphp

                @if (count($field_value) > 0)
                <tr style="display: none">
                    <td colspan="8">
                        <table class="table table-striped">
                            <thead>
                                <tr class="bg-secondary text-light">
                                    <th>Sn.</th>
                                    <th>Dropdown Value</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $j = 1; @endphp
                                @foreach ($field_value as $val)

                                <tr>
                                    <td>
                                        {{ $j }}
                                    </td>
                                    <td>
                                        {{ $val->dropvalue }}
                                    </td>
                                    @if($key->slug != "user_type")

                                       @if(($val->dropvalue != 'Operator') AND ($val->dropvalue != 'Assistant'))
                                        <td class="table-action">
                                            <a class="btn btn-info btn-sm" href="{{route('dropdown.edit',$val->id)}}"><i class="fa fa-edit tooltips" data-original-title="Edit Dropdown value"></i> Edit</a> |
                                            <button type="button" class="btn btn-danger btn-sm delete_dropdown" data-toggle="modal" link="{{route('dropdown.delete',$val->id)}}" data-target="#modal_theme_warning"><i class="fa fa-trash tooltips" data-original-title="Delete Dropdown value"></i> Delete</button>
                                        </td>
                                        @endif

                                    @else
                                    <td> - No Action Active - </td>
                                    @endif
                                </tr>

                                @php $j++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
                @else
                <tr style="display: none" class="bg-success-300">
                    <td colspan="8">No Dropdown Value Found</td>
                </tr>
                @endif
                @endforeach
                @else
                <tr>
                    <td colspan="8">
                        <center>No Dropdown Found !!!</center>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>


<!-- Warning modal -->
<div id="modal_theme_warning" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <center>
                <i class="icon-alert icon-2x text-danger border-danger border-3 rounded-pill p-3 mb-1 mt-1"></i>
                </center>
                <br>
                <center>
                    <h2>Are You Sure Want To Delete ?</h2>
                    <a class="btn btn-success get_link" href="">Yes, Delete It!</a>
                    <button type="button" class="ml-1 btn btn-danger" data-dismiss="modal">Cancel</button>
                </center>
            </div>
        </div>
    </div>
</div>
<!-- /warning modal -->

<script type="text/javascript">
    $(document).ready(function() {
        $('.delete_petty').on('click', function() {
            var link = $(this).attr('link');
            $('.get_link').attr('href', link);
        });

        $('.delete_dropdown').on('click', function() {
            var link = $(this).attr('link');
            $('.get_link').attr('href', link);
        });

        $('.toggle_sku').click(function() {
            $(this).parent().parent().next().toggle('slow');
            $(this).find('i').toggleClass("icon-plus-circle2 icon-minus-circle2");
        });

    });

</script>

@endsection
