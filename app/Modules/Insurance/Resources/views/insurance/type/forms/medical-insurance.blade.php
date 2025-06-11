 <div class="card-body medical-insurance-form" style="display: none;">
     <legend class="text-uppercase font-size-sm font-weight-bold medical-insurance"></legend>
     <div class="form-group row">
         <div class="col-lg-6 mb-3">
             <div class="row">
                 <label class="col-form-label col-lg-2">Company Name : </label>
                 <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                     <div class="input-group">
                         {!! Form::text('medical_company_name', @$insurance->company_name, [
                             'rows' => 5,
                             'placeholder' => 'Enter Company Name',
                             'class' => 'form-control',
                         ]) !!}
                     </div>
                 </div>
             </div>
         </div>
         <div class="col-lg-6 mb-3">
             <div class="row">
                 <label class="col-form-label col-lg-2">Sum Assured Amount: </label>
                 <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                     <div class="input-group">
                         {!! Form::number('medical_sum_assured_amount', @$insurance->sum_assured_amount, [
                             'rows' => 5,
                             'placeholder' => 'Enter Sum Assured Amount',
                             'class' => 'form-control',
                         ]) !!}
                     </div>
                 </div>
             </div>
         </div>

     </div>
     <div class="form-group row">
         <div class="col-lg-12 mb-3">
             <div class="row">
                 <div class="col-lg-6">
                     <div class="row">
                         <label class="col-form-label col-lg-2">Policy Start Date : </label>
                         <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                             <div class="input-group">
                                 {!! Form::text('medical_policy_start_date', @$insurance->policy_start_date, [
                                     'rows' => 5,
                                     'placeholder' => 'Enter Policy Start Date',
                                     'class' => 'form-control nepali-calendar',
                                 ]) !!}
                             </div>

                         </div>
                     </div>
                 </div>
                 <div class="col-lg-6">
                     <div class="row">
                         <label class="col-form-label col-lg-2">Last Installment Date : </label>
                         <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                             <div class="input-group">
                                 {!! Form::text('medical_policy_end_date', @$insurance->policy_end_date, [
                                     'rows' => 5,
                                     'placeholder' => 'Enter Policy End Date',
                                     'class' => 'form-control nepali-calendar',
                                 ]) !!}
                             </div>

                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>

     <div class="form-group row">
         <div class="col-lg-6 mb-3">
             <div class="row">
                 <label class="col-form-label col-lg-2">Premium Amount: </label>
                 <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                     <div class="input-group">
                         {!! Form::number('medical_premium_amount', @$insurance->premium_amount, [
                             'rows' => 5,
                             'placeholder' => 'Enter Premium Amount',
                             'class' => 'form-control',
                         ]) !!}
                     </div>

                 </div>
             </div>
         </div>
         <div class="col-lg-6 mb-3">
             <div class="row">
                 <label class="col-form-label col-lg-2">Premium Payment By: </label>
                 <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                     <div class="input-group">
                         <input type="radio" name="medical_premium_payment_by" id="medical_premium_payment_by"
                             value="self" {{ @$insurance->premium_payment_by == 'self' ? 'checked' : '' }}>
                         <span style="margin: 0.5rem">Self</span>
                         <input type="radio" name="medical_premium_payment_by" id="medical_premium_payment_by_company"
                             value="company" {{ @$insurance->premium_payment_by == 'company' ? 'checked' : '' }}>
                         <span style="margin: 0.5rem">Company</span>
                         <input type="radio" name="medical_premium_payment_by" id="medical_premium_payment_by_sharing"
                             value="sharing" {{ @$insurance->premium_payment_by == 'sharing' ? 'checked' : '' }}>
                         <span style="margin: 0.5rem">Sharing</span>
                     </div>
                     @if ($errors->has('policy_maturity_date'))
                         <div class="error text-danger">{{ $errors->first('policy_maturity_date') }}</div>
                     @endif
                 </div>
             </div>
             <div class="row" id="medical_sharing_container" style="display: none;">
                 <div class="d-flex">
                     <div class="col-lg-6">
                         <label class="col-form-label">Employee: </label>
                         <div class="form-group-feedback form-group-feedback-right">
                             <div class="input-group">
                                 {!! Form::number('medical_employees', @$insurance->total_employees, [
                                     'placeholder' => 'Enter Employee Amount',
                                     'class' => 'form-control',
                                 ]) !!}
                             </div>
                         </div>
                     </div>
                     <div class="col-lg-6">
                         <label class="col-form-label ">Employer: </label>
                         <div class="form-group-feedback form-group-feedback-right">
                             <div class="input-group">
                                 {!! Form::number('medical_employer', @$insurance->total_employer, [
                                     'placeholder' => 'Enter Employer Amount',
                                     'class' => 'form-control',
                                 ]) !!}
                             </div>
                         </div>
                     </div>

                 </div>
             </div>
         </div>
     </div>
 </div>
