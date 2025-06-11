@extends('admin::employee.layout')
@section('title')Notification @endSection
@section('breadcrum')
    <a class="breadcrumb-item active">Notification</a>
@stop

@section('content')


<div class="card">
    <div class="card-body">
        <fieldset>
            <legend class="text-uppercase font-size-sm font-weight-bold"><h6>Your Notification</h6></legend>

            <div class="list-feed-rhombus">
                @if($notification->total() >0)
                @foreach($notification as $key => $notice) @php $icon = ($notice->is_read =='0') ? "icon-bell3" :"icon-bell-check"; $icon_color = ($notice->is_read =='0') ? "text-danger" :"text-success"; @endphp
                <div class="list-feed-item d-flex flex-nowrap">

                    <span class="mr-3">
                        <a href="{{route('Notification.checkLink',['notification_id'=>$notice->id])}}"><i class="{{$icon}} {{$icon_color}} mr-1"></i><span class="text-dark">{!!  $notice->message !!}</span></a>
                    <div class="text-muted">{{$notice->created_at->diffForHumans().' '. date('g:i A', strtotime($notice->created_at))}}</div>
                    </span>

                    <div class="ml-auto">
                        <div class="list-icons">
                            <a href="{{$notice->link}}" class="list-icons-item ml-1"><i class="icon-circle-right2"></i></a>
                        </div>
                    </div>
                </div>
                <hr />
                @endforeach
                @else
                <span>
                No Notification
                </span>
                @endif

            </div>

        </fieldset>
    </div>
</div>
@endsection
