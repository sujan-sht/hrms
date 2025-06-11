/* ------------------------------------------------------------------------------
*
*  # Custom JS code
*
*  Place here all your custom js. Make sure it's loaded after app.js
*
* ---------------------------------------------------------------------------- */
function getRandomColor() {
  var letters = '0123456789ABCDEF';
  var color = '#';
  for (var i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}

$(document).ready( function () {
  $('input[type=submit], button[type=submit]').click( function () {
    var form = $(this).parents('form:first');
    if (form.valid()) {
      $(this).attr('disabled', true);
      $(this).prepend('<i class="icon-spinner4 spinner"></i> ');
      form.submit();
    }
  });

  $('.filter-button').click(function () {
    $('.filter-option').toggleClass('d-none');
    $('.filter-option').toggleClass('show');
  });

  $('.cancel-filter').click(function () {
    $('.filter-option').removeClass('show');
  });
});