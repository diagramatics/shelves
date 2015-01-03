$(function() {
  // TODO: Probably make this object oriented. Make it as a class or something?
  $('body').on('click', 'a[data-popup]', function() {
    var popupName = $(this).attr('data-popup');
    popupName = popupName.charAt(0).toUpperCase() + popupName.slice(1);

    var popupId = '#popup'+popupName;

    $('body').addClass('is-popup-overlayed');
    $(popupId)
      .addClass('popup--show')
      .on('click', 'a[data-popup-close]', function() {
        $('body').removeClass('is-popup-overlayed');
        $(popupId)
          .removeClass('popup--show')
        // Remove the event listener after the popup is closed.
          .off('click', 'a[data-popup-close]');
      });
  })

  $('#metaButton').click(function() {
    $siteHeader = $('#siteHeader');

    if ($siteHeader.hasClass('is-sign-in')) {
      $siteHeader
        .css('min-height', 'auto')
        .removeClass('is-sign-in')
        .attr('aria-hidden', 'true');
    }
    else {
      $siteHeader
        .css('min-height', $siteHeader.outerHeight())
        .addClass('is-meta')
        .attr('aria-hidden', 'false')
        .on('click', '#signInBackButton', function() {
          $siteHeader.off('click', '#metaBackButton');
          $('#metaButton').click();
        });
    }
  })
})
