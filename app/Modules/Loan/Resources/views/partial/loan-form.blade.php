<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Loan Type<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12 ml-4">

                                {!! Form::select('loan_type', $data['loanTypes'], @$loan->loan_type ?? null, [
                                    'id' => 'loan_type',
                                    'class' => 'form-control select-search',
                                    'placeholder' => 'Select Loan Type',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('loan::application-form.staff-home-loan-layout')

            {{-- <div class="mb-2 text-right mr-2">
                <a href="{{ route('loan.index') }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                            class="icon-backward2"></i></b>Go Back</a>

                <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                            class="icon-database-insert"></i></b>Save</button>
            </div> --}}

            <!-- Navigation Buttons -->
        </div>

    </div>

</div>

<script>
    $(document).ready(function() {
        // edit
        let selectedText = $('#loan_type option:selected').text();
        console.log(selectedText);

        if (selectedText != '' || selectedText != null) {
            if (selectedText == 'Home Loan') {
                $('.staff-home-form').show();
            }
        }
        // on change
        $("#loan_type").on('change', function() {
            var value = $(this).val();
            if (value != '' || value != null) {

                switch (value) {
                    case 'Home Loan':
                        $('.staff-home-form').show();
                        $('.staff-vehicle-form').hide();

                        break;
                    case 'Vehicle Loan':
                        $('.staff-vehicle-form').show();
                        $('.staff-home-form').hide();
                        break;
                    default:
                        break;
                }


                // $.ajax({
                //     type: "GET",
                //     url: "{{ route('insurance.type.show') }}",
                //     data: {
                //         "_token": "{{ csrf_token() }}",
                //         "id": value,
                //     },
                //     success: function(response) {
                //         if (response.status) {
                //             switch (response.data.title) {
                //                 case 'Life Insurance':
                //                     console.log('life insurance');
                //                     $('.medical-insurance-form').hide();
                //                     $('.accident-insurance-form').hide();
                //                     $('.life-insurance-form').show();
                //                     $('.life-insurance').text(response.data.title);
                //                     break;
                //                 case 'Medical Insurance':
                //                     console.log('medical insurance');
                //                     $('.life-insurance-form').hide();
                //                     $('.accident-insurance-form').hide();
                //                     $('.medical-insurance-form').show();
                //                     $('.medical-insurance').text(response.data.title);
                //                     break;
                //                 case 'Accident Insurance':
                //                     console.log('accident insurance');
                //                     $('.accident-insurance-form').show();
                //                     $('.accident-insurance').text(response.data.title);
                //                     $('.life-insurance-form').hide();
                //                     $('.medical-insurance-form').hide();
                //                     break;
                //                 default:
                //                     alert(
                //                         'Insurance Type not found. I have only [Life Insurance, Medical Insurance, Accident Insurance]'
                //                     );
                //                     break;
                //             }
                //         }
                //     }
                // });
            }

        });


        // Disable clicking on nav-tabs directly
        $('.nav-tabs .nav-link').click(function(e) {
            e.preventDefault(); // Prevent the default click behavior
            e.stopPropagation(); // Stop the event from propagating
            console.log('Direct tab navigation is disabled. Use the Next or Previous buttons.');
        });

        function updateButtonText() {
            var currentTab = $('.nav-tabs .nav-link.active');
            var nextTab = currentTab.closest('button').next();
            var prevTab = currentTab.closest('button').prev();

            if (nextTab.length === 0) {
                // If it's the last tab, change "Next" to "Submit"
                $('.next-tab').html("Submit");
            } else {
                // If not the last tab, ensure the text is "Next"
                $('.next-tab').html('<i class="icon-forward3"></i> Next');
            }

            if (prevTab.length === 0) {
                // If it's the first tab, disable "Previous" or handle it differently
                $('.prev-tab').html(
                    "<i class='icon-backward2'></i> Back"); // Optional: Add custom text for the first tab
            } else {
                $('.prev-tab').html("<i class='icon-backward2'></i> Previous");
            }
        }

        // On "Next" button click
        $('.next-tab').click(function(e) {
            e.preventDefault();
            var currentTab = $('.nav-tabs .nav-link.active');
            var nextTab = currentTab.closest('button').next(); // Find next tab

            if (nextTab.length > 0) {
                currentTab.removeClass('active');
                currentTab.attr('aria-selected', 'false');

                nextTab.addClass('active');
                nextTab.attr('aria-selected', 'true');

                $('.tab-pane.active').removeClass('show active');
                $(nextTab.data('target')).addClass('show active');

                updateButtonText();
            } else {
                // Handle submit action when on the last tab
                console.log('Submit form');
                $('#staff-loan')
                    .submit(); // Ensure your form has a `submit` handler or replace with custom logic
            }
        });

        // On "Previous" button click
        $('.prev-tab').click(function(e) {
            e.preventDefault();

            var currentTab = $('.nav-tabs .nav-link.active');
            var prevTab = currentTab.closest('button').prev(); // Find previous tab

            if (prevTab.length > 0) {
                currentTab.removeClass('active');
                currentTab.attr('aria-selected', 'false');

                prevTab.addClass('active');
                prevTab.attr('aria-selected', 'true');

                $('.tab-pane.active').removeClass('show active');
                $(prevTab.data('target')).addClass('show active');

                updateButtonText();
            } else {
                // Handle redirection when no previous tab
                console.log('Redirecting to previous page');
                window.location.href =
                    "{{ route('loan.index') }}"; // Replace with your desired route URL
            }
        });

        // Call this function initially to set the correct text
        updateButtonText();



    });
</script>
