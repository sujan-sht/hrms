$(document).ready(function() {
    var award = $('#award_project').val();
    if (award == '1') {
        $('#project_award_modal').modal('show');
    }

    $('#update-award-status').click( function () {
        var project_id = $('input[name=project_id]').val();
        $.ajax({
            url: "/admin/projectBid/updateAwardStatus?project_id="+project_id,
            method: "get",
            success:function (response) {
                console.log(response);
            }
        });
    });
});

$(document).ready(function() {
    $(document).on('click', '.pmt_dashboard', function() {
        var assigned_user = $('.total_assign_employee').val();
        if (assigned_user == 0) {
            $('#modal_error_show').modal('show');
            return false;
        }
    });

    $(document).on('click', '.check_workschedule_data', function() {
        var exist_worksheet = $('#exist_worksheet').val();

        if (exist_worksheet == 0) {
            $('#modal_error_worksheet').modal('show');
            return false;
        }
    });

    $(document).on('click', '.check_boq_data', function() {
        var exist_boq = $('#exist_boq').val();

        if (exist_boq == 0) {
            $('#modal_error_boq').modal('show');
            return false;
        }
    });

    $('.edit_boq_amount').on('click', function() {
        var total_inc_ps = $('#total_inc_ps').val();
        var total_exc_ps = $('#total_exc_ps').val();
        var discount = $('#discount').val();
        var discount_amount = $('#discount_amount').val();
        var total_aft_discount = $('#total_aft_discount').val();
        var ps_amount= $('#ps_amount').val();
        var total_disc_with_ps = $('#total_disc_with_ps').val();
        var vat_amt = $('#vat_amt').val();
        var grand_total = $('#grand_total').val();
        var project_id = $('#project_id').val();

        $('#edit_total_inc_ps').val(total_inc_ps);
        $('#edit_total_exc_ps').val(total_exc_ps);
        $('#edit_discount').val(discount);
        $('#edit_discount_amount').val(discount_amount);
        $('#edit_total_aft_discount').val(total_aft_discount);
        $('#edit_ps_amount').val(ps_amount);
        $('#edit_total_disc_with_ps').val(total_disc_with_ps);
        $('#edit_vat_amt').val(vat_amt);
        $('#edit_grand_total').val(grand_total);
        $('.project_id').val(project_id);    
    });

    $(document).on('keyup','#edit_total_inc_ps,#edit_total_exc_ps,#edit_discount',function(){

        var total_inc_ps = $('#edit_total_inc_ps').val();
        var total_exc_ps = $('#edit_total_exc_ps').val();
        var discount = $('#edit_discount').val();

        var discount_amt = (discount/100) * total_exc_ps;
        var total_aft_discount = total_exc_ps - discount_amt;
        var ps_amount = total_inc_ps - total_exc_ps;
        var total_disc_with_ps = total_aft_discount + ps_amount;
        var vat_amt = (13/100) * total_disc_with_ps;
        var grand_total = total_disc_with_ps + vat_amt;


        $('#edit_discount_amount').val(discount_amt);
        $('#edit_total_aft_discount').val(total_aft_discount);
        $('#edit_ps_amount').val(ps_amount);
        $('#edit_total_disc_with_ps').val(total_disc_with_ps);
        $('#edit_vat_amt').val(vat_amt);
        $('#edit_grand_total').val(grand_total);

    });

    $('#check-all-employee').change(function () {
        if ($('#check-all-employee').is(':checked')) {
            $('input[name="checkbox[]"]').prop('checked', true);
        } else {
            $('input[name="checkbox[]"]').prop('checked', false);
        }
    });

    $('#assign-equipment-to-project').click( function (e) {
        var val = $('input[name=assign_new_equipment]').val();

        var equipment = $('#project-assigned-equipments option').filter(function() {
            return this.value == val;
        }).data('id');
        var link = $(this).attr('link');
        var project = $(this).attr('data-project');
        if (equipment > 0) {
            $.ajax({
                url: link+"?bid_id="+project,
                data: {
                    equipment: equipment
                },
                method: "post",
                success:function (response) {
                    $('.assigned-equipment-table-body').empty();
                    $('.assigned-equipment-table-body').append(response);
                }
            });
        } else{
            $(this).parents('.form-group').append('<span class="text-danger equipment-validation">Please Select Equipment</span>');
            $('.equipment-validation').delay(3000).fadeOut();
        }
    });

    $('#assign-vehicle-to-project').click( function (e) {
        var val = $('input[name=assign_new_vehicle]').val();

        var vehicle = $('#project-assigned-vehicles option').filter(function() {
            return this.value == val;
        }).data('id');
        var project = $(this).attr('data-project');
        var link = $(this).attr('link')+"?bid_id="+project;
        if (vehicle > 0) {
            $.ajax({
                url: link,
                data: {
                    vehicle: vehicle
                },
                method: "post",
                success:function (res) {
                    $('.assigned-vehicle-table-body').empty();
                    $('.assigned-vehicle-table-body').append(res);
                }
            });
        } else{
            $(this).parents('.form-group').append('<span class="text-danger vehicle-validation">Please Select Vehicle</span>');
            $('.vehicle-validation').delay(3000).fadeOut();
        }
    });


    $('#rawmaterial_submit').submit(function (e) {
        e.preventDefault();

        $("#rawmaterial_submit").validate({
            rules: {
                project_boq_id: "required",
                material_name:"required",
                rate: {
                    number: true
                }
            },
            messages: {
                project_boq_id: "Select Project Boq",
                material_name:"Enter Material Name",
                rate: "Enter Valid Rate"
            }
        });

        if ($('#rawmaterial_submit').valid()) {
            var data = $(this).serialize();
            var link = $(this).attr('action');
            $.ajax({
                url: link,
                data: data,
                method: 'POST',
                success:function (res) {
                    if (res == 'success') {
                        $('#rawmaterial_submit').trigger('reset');
                        $('#rawmaterial_submit').append('<span class="text-success raw-material-validation">Raw Material Saved Successfully.</span>');
                        $('.raw-material-validation').delay(3000).fadeOut();
                    }
                }
            });
        }
    });

    $('#raw-material-select-search').change( function (e) {
        e.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            url: '/admin/ProjectDashboard/projectboq/rawmaterial/getHtml',
            data: data,
            method: 'post',
            success:function (res) {
                $('.assigned-raw-material-table-body').empty();
                $('.assigned-raw-material-table-body').append(res);
            }
        });
    });
});

function selectAllCheckBoxes(FormName, FieldName, CheckValue) {
    if (!document.forms[FormName])
        return;
    var objCheckBoxes = document.forms[FormName].elements[FieldName];
    if (!objCheckBoxes)
        return;
    var countCheckBoxes = objCheckBoxes.length;
    if (!countCheckBoxes)
        objCheckBoxes.checked = CheckValue;
    else
// set the check value for all check boxes
for (var i = 0; i < countCheckBoxes; i++)
    objCheckBoxes[i].checked = CheckValue;
}

