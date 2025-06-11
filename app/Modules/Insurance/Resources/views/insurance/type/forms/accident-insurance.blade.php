 <div class="card-body accident-insurance-form" style="display: none;">
     <legend class="text-uppercase font-size-sm font-weight-bold accident-insurance"></legend>
     <div class="form-group row">
         <div class="col-lg-6 mb-3">
             <div class="row">
                 <label class="col-form-label col-lg-2">Company Name : </label>
                 <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                     <div class="input-group">
                         {!! Form::text('accident_company_name', @$insurance->company_name, [
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
                 <label class="col-form-label col-lg-2">Assured Amount: </label>
                 <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                     <div class="input-group">
                         {!! Form::number('accident_sum_assured_amount', @$insurance->sum_assured_amount, [
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
                                 {!! Form::text('accident_policy_start_date', @$insurance->policy_start_date, [
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
                                 {!! Form::text('accident_policy_end_date', @$insurance->policy_end_date, [
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
                         {!! Form::number('accident_premium_amount', @$insurance->premium_amount, [
                             'rows' => 5,
                             'placeholder' => 'Enter Premium Amount',
                             'class' => 'form-control',
                         ]) !!}
                     </div>

                 </div>
             </div>
         </div>
     </div>
 </div>
