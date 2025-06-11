<fieldset class="mb-1">
    <legend class="text-uppercase font-size-sm font-weight-bold">Basic Company Information</legend>


    <div class="form-group row">
        <div class="col-lg-6">
            <div class="row">
                <label class="col-form-label col-lg-3">Company Name:</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-office"></i></span>
                        </span>
                        @if ($is_edit)
                            {!! Form::text('company_name', $setting->company_name, [
                                'id' => 'company_name',
                                'placeholder' => 'Enter Company Name',
                                'class' => 'form-control',
                            ]) !!}
                        @else
                            {!! Form::text('company_name', $value = null, [
                                'id' => 'company_name',
                                'placeholder' => 'Enter Company Name',
                                'class' => 'form-control',
                            ]) !!}
                        @endif
                        <span class="text-danger">{{ $errors->first('company_name') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="row">
                <label class="col-form-label col-lg-3"> Company PAN / VAT No. </label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-cash3"></i></span>
                        </span>
                        @if ($is_edit)
                            {!! Form::text('pan_vat_no', $setting->pan_vat_no, [
                                'id' => 'pan_vat_no',
                                'placeholder' => 'Enter Company PAN/VAT No',
                                'class' => 'form-control',
                            ]) !!}
                        @else
                            {!! Form::text('pan_vat_no', $value = null, [
                                'id' => 'pan_vat_no',
                                'placeholder' => 'Enter Company PAN/VAT No',
                                'class' => 'form-control',
                            ]) !!}
                        @endif
                        <span class="text-danger">{{ $errors->first('pan_vat_no') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-lg-6">
            <div class="row">
                <label class="col-form-label col-lg-3">Company Website:</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-sphere3"></i></span>
                        </span>
                        @if ($is_edit)
                            {!! Form::text('website', $setting->website, [
                                'id' => 'website',
                                'placeholder' => 'Enter Company Website',
                                'class' => 'form-control',
                            ]) !!}
                        @else
                            {!! Form::text('website', $value = null, [
                                'id' => 'website',
                                'placeholder' => 'Enter Company Website',
                                'class' => 'form-control',
                            ]) !!}
                        @endif
                        <span class="text-danger">{{ $errors->first('website') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="row">
                <label class="col-form-label col-lg-3"> Company Copyright:</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><b>©</b></span>
                        </span>
                        @if ($is_edit)
                            {!! Form::text('company_copyright', $setting->company_copyright, [
                                'id' => 'company_copyright',
                                'placeholder' => 'Enter Company Copyright',
                                'class' => 'form-control',
                            ]) !!}
                        @else
                            {!! Form::text('company_copyright', $value = null, [
                                'id' => 'company_copyright',
                                'placeholder' => 'Enter Company Copyright',
                                'class' => 'form-control',
                            ]) !!}
                        @endif
                        <span class="text-danger">{{ $errors->first('company_copyright') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-lg-6">
            <div class="row">
                <label class="col-form-label col-lg-3">Company Logo:</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-image2"></i></span>
                        </span>
                        {!! Form::file('company_logo', $value = null, ['id' => 'company_logo', 'class' => 'form-control']) !!}
                        <span class="text-danger">{{ $errors->first('company_logo') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="row">
                <label class="col-form-label col-lg-2"></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        @if ($is_edit and $setting->company_logo !== null)
                            <img id="bannerImage" src="{{ asset('uploads/setting/' . $setting->company_logo) }}"
                                alt="your image" class="preview-image" style="height: 100px;width: auto;" />
                        @else
                            <img id="bannerImage" src="{{ asset('admin/image.png') }}" alt="your image"
                                class="preview-image" style="height: 100px; width: auto;" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-6">
            <div class="row">
                <label class="col-form-label col-lg-3">Company Header Image::</label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-image2"></i></span>
                        </span>
                        {!! Form::file('company_header_logo', $value = null, ['id' => 'company_header_logo', 'class' => 'form-control']) !!}
                        <span class="text-danger">{{ $errors->first('company_header_logo') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="row">
                <label class="col-form-label col-lg-2"></label>
                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        @if ($is_edit and $setting->company_header_logo !== null)
                            <img id="bannerImage"
                                src="{{ asset('uploads/setting/header/' . $setting->company_header_logo) }}"
                                alt="your image" class="preview-image" style="height: 100px;width: auto;" />
                        @else
                            <img id="bannerImage" src="{{ asset('admin/image.png') }}" alt="your image"
                                class="preview-image" style="height: 100px; width: auto;" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


</fieldset>

<fieldset class="mb-1">
    <legend class="text-uppercase font-size-sm font-weight-bold">Company Detail Information</legend>

    <div class="form-group row">
        <label class="col-form-label col-lg-3">Contact No 1:</label>
        <div class="col-lg-3 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-phone"></i></span>
                </span>
                @if ($is_edit)
                    {!! Form::text('contact_no1', $setting->contact_no1, [
                        'id' => 'contact_no1',
                        'placeholder' => 'Enter contact number',
                        'class' => 'form-control',
                    ]) !!}
                @else
                    {!! Form::text('contact_no1', $value = null, [
                        'id' => 'contact_no1',
                        'placeholder' => 'Enter contact number',
                        'class' => 'form-control',
                    ]) !!}
                @endif
                <span class="text-danger">{{ $errors->first('contact_no1') }}</span>
            </div>
        </div>
        <label class="col-form-label col-lg-3">Contact No 2</label>
        <div class="col-lg-3 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-phone"></i></span>
                </span>
                @if ($is_edit)
                    {!! Form::text('contact_no2', $setting->contact_no2, [
                        'id' => 'contact_no2',
                        'placeholder' => 'Enter contact number 2',
                        'class' => 'form-control',
                    ]) !!}
                @else
                    {!! Form::text('contact_no2', $value = null, [
                        'id' => 'contact_no2',
                        'placeholder' => 'Enter contact number 2',
                        'class' => 'form-control',
                    ]) !!}
                @endif
                <span class="text-danger">{{ $errors->first('contact_no2') }}</span>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-form-label col-lg-3">Address 1:</label>
        <div class="col-lg-3 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-location4"></i></span>
                </span>
                @if ($is_edit)
                    {!! Form::text('address1', $setting->address1, [
                        'id' => 'address1',
                        'placeholder' => 'Enter Address 1',
                        'class' => 'form-control',
                    ]) !!}
                @else
                    {!! Form::text('address1', $value = null, [
                        'id' => 'address1',
                        'placeholder' => 'Enter Address 1',
                        'class' => 'form-control',
                    ]) !!}
                @endif
                <span class="text-danger">{{ $errors->first('address1') }}</span>
            </div>
        </div>
        <label class="col-form-label col-lg-3">Address 2:</label>
        <div class="col-lg-3 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-location4"></i></span>
                </span>
                @if ($is_edit)
                    {!! Form::text('address2', $setting->address2, [
                        'id' => 'address2',
                        'placeholder' => 'Enter Address 2',
                        'class' => 'form-control',
                    ]) !!}
                @else
                    {!! Form::text('address2', $value = null, [
                        'id' => 'address2',
                        'placeholder' => 'Enter Address 2',
                        'class' => 'form-control',
                    ]) !!}
                @endif
                <span class="text-danger">{{ $errors->first('address2') }}</span>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-form-label col-lg-3">Company Email:</label>
        <div class="col-lg-3 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-envelop5"></i></span>
                </span>
                @if ($is_edit)
                    {!! Form::text('company_email', $setting->company_email, [
                        'id' => 'company_email',
                        'placeholder' => 'Enter Company Email',
                        'class' => 'form-control',
                    ]) !!}
                @else
                    {!! Form::text('company_email', $value = null, [
                        'id' => 'company_email',
                        'placeholder' => 'Enter Company Email',
                        'class' => 'form-control',
                    ]) !!}
                @endif
                <span class="text-danger">{{ $errors->first('company_email') }}</span>
            </div>
        </div>
        <label class="col-form-label col-lg-3">Instagram URL:</label>
        <div class="col-lg-3 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-instagram"></i></span>
                </span>
                @if ($is_edit)
                    {!! Form::text('instagram_link', $setting->instagram_link, [
                        'id' => 'instagram_link',
                        'placeholder' => 'Enter Instagram URL',
                        'class' => 'form-control',
                    ]) !!}
                @else
                    {!! Form::text('instagram_link', $value = null, [
                        'id' => 'instagram_link',
                        'placeholder' => 'Enter Instagram URL',
                        'class' => 'form-control',
                    ]) !!}
                @endif
                <span class="text-danger">{{ $errors->first('instagram_link') }}</span>
            </div>
        </div>
    </div>


    <div class="form-group row">

        <label class="col-form-label col-lg-3">Facebook URL:</label>
        <div class="col-lg-3 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-facebook2"></i></span>
                </span>
                @if ($is_edit)
                    {!! Form::text('facebook_link', $setting->facebook_link, [
                        'id' => 'facebook_link',
                        'placeholder' => 'Enter Facebook URL',
                        'class' => 'form-control',
                    ]) !!}
                @else
                    {!! Form::text('facebook_link', $value = null, [
                        'id' => 'facebook_link',
                        'placeholder' => 'Enter Facebook URL',
                        'class' => 'form-control',
                    ]) !!}
                @endif
                <span class="text-danger">{{ $errors->first('facebook_link') }}</span>
            </div>
        </div>
        <label class="col-form-label col-lg-3">LinkedIn URL:</label>
        <div class="col-lg-3 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-linkedin"></i></span>
                </span>
                @if ($is_edit)
                    {!! Form::text('linkin_link', $setting->linkin_link, [
                        'id' => 'linkin_link',
                        'placeholder' => 'Enter LinkedIn URL',
                        'class' => 'form-control',
                    ]) !!}
                @else
                    {!! Form::text('linkin_link', $value = null, [
                        'id' => 'linkin_link',
                        'placeholder' => 'Enter LinkedIn URL',
                        'class' => 'form-control',
                    ]) !!}
                @endif
                <span class="text-danger">{{ $errors->first('linkin_link') }}</span>
            </div>
        </div>
    </div>

    <div class="form-group row">

        <label class="col-form-label col-lg-3">Twitter URL:</label>
        <div class="col-lg-3 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-twitter"></i></span>
                </span>
                @if ($is_edit)
                    {!! Form::text('twitter_link', $setting->twitter_link, [
                        'id' => 'twitter_link',
                        'placeholder' => 'Enter Twitter URL',
                        'class' => 'form-control',
                    ]) !!}
                @else
                    {!! Form::text('twitter_link', $value = null, [
                        'id' => 'twitter_link',
                        'placeholder' => 'Enter Twitter URL',
                        'class' => 'form-control',
                    ]) !!}
                @endif
                <span class="text-danger">{{ $errors->first('twitter_link') }}</span>
            </div>
        </div>
        <label class="col-form-label col-lg-3">Youtube URL:</label>
        <div class="col-lg-3 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-play"></i></span>
                </span>
                @if ($is_edit)
                    {!! Form::text('youtube_link', $setting->youtube_link, [
                        'id' => 'youtube_link',
                        'placeholder' => 'Enter Youtube URL',
                        'class' => 'form-control',
                    ]) !!}
                @else
                    {!! Form::text('youtube_link', $value = null, [
                        'id' => 'youtube_link',
                        'placeholder' => 'Enter Youtube URL',
                        'class' => 'form-control',
                    ]) !!}
                @endif
                <span class="text-danger">{{ $errors->first('youtube_link') }}</span>
            </div>
        </div>
    </div>

</fieldset>
