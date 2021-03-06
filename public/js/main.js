Helpers = function() {};
Helpers.initAlertTemplate = function() {
  $.ajax({
    url: '/ajax/ajaxMakeAlert',
    type: 'POST',
    data: {
      alertID: '%id%',
      alertString: '%string%'
    }
  }).done(function(data) {
    Helpers.alertTemplate = data;
  });
}
Helpers.makeAlert = function(id, string) {
  var fid = id.charAt(0).toUpperCase() + id.slice(1);

  // If there's an alert with the same ID delete that first
  if ($('#alert' +  fid).length) {
    $('#alert' + fid).remove();
  }

  var alert = Helpers.alertTemplate;
  alert = alert.replace(/%id%/g, fid).replace(/%string%/g, string);
  // Prepend the alert made to the body
  $('body').prepend(alert);
}

Helpers.initAlertTemplate();

$(function() {
  // TODO: Probably make this object oriented. Make it as a class or something?
  $('body').on('click', 'a[data-popup]', function() {
    var popupName = $(this).attr('data-popup');
    popupName = popupName.charAt(0).toUpperCase() + popupName.slice(1);

    var popupId = '#popup'+popupName;

    $('body').addClass('is-popup-overlayed');
    $(popupId).addClass('popup--show');
  })

  $('body').on('click', 'a[data-popup-close]', function() {
    var popupName = $(this).attr('data-popup-close');
    popupName = popupName.charAt(0).toUpperCase() + popupName.slice(1);
    var popupId = '#popup'+popupName;

    $('body').removeClass('is-popup-overlayed');
    $(popupId)
      .removeClass('popup--show')
      // Remove the event listener after the popup is closed.
      .off('click', 'a[data-popup-close]');
  });

  // Alert close button functionality
  $('body').on('click', 'a[data-alert-close]', function() {
    var alert = $(this).parents('.alert');
    $(alert).addClass('alert--hide');

    // After animation plays remove the alert
    setInterval(function() {
      $(alert).remove();
    }, 500);
  });


  $('#metaButton').click(function() {
    $siteHeader = $('#siteHeader');

    if ($siteHeader.hasClass('is-meta')) {
      $siteHeader
        .css('min-height', 'auto')
        .removeClass('is-meta')
        .attr('aria-hidden', 'true');
    }
    else {
      $siteHeader
        .css('min-height', $siteHeader.outerHeight())
        .addClass('is-meta')
        .attr('aria-hidden', 'false')
        .on('click', '#metaBackButton', function() {
          $siteHeader.off('click', '#metaBackButton');
          $('#metaButton').click();
        });
    }
  });

  $('#menuButton').click(function() {
    $('#menuButtonPopup').toggleClass('is-shown');
  });
})
