@extends('admin::layout')
@section('title') Module @stop
@section('breadcrum')
<a class="breadcrumb-item active">Module</a>
@endsection

@section('script')
<!-- Theme JS files -->
<script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
<!-- /theme JS files -->
{{-- <script src="{{ asset('admin/validation/setting.js') }}"></script> --}}
@stop
@section('content')
<div class="row">
    <div class="col-12">
        <form class="login-form" action="index.html">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="text-center">
                        <div class="card-img-actions d-inline-block mb-3">
                            <img class="rounded-circle" src="../../../assets/images/demo/users/face11.jpg" width="160" height="160" alt="">
                            <div class="card-img-actions-overlay card-img rounded-circle">
                                <a href="#" class="btn btn-outline-white btn-icon rounded-pill">
                                    <i class="ph-upload-simple"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-3">
                        <h6 class="mb-0">Victoria Baker</h6>
                        <span class="text-muted">Unlock your account</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Enter password</label>
                        <div class="form-control-feedback form-control-feedback-start">
                            <input type="password" class="form-control" placeholder="•••••••••••">
                            <div class="form-control-feedback-icon">
                                <i class="ph-lock text-muted"></i>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <label class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" checked="">
                            <span class="form-check-label">Remember</span>
                        </label>

                        <a href="login_password_recover.html" class="ms-auto">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ph-lock-key-open me-2"></i>
                        Unlock
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


@stop
