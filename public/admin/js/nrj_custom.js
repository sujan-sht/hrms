/* ------------------------------------------------------------------------------
 *
 *  @author Er. Niraj Thike
 *  @link thikeniraj.com.np
 *  @version v1.0.0
 *
 * ---------------------------------------------------------------------------- */

$(document).ready(function () {

    filterEmployeeAndLeaveTypeByOrganization();
    filterAlternativeEmployeeByEmployee();
    // filterBranchByOrganization();
    // filterBranchDepartmentLevelDesignationByOrganization();
    // filterUserListExceptEmployeeRoleByOrganization();

    // initialize select2
    $('.select-filter').select2({
        placeholder: 'Select',
        allowClear: true
    });

    // disable button once clicked
    $('input[type=submit], button[type=submit]').click(function () {
        var form = $(this).parents('form:first');
        if (form.valid()) {
            $(this).attr('disabled', true);
            $(this).prepend('<i class="icon-spinner spinner mr-2"></i>');
            form.submit();
        }
    });

    // popup for delete confirmation
    $('.confirmDelete').on('click', function () {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleted!',
                    text: 'Your file has been deleted.',
                    icon: 'success',
                    showCancelButton: false,
                    showConfirmButton: false,
                });
                window.location.href = $(this).attr('link');
            }
        });
    });
    //nepali single date picker
    // window.onload = function() {
    //     var input = document.getElementsByClassName("nepali-daterange-single");
    //     input.nepaliDatePicker({
    //         ndpYear: true,
    //         ndpMonth: true,
    //         ndpYearCount: 10
    //     });
    // };


    $('.organization-filter').on('change', function () {
        filterEmployeeAndLeaveTypeByOrganization();
    });

    $('.organization-filter-confirmation').on('change', function () {
        filterEmployeeBasedOnConfirmation();
    });
    function filterEmployeeBasedOnConfirmation() {
        var organizationId = $('. organization-filter-confirmation').val();
        var employeeId = $('.employee-filter').val();
        $.ajax({
            type: 'GET',
            url: '/admin/organization/get-confirmed-employees',
            data: {
                organization_id: organizationId,
            },
            success: function (data) {
                var list = JSON.parse(data);
                var options = '';

                options += "<option value=''>Select Employee</option>";
                $.each(list, function (id, value) {
                    options += "<option value='" + id + "'  >" + value + "</option>";
                });

                $('.employee-filter').html(options);
                $('.employee-filter').select2();


                if (employeeId) {
                    $('.employee-filter').val(employeeId).select2();
                }
            }
        });

    }

    $('.organizationWisePermanentEmployeeFetch').on('change', function () {
        filterPermanentEmployeeByOrganization();
    });


    function filterPermanentEmployeeByOrganization() {
        var organizationId = $('.organizationWisePermanentEmployeeFetch').val();
        var employeeId = $('.employee-filter').val();
        if (!organizationId) return false;
        $.ajax({
            type: 'GET',
            url: '/admin/organization/get-permanent-employees',
            data: {
                organization_id: organizationId,
            },
            success: function (data) {
                var list = JSON.parse(data);
                var options = '';

                options += "<option value=''>Select Permanent Employee</option>";
                $.each(list, function (id, value) {
                    options += "<option value='" + id + "'  >" + value + "</option>";
                });

                $('.employee-filter').html(options);
                $('.employee-filter').select2();


                if (employeeId) {
                    $('.employee-filter').val(employeeId).select2();
                }
            }
        });
    }


    $('.organization-filter2').on('change', function () {
        // filterBranchByOrganization();
        filterBranchDepartmentLevelDesignationByOrganization();
    });

    $('.employee-filter').on('change', function () {
        filterAlternativeEmployeeByEmployee();
    })

    // $('.organization-filter3').on('change', function () {
    //     filterUserListExceptEmployeeRoleByOrganization();
    // });

    /**
     *
     */
    // function filterBranchByOrganization()
    // {
    //     var organizationId = $('.organization-filter2').val();
    //     var branchId = $('.branch-filter').val();
    //     if(!organizationId) return false;
    //     $.ajax({
    //         type: 'GET',
    //         url: '/admin/organization/get-branches',
    //         data: {
    //             organization_id : organizationId
    //         },
    //         success: function(data) {
    //             var list = JSON.parse(data);
    //             var options = '';

    //             options += "<option value=''>Select Branch</option>";
    //             $.each(list, function(id, value){
    //                 options += "<option value='" + id + "'>" + value + "</option>";
    //             });

    //             $('.branch-filter').html(options);
    //             $('.branch-filter').select2({
    //                 placeholder: "Select Branch"
    //             });

    //             if(branchId) {
    //                 $('.branch-filter').val(branchId).select2();
    //             }
    //         }
    //     });
    // }

    /**
     *
     */
    function filterEmployeeAndLeaveTypeByOrganization() {

        var organizationId = $('.organization-filter').val();
        var fiscalYearId = $('.fiscal_year_id').val();

        var employeeId = $('.employee-filter').val();


        var leaveTypeId = $('.leave-type-filter').val();
        var unpaidLeaveTypeId = $('.unpaid-leave-type-filter').val();

        if (!organizationId) {
            return false;
        }
        $.ajax({
            type: 'GET',
            url: '/admin/organization/get-employees',
            data: {
                organization_id: organizationId,
            },
            success: function (data) {
                console.log('===================', data);


                var list = JSON.parse(data);
                var options = '';

                options += "<option value=''>Select Employee</option>";
                $.each(list, function (id, value) {
                    options += "<option value='" + id + "'  >" + value + "</option>";
                });

                $('.employee-filter').html(options);
                $('.employee-filter').select2();


                if (employeeId) {
                    $('.employee-filter').val(employeeId).select2();
                }
            }
        });

        $.ajax({
            type: 'GET',
            url: '/admin/organization/get-leave-types',
            data: {
                organization_id: organizationId,
                fiscal_year_id: fiscalYearId,
            },
            success: function (data) {
                var list = JSON.parse(data);
                var options = '';

                options += "<option value=''>Select Leave Type</option>";
                $.each(list, function (id, value) {
                    options += "<option value='" + id + "'>" + value + "</option>";
                });

                $('.leave-type-filter').html(options);
                $('.leave-type-filter').select2();

                if (leaveTypeId) {
                    $('.leave-type-filter').val(leaveTypeId).select2();
                }
            }
        });

        $.ajax({
            type: 'GET',
            url: '/admin/organization/get-unpaid-leave-types',
            data: {
                organization_id: organizationId,
                fiscal_year_id: fiscalYearId,
            },
            success: function (data) {
                var list = JSON.parse(data);
                var options = '';

                options += "<option value=''>Select Leave Type</option>";
                $.each(list, function (id, value) {
                    options += "<option value='" + id + "'>" + value + "</option>";
                });

                $('.unpaid-leave-type-filter').html(options);
                $('.unpaid-leave-type-filter').select2();

                if (unpaidLeaveTypeId) {
                    $('.unpaid-leave-type-filter').val(unpaidLeaveTypeId).select2();
                }
            }
        });
    }

    /**
     *
     */
    function filterAlternativeEmployeeByEmployee() {
        var employeeId = $('.employee-filter').val();
        var alternativeEmployeeId = $('.alt-employee-filter').val();
        if (!employeeId) return false;
        $.ajax({
            type: 'GET',
            url: '/admin/employee/get-alternative-employees',
            data: {
                employee_id: employeeId
            },
            success: function (data) {
                var list = JSON.parse(data);
                var options = '';

                options += "<option value=''>Select Employee</option>";
                $.each(list, function (id, value) {
                    options += "<option value='" + id + "'>" + value + "</option>";
                });

                $('.alt-employee-filter').html(options);
                $('.alt-employee-filter').select2();

                if (alternativeEmployeeId) {
                    $('.alt-employee-filter').val(alternativeEmployeeId).select2();
                }
            }
        });
    }



    function filterBranchDepartmentLevelDesignationByOrganization() {
        var organizationId = $('.organization-filter2').val()
        var branchId = $('.branch-filter').val()
        var departmentId = $('.department-filter').val()
        var designationId = $('.designation-filter').val()
        var levelId = $('.level-filter').val()
        if (!organizationId) return false

        //Branches
        $.ajax({
            type: 'GET',
            url: '/admin/organization/get-branches',
            data: {
                organization_id: organizationId
            },
            success: function (data) {
                var list = JSON.parse(data);
                var options = '';

                options += "<option value=''>Select Branch</option>";
                $.each(list, function (id, value) {
                    options += "<option value='" + id + "'>" + value + "</option>"
                });

                $('.branch-filter').html(options);
                $('.branch-filter').select2({
                    placeholder: "Select Branch"
                });

                if (branchId) {
                    $('.branch-filter').val(branchId).select2()
                }
            }
        })
        //

        //Departments
        $.ajax({
            type: 'GET',
            url: '/admin/organization/get-departments',
            data: {
                organization_id: organizationId
            },
            success: function (data) {
                var list = JSON.parse(data)
                var options = ''

                options += "<option value=''>Select Department</option>"
                $.each(list, function (id, value) {
                    options += "<option value='" + id + "'>" + value + "</option>"
                })

                $('.department-filter').html(options)
                $('.department-filter').select2({
                    placeholder: "Select Department"
                });

                if (departmentId) {
                    $('.department-filter').val(departmentId).select2()
                }
            }
        });
        //

        //Designations
        $.ajax({
            type: 'GET',
            url: '/admin/organization/get-designations',
            data: {
                organization_id: organizationId
            },
            success: function (data) {
                var list = JSON.parse(data)
                var options = ''

                options += "<option value=''>Select Designation</option>"
                $.each(list, function (id, value) {
                    options += "<option value='" + id + "'>" + value + "</option>"
                })

                $('.designation-filter').html(options)
                $('.designation-filter').select2({
                    placeholder: "Select Designation"
                });


                if (designationId) {
                    $('.designation-filter').val(designationId).select2()
                }
            }
        });
        //

        //Levels
        $.ajax({
            type: 'GET',
            url: '/admin/organization/get-levels',
            data: {
                organization_id: organizationId
            },
            success: function (data) {
                var list = JSON.parse(data)
                var options = ''

                options += "<option value=''>Select Level</option>"
                $.each(list, function (id, value) {
                    options += "<option value='" + id + "'>" + value + "</option>"
                })

                $('.level-filter').html(options)
                $('.level-filter').select2({
                    placeholder: "Select Level"
                });

                if (levelId) {
                    $('.level-filter').val(levelId).select2()
                }
            }
        });
        //
    }

    //get designation-organization filtered data of level list
    $('.designation-filter').on('change', function () {
        var designationId = $(this).val()
        var organizationId = $('.organization-filter2').val()
        var levelId = $('.level-filter').val()

        $.ajax({
            type: 'GET',
            url: '/admin/organization/get-levels-from-designation',
            data: {
                organization_id: organizationId,
                designation_id: designationId
            },
            success: function (data) {
                var list = JSON.parse(data)

                var options = ''

                // options += "<option value=''>Select Level</option>"
                $.each(list, function (id, value) {
                    options += "<option value='" + id + "'>" + value + "</option>"
                })

                $('.level-filter').html(options)
                $('.level-filter').select2({
                    placeholder: "Select Level"
                });
                if (levelId) {
                    $('.level-filter').val(levelId).select2()
                }
                getEmployees()
            },
            error: function (xhr, status, error) {
                console.error("Error fetching levels: ", error);
            }
        });
    })



    // get employees from the org,branch,department and designation list
    const getEmployees = () => {
        var orgId = $("#organization_id").val();
        var branchId = $("#branch_id").val();
        var departmentId = $("#department_id").val();
        var designationId = $("#designation_id").val();
        $.ajax({
            type: 'GET',
            url: "/admin/get-employees/from/allSetParams",
            data: {
                organization_id: orgId,
                branch_id: branchId,
                department_id: departmentId,
                designation_id: designationId
            },
            success: function (data) {
                var options = ''
                $.each(data, function (id, value) {
                    options += "<option value='" + id + "'>" + value + "</option>"
                })

                $('.attachFilterEmployees').html(options)
                $('.attachFilterEmployees').select2({
                    placeholder: "Select Employee"
                });
            },
            error: function (xhr, status, error) {
                console.error("Error fetching levels: ", error);
            }
        });
    }

})
