<div id="modal_default_import" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-grey">
                <h5 class="modal-title text-light btn-slate ">Employee Education</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                                @forelse($education_details as $key => $item)
                                    <ul class="media-list">
                                        <li class="media mt-2">
                                            <span class="font-weight-semibold">Type of Institution :</span>
                                            <div class="ml-auto">{{ @$item['type_of_institution'] ?? null }}</div>
                                        </li>
                                        <li class="media mt-2">
                                            <span class="font-weight-semibold">Institution Name :</span>
                                            <div class="ml-auto">{{ @$item['institution_name'] ?? null }}</div>
                                        </li>

                                        <li class="media mt-2">
                                            <span class="font-weight-semibold">Passed Year :</span>
                                            <div class="ml-auto">{{ @$item['passed_year'] ?? null }}</div>
                                        </li>

                                        <li class="media mt-2">
                                            <span class="font-weight-semibold">Level :</span>
                                            <div class="ml-auto">{{ @$item['level'] ?? null }}</div>
                                        </li>


                                    </ul>
                                @empty
                                    <tr>
                                        <td colspan="5">No Education Details Found !!!</td>
                                    </tr>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#employee-form').validate({
            rules: {
                upload_employee: 'required'
            },
            messages: {
                upload_employee: "Please Select A File."
            },
            errorElement: "em",
            errorPlacement: function(error, element) {
                console.log(element)
                // Add the `help-block` class to the error element
                error.addClass("help-block");

                // Add `has-feedback` class to the parent div.form-group
                // in order to add icons to inputs
                element.parents(".col-lg-9").addClass("form-group-feedback");

                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent("label"));
                } else {
                    error.insertAfter(element.parent());
                }

                // Add the span element, if doesn't exists, and apply the icon classes to it.
                if (!element.parent().parent().next("div")[0]) {
                    $("<div class='form-control-feedback'><i class='icon-cross2 text-danger'></i></div>")
                        .insertAfter(element);
                }
            },
            success: function(label, element) {
                console.log(element);
                // Add the span element, if doesn't exists, and apply the icon classes to it.
                if (!$(element).next("div")[0]) {
                    $("<div class='form-control-feedback'><i class='icon-checkmark4 text-success'></i></div>")
                        .insertAfter($(element));
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).parent().find('span .input-group-text').addClass(
                    "alpha-danger text-danger border-danger ").removeClass(
                    "alpha-success text-success border-success");
                $(element).addClass("border-danger").removeClass("border-success");
                $(element).parent().parent().addClass("text-danger").removeClass("text-success");
                $(element).next('div .form-control-feedback').find('i').addClass(
                    "icon-cross2 text-danger").removeClass("icon-checkmark4 text-success");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parent().find('span .input-group-text').addClass(
                    "alpha-success text-success border-success").removeClass(
                    "alpha-danger text-danger border-danger ");
                $(element).addClass("border-success").removeClass("border-danger");
                $(element).parent().parent().addClass("text-success").removeClass("border-danger");
                $(element).next('div .form-control-feedback').find('i').addClass(
                    "icon-checkmark4 text-success").removeClass("icon-cross2 text-danger");
            }
        });
    });
</script>
