/* ------------------------------------------------------------------------------
 *
 *  # Sweet Alert component
 *
 *  Demo JS code for extra_sweetalert.html page
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var SweetAlert = function () {


    //
    // Setup module components
    //

    // Sweet Alerts
    var _componentSweetAlert = function() {
        if (typeof swal == 'undefined') {
            console.warn('Warning - sweet_alert.min.js is not loaded.');
            return;
        }

        // Defaults
        var swalInit = swal.mixin({
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-light',
                    denyButton: 'btn btn-light',
                    input: 'form-control'
                }
            });


        //
        // Basic options
        //

        // Basic
        $('#sweet_basic').on('click', function() {
            swalInit.fire({
                title: 'Here is a message!'
            });
        });

        // With title
        $('#sweet_title_text').on('click', function() {
            swalInit.fire({
                title: 'Here\'s a message!',
                text: 'It\'s pretty, isn\'t it?'
            });
        });

        // Close button
        $('#sweet_close').on('click', function() {
            swalInit.fire({
                title: 'Good job!',
                text: 'You clicked the button!',
                icon: 'success',
                showCloseButton: true
            });
        });

        // Custom padding
        $('#sweet_padding').on('click', function() {
            swalInit.fire({
                title: 'Oops...',
                text: 'Something went wrong!',
                icon: 'error',
                padding: 40
            });
        });

        // Custom width
        $('#sweet_width').on('click', function() {
            swalInit.fire({
                title: 'Got question?',
                text: 'If you have any questions, don\t hesitate to let us know!',
                icon: 'question',
                width: '35%'
            });
        });

        // Auto closing
        $('#sweet_auto_closer').on('click', function() {
            let timerInterval;
            swalInit.fire({
                title: 'Auto close alert!',
                html: 'I will close in <b></b> milliseconds.',
                timer: 2500,
                timerProgressBar: true,
                didOpen: function() {
                    Swal.showLoading()
                    timerInterval = setInterval(function() {
                        const content = Swal.getContent();
                        if (content) {
                            const b = content.querySelector('b')
                            if (b) {
                                b.textContent = Swal.getTimerLeft();
                            }
                        }
                    }, 100);
                },
                willClose: function() {
                    clearInterval(timerInterval)
                }
            }).then(function (result) {
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log('I was closed by the timer')
                }
            });
        });

        // Dynamic queue
        $('#sweet_queue').on('click', function() {
            swalInit.queue([{
                title: 'Your public IP',
                confirmButtonText: 'Show my public IP',
                text: 'Your public IP will be received ' + 'via AJAX request',
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return fetch('https://api.ipify.org?format=json')
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(data) {
                            return swal.insertQueueStep(data.ip);
                        })
                        .catch(function() {
                            swalInit.insertQueueStep({
                                icon: 'error',
                                title: 'Unable to get your public IP'
                            });
                        });
                }
            }]);
        });

        // AJAX requests
        $('#sweet_ajax').on('click', function() {
            swalInit.fire({
                title: 'Submit your Github username',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                inputPlaceholder: 'Enter Github username',
                showCancelButton: true,
                confirmButtonText: 'Look up',
                showLoaderOnConfirm: true,
                preConfirm: function(login) {
                    return fetch('https://api.github.com/users/' + login)
                        .then(function(response) {
                            if(!response.ok) {
                                throw new Error(response.statusText)
                            }
                            return response.json();
                        })
                        .catch(function(error) {
                            swalInit.showValidationMessage(
                                'Request failed: ' + error
                            );
                        });
                },
                allowOutsideClick: false
            }).then(function(result) {
                if(result.value) {
                    swalInit.fire({
                        title: result.value.login + '\'s avatar',
                        imageUrl: result.value.avatar_url
                    });
                }
            });
        });

        // HTML message
        $('#sweet_html').on('click', function() {
            swalInit.fire({
                title: '<i>HTML</i> &nbsp; <span class="font-weight-light">example</span>',
                icon: 'info',
                html:
                    'You can use <strong>bold text</strong>, ' +
                    '<a href="//github.com">links</a> ' +
                    'and other HTML tags',
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: '<i class="icon-thumbs-up2 mr-2"></i> Great!',
                confirmButtonAriaLabel: 'Thumbs up, great!',
                cancelButtonText: '<i class="icon-thumbs-down2"></i>',
                cancelButtonAriaLabel: 'Thumbs down'
            });
        });

        // Image
        $('#sweet_image').on('click', function() {
            swalInit.fire({
                title: 'Sweet!',
                text: 'Bootstrap is awesome.',
                imageUrl: 'https://getbootstrap.com/docs/4.1/assets/img/bootstrap-stack.png',
                imageWidth: 260
            });
        });

        // Background image
        $('#sweet_bg').on('click', function() {
            swalInit.fire({
                title: 'Sweet!',
                icon: 'success',
                text: 'Custom backgrounds are awesome.',
                background: '#fff url("https://www.toptal.com/designers/subtlepatterns/patterns/double-bubble-outline.png") repeat'
            });
        });

        // Chaining notifications
        $('#sweet_chain').on('click', function() {
            swalInit.mixin({
                input: 'text',
                confirmButtonText: $('html').attr('dir') == 'rtl' ? 'Next <i class="icon-arrow-left13 ml-2"></i>' : 'Next <i class="icon-arrow-right14 ml-2"></i>',
                showCancelButton: true,
                buttonsStyling: false,
                progressSteps: ['1', '2', '3']
            }).queue([
                {
                    title: 'Question 1',
                    text: 'Step #1 - ask your first question',
                    inputPlaceholder: 'Enter your first question'
                },
                {
                    title: 'Question 2',
                    text: 'Step #2 - ask your second question',
                    inputPlaceholder: 'Enter your second question'
                },
                {
                    title: 'Question 3',
                    text: 'Step #3 - ask your third question',
                    inputPlaceholder: 'Enter your third question'
                }
            ]).then(function(result) {
                if(result.value) {
                    const answers = JSON.stringify(result.value);
                    swalInit.fire({
                        title: 'All done!',
                        html: 'Your answers: <pre class="mt-3">' + answers + '</pre>',
                        confirmButtonText: 'Lovely!'
                    });
                }
            });
        });

        // Reversed buttons
        $('#sweet_reverse_buttons').on('click', function() {
            swalInit.fire({
                title: 'What is your name?',
                input: 'text',
                inputPlaceholder: 'Your name or nickname',
                showCancelButton: true,
                confirmButtonText: 'Submit',
                reverseButtons: true
            });
        });

        // Fullscreen
        $('#sweet_fullscreen').on('click', function() {
            swalInit.fire({
                title: 'Here\'s a message!',
                text: 'It\'s pretty, isn\'t it?',
                icon: 'success',
                showConfirmButton: false,
                showCloseButton: true,
                grow: 'fullscreen'
            });
        });

        // Column grow
        $('#sweet_column').on('click', function() {
            swalInit.fire({
                title: 'Here\'s a message!',
                text: 'It\'s pretty, isn\'t it?',
                icon: 'success',
                showConfirmButton: false,
                showCloseButton: true,
                grow: 'column'
            });
        });

        // Row grow
        $('#sweet_row').on('click', function() {
            swalInit.fire({
                title: 'Here\'s a message!',
                text: 'It\'s pretty, isn\'t it?',
                icon: 'success',
                grow: 'row'
            });
        });

        // Disabled keyboard interactions
        $('#sweet_disabled_keyboard').on('click', function() {
            swalInit.fire({
                title: 'Oops...',
                text: 'Something went wrong!',
                icon: 'error',
                allowEscapeKey: false,
                allowEnterKey: false
            });
        });

        // Disabled animation
        $('#sweet_disabled_animation').on('click', function() {
            swalInit.fire({
                title: 'For your information',
                text: 'This is some sort of a custom alert',
                icon: 'info',
                showClass: {
                    popup: 'swal2-noanimation',
                    backdrop: 'swal2-noanimation'
                },
                hideClass: {
                    popup: '',
                    backdrop: ''
                }
            });
        });

        // Disabled backdrop
        $('#sweet_disabled_backdrop').on('click', function() {
            swalInit.fire({
                title: 'Got question?',
                text: 'You will get the answer soon!',
                icon: 'question',
                allowOutsideClick: false,
                backdrop: false
            });
        });

        // Disabled backdrop
        $('#sweet_disabled_outside_click').on('click', function() {
            swalInit.fire({
                title: 'Oops...',
                text: 'Something went wrong!',
                icon: 'error',
                allowOutsideClick: false
            });
        });


        //
        // Input types
        //

        // Text type
        $('#sweet_text').on('click', function() {
            swalInit.fire({
                title: 'What is your name?',
                input: 'text',
                inputPlaceholder: 'Your name or nickname',
                showCancelButton: true,
                inputValidator: function(value) {
                    return !value && 'You need to write something!'
                }
            }).then(function(result) {
                if(result.value) {
                    swalInit.fire({
                        icon: 'success',
                        html: 'Hi, ' + result.value
                    });
                }
            });
        });

        // Email type
        $('#sweet_email').on('click', function() {
            swalInit.fire({
                title: 'Input email address',
                input: 'email',
                inputPlaceholder: 'Enter your email',
            }).then(function(result) {
                if(result.value) {
                    swalInit.fire({
                        icon: 'success',
                        html: 'Entered email: ' + result.value
                    });
                }
            });
        });

        // URL type
        $('#sweet_url').on('click', function() {
            swalInit.fire({
                title: 'Input URL',
                input: 'url',
                inputPlaceholder: 'Enter URL',
            }).then(function(result) {
                if(result.value) {
                    swalInit.fire({
                        icon: 'success',
                        html: 'Entered URL: ' + result.value
                    });
                }
            });
        });

        // Password type
        $('#sweet_password').on('click', function() {
            swalInit.fire({
                title: 'Enter your password',
                input: 'password',
                inputPlaceholder: 'Enter your password',
                inputAttributes: {
                    'maxlength': 10,
                    'autocapitalize': 'off',
                    'autocorrect': 'off'
                }
            }).then(function(result) {
                if(result.value) {
                    swalInit.fire({
                        icon: 'success',
                        html: 'Entered password: ' + result.value
                    });
                }
            });
        });

        // Textarea type
        $('#sweet_textarea').on('click', function() {
            swalInit.fire({
                title: 'Enter your comment',
                input: 'textarea',
                inputPlaceholder: 'Type your message here',
                showCancelButton: true
            }).then(function(result) {
                if(result.value) {
                    swalInit.fire({
                        title: '<span class="mb-2">Your comment:</span>',
                        text: result.value
                    });
                }
            });
        });

        // Select type
        $('#sweet_select').on('click', function() {
            swalInit.fire({
                title: 'Select Netherlands',
                input: 'select',
                inputOptions: {
                    'DE': 'Germany',
                    'UA': 'Ukraine',
                    'HR': 'Croatia',
                    'NL': 'Netherlands'
                },
                inputPlaceholder: 'Select country',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-light',
                    denyButton: 'btn btn-light',
                    input: 'custom-select'
                },
                showCancelButton: true,
                inputValidator: function(value) {
                    return new Promise(function(resolve) {
                        if(value === 'NL') {
                            resolve();
                        }
                        else {
                            resolve('You need to select Netherlands :)');
                        }
                    });
                }
            }).then(function(result) {
                if(result.value) {
                    swalInit.fire({
                        icon: 'success',
                        html: 'You selected: ' + result.value
                    });
                }
            });
        });

        // Radio type
        $('#sweet_radio').on('click', function() {

            // inputOptions can be an object or Promise
            var inputOptions = new Promise(function(resolve) {
                setTimeout(function() {
                    resolve({
                        '#ff0000': 'Red',
                        '#00ff00': 'Green',
                        '#0000ff': 'Blue'
                    });
                }, 2000)
            });

            // Initialize
            swalInit.fire({
                title: 'Select color',
                input: 'radio',
                inputOptions: inputOptions,
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-light',
                    denyButton: 'btn btn-light'
                },
                inputValidator: function(value) {
                    return !value && 'You need to choose something!'
                }
            }).then(function(result) {
                if(result.value) {
                    swalInit.fire({
                        icon: 'success',
                        html: 'You selected: ' + result.value
                    });
                }
            });
        });

        // Checkbox type
        $('#sweet_checkbox').on('click', function() {
            swalInit.fire({
                title: 'Terms and conditions',
                input: 'checkbox',
                inputValue: 1,
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-light',
                    denyButton: 'btn btn-light'
                },
                inputPlaceholder: 'I agree with the terms and conditions',
                confirmButtonText: 'Continue <i class="icon-arrow-right14 ml-2></i>',
                inputValidator: function(value) {
                    return !value && 'You need to agree with T&C'
                }
            }).then(function(result) {
                if(result.value) {
                    swalInit.fire({
                        icon: 'success',
                        text: 'You agreed with T&C :)'
                    });
                }
            });
        });

        // Range type
        $('#sweet_range').on('click', function() {
            swalInit.fire({
                title: 'How old are you?',
                icon: 'question',
                input: 'range',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-light',
                    denyButton: 'btn btn-light'
                },
                inputAttributes: {
                    min: 8,
                    max: 120,
                    step: 1
                },
                inputValue: 25
            }).then(function(result) {
                if(result.value) {
                    swalInit.fire({
                        icon: 'success',
                        html: 'Your age is ' + result.value
                    });
                }
            });
        });


        //
        // Contextual alerts
        //
        
        // Success alert
        $('#sweet_success').on('click', function() {
            swalInit.fire({
                title: 'Good job!',
                text: 'You clicked the button!',
                icon: 'success'
            });
        });

        // Error alert
        $('#sweet_error').on('click', function() {
            swalInit.fire({
                title: 'Oops...',
                text: 'Something went wrong!',
                icon: 'error'
            });
        });

        // Warning alert
        $('#sweet_warning').on('click', function() {
            swalInit.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this imaginary file!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            });
        });

        // Info alert
        $('#sweet_info').on('click', function() {
            swalInit.fire({
                title: 'For your information',
                text: 'This is some sort of a custom alert',
                icon: 'info'
            });
        });

        // Question
        $('#sweet_question').on('click', function() {
            swalInit.fire({
                title: 'Got question?',
                text: 'You will get the answer soon!',
                icon: 'question'
            });
        });

        // Alert combination
        $('#sweet_combine').on('click', function() {
            swalInit.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                }
            }).then(function(result) {
                if(result.value) {
                    swalInit.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    );
                }
                else if(result.dismiss === swal.DismissReason.cancel) {
                    swalInit.fire(
                        'Cancelled',
                        'Your imaginary file is safe :)',
                        'error'
                    );
                }
            });
        });


        //
        // Positions
        //

        // Top
        $('#sweet_top').on('click', function() {
            swalInit.fire({
                title: 'Good job!',
                text: 'You clicked the button!',
                icon: 'success',
                position: 'top'
            });
        });

        // Top left
        $('#sweet_top_left').on('click', function() {
            swalInit.fire({
                title: 'Good job!',
                text: 'You clicked the button!',
                icon: 'success',
                position: 'top-left'
            });
        });

        // Top right
        $('#sweet_top_right').on('click', function() {
            swalInit.fire({
                title: 'Good job!',
                text: 'You clicked the button!',
                icon: 'success',
                position: 'top-right'
            });
        });


        // Center left
        $('#sweet_center_left').on('click', function() {
            swalInit.fire({
                title: 'Good job!',
                text: 'You clicked the button!',
                icon: 'success',
                position: 'center-left'
            });
        });

        // Center right
        $('#sweet_center_right').on('click', function() {
            swalInit.fire({
                title: 'Good job!',
                text: 'You clicked the button!',
                icon: 'success',
                position: 'center-right'
            });
        });


        // Bottom
        $('#sweet_bottom').on('click', function() {
            swalInit.fire({
                title: 'Good job!',
                text: 'You clicked the button!',
                icon: 'success',
                position: 'bottom'
            });
        });

        // Bottom left
        $('#sweet_bottom_left').on('click', function() {
            swalInit.fire({
                title: 'Good job!',
                text: 'You clicked the button!',
                icon: 'success',
                position: 'bottom-left'
            });
        });

        // Bottom right
        $('#sweet_bottom_right').on('click', function() {
            swalInit.fire({
                title: 'Good job!',
                text: 'You clicked the button!',
                icon: 'success',
                position: 'bottom-right'
            });
        });


        //
        // Toasts
        //

        // Success alert
        $('#sweet_toast_success').on('click', function() {
            swalInit.fire({
                text: 'Success toast message',
                icon: 'success',
                toast: true,
                showConfirmButton: false,
                position: 'top-right'
            });
        });

        // Error alert
        $('#sweet_toast_error').on('click', function() {
            swalInit.fire({
                text: 'Something went wrong!',
                icon: 'error',
                toast: true,
                showConfirmButton: false,
                position: 'top-right'
            });
        });

        // Warning alert
        $('#sweet_toast_warning').on('click', function() {
            swalInit.fire({
                text: 'Warning toast message',
                icon: 'warning',
                toast: true,
                showConfirmButton: false,
                position: 'top-right'
            });
        });

        // Info alert
        $('#sweet_toast_info').on('click', function() {
            swalInit.fire({
                text: 'Info toast message',
                icon: 'info',
                toast: true,
                showConfirmButton: false,
                position: 'top-right'
            });
        });

        // Question
        $('#sweet_toast_question').on('click', function() {
            swalInit.fire({
                text: 'Toast message with question',
                icon: 'question',
                toast: true,
                showConfirmButton: false,
                position: 'top-right'
            });
        });


        // Top
        $('#sweet_toast_top').on('click', function() {
            swalInit.fire({
                text: 'Success toast message',
                icon: 'success',
                toast: true,
                showConfirmButton: false,
                position: 'top'
            });
        });

        // Top left
        $('#sweet_toast_top_left').on('click', function() {
            swalInit.fire({
                text: 'Success toast message',
                icon: 'success',
                toast: true,
                showConfirmButton: false,
                position: 'top-left'
            });
        });

        // Center left
        $('#sweet_toast_center_left').on('click', function() {
            swalInit.fire({
                text: 'Success toast message',
                icon: 'success',
                toast: true,
                showConfirmButton: false,
                position: 'center-left'
            });
        });

        // Center
        $('#sweet_toast_center').on('click', function() {
            swalInit.fire({
                text: 'Success toast message',
                icon: 'success',
                toast: true,
                showConfirmButton: false,
                position: 'center'
            });
        });

        // Center right
        $('#sweet_toast_center_right').on('click', function() {
            swalInit.fire({
                text: 'Success toast message',
                icon: 'success',
                toast: true,
                showConfirmButton: false,
                position: 'center-right'
            });
        });

        // Bottom left
        $('#sweet_toast_bottom_left').on('click', function() {
            swalInit.fire({
                text: 'Success toast message',
                icon: 'success',
                toast: true,
                showConfirmButton: false,
                position: 'bottom-left'
            });
        });

        // Bottom
        $('#sweet_toast_bottom').on('click', function() {
            swalInit.fire({
                text: 'Success toast message',
                icon: 'success',
                toast: true,
                showConfirmButton: false,
                position: 'bottom'
            });
        });

        // Bottom right
        $('#sweet_toast_bottom_right').on('click', function() {
            swalInit.fire({
                text: 'Success toast message',
                icon: 'success',
                toast: true,
                showConfirmButton: false,
                position: 'bottom-right'
            });
        });


        //
        // Events
        //

        // onOpen event
        $('#sweet_onopen').on('click', function() {
            swalInit.fire({
                title: 'Here\'s a message!',
                icon: 'success',
                text: 'It\'s pretty, isn\'t it?',
                didOpen: function() {
                    alert('Notification is opened.');
                }
            });
        });

        // onClose event
        $('#sweet_onclose').on('click', function() {
            swalInit.fire({
                title: 'Here\'s a message!',
                text: 'It\'s pretty, isn\'t it?',
                icon: 'success',
                didClose: function() {
                    alert('Notification is closed.');
                }
            });
        });

        // Custom sweetAlert By Er. Niraj Thike
        $('.confirmSweetDelete').on('click', function() {
            var href = $(this).attr('link');
            swalInit.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                }
            }).then(function(result) {
                if(result.value) {
                    swalInit.fire({
                        title: 'Deleted!',
                        text: "The record has been deleted.",
                        icon: 'success',
                        showConfirmButton: true
                    });

                    function show_popup(){
                        window.location.href = href;
                    };
                    window.setTimeout( show_popup, 1000 ); // 1 second
                }
                else if(result.dismiss === swal.DismissReason.cancel) {
                    // swalInit.fire(
                    //     'Cancelled',
                    //     'Your imaginary file is safe :)',
                    //     'error'
                    // );
                }
            });
        });
    };

    // SweetAlert with Select2 selects
    var _componentSelect2 = function() {
        if (!$().select2) {
            console.warn('Warning - select2.min.js is not loaded.');
            return;
        }

        // Swal defaults
        var swalInit = swal.mixin({
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-light',
                    denyButton: 'btn btn-light'
                }
            });

        // Select2 single
        $('#sweet_select2_single').on('click', function() {
            swalInit.fire({
                title: 'Select Netherlands',
                input: 'select',
                inputOptions: {
                    '': '',
                    'DE': 'Germany',
                    'UA': 'Ukraine',
                    'HR': 'Croatia',
                    'NL': 'Netherlands'
                },
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-light',
                    denyButton: 'btn btn-light',
                    input: 'form-control select-single'
                },
                showCancelButton: true,
                inputValidator: function(value) {
                    return new Promise(function(resolve) {
                        if(value === 'NL') {
                            resolve();
                        }
                        else {
                            resolve('You need to select Netherlands :)');
                        }
                    });
                },
                inputAttributes: {
                    'data-placeholder': 'Select Netherlands'
                },
                didOpen: function() {

                    // Initialize Select2
                    $('.swal2-select.select-single').select2({
                        minimumResultsForSearch: Infinity
                    });
                }
            }).then(function(result) {
                if(result.value) {
                    swalInit.fire({
                        icon: 'success',
                        html: 'You selected: ' + result.value
                    });
                }
            });
        });

        // Select2 multiple
        $('#sweet_select2_multiple').on('click', function() {
            swalInit.fire({
                title: 'Select country',
                input: 'select',
                inputOptions: {
                    'DE': 'Germany',
                    'UA': 'Ukraine',
                    'HR': 'Croatia',
                    'NL': 'Netherlands'
                },
                inputValue: 'NL',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-light',
                    denyButton: 'btn btn-light',
                    input: 'form-control select-multiple'
                },
                showCancelButton: true,
                inputValidator: function(value) {
                    return !value && 'You need to select something!'
                },
                inputAttributes: {
                    'data-placeholder': 'Select country',
                    'multiple': 'multiple'
                },
                didOpen: function() {

                    // Initialize Select2
                    $('.swal2-select.select-multiple').select2();
                }
            }).then(function(result) {
                if(result.value) {
                    swalInit.fire({
                    icon: 'success',
                    html: 'You selected: ' + JSON.stringify($('.swal2-select.select-multiple').val())
                });
            }
            });
        });
    };

    // SweetAlert with Bootstrap Multiselect
    var _componentMultiselect = function() {
        if (!$().multiselect) {
            console.warn('Warning - bootstrap-multiselect.js is not loaded.');
            return;
        }

        // Swal defaults
        var swalInit = swal.mixin({
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-light',
                    denyButton: 'btn btn-light',
                    input: 'form-control select-multiselect'
                }
            });

        // SweetAlert with Multiselect
        $('#sweet_multiselect').on('click', function() {
            swalInit.fire({
                title: 'Select countries',
                input: 'select',
                buttonsStyling: false,
                inputOptions: {
                    'DE': 'Germany',
                    'UA': 'Ukraine',
                    'HR': 'Croatia',
                    'NL': 'Netherlands'
                },
                showCancelButton: true,
                inputValidator: function(value) {
                    return !$('.swal2-select.select-multiselect').val().length && 'You need to select something!'
                },
                inputAttributes: {
                    'multiple': 'multiple'
                },
                didOpen: function() {

                    // Initialize Multiselect when dialog is opened
                    $('.swal2-select.select-multiselect').multiselect();
                }
            }).then(function(result) {

                // Display selected values
                swalInit.fire({
                    icon: 'success',
                    html: 'You selected: ' + JSON.stringify($('.swal2-select.select-multiselect').val())
                });

                // Cancel button dialog
                if(result.dismiss === swal.DismissReason.cancel) {
                    swalInit.fire(
                        'Cancelled',
                        'You are safe now',
                        'error'
                    );
                }
            });
        });        
    };


    //
    // Return objects assigned to module
    //

    return {
        initComponents: function() {
            _componentSweetAlert();
            _componentSelect2();
            _componentMultiselect();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    SweetAlert.initComponents();
});
