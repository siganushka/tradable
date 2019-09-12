import 'jquery'
import 'bootstrap'

global.$ = global.jQuery = $

$(function() {

  $.fn.resetElement = function() {
    $(this).wrap('<form>').closest('form').get(0).reset()
    $(this).unwrap()
  }

  $('[data-confirm-link]').on('click', function(event) {
    var $el = $(event.currentTarget)
    if ($el.hasClass('row-loading')) {
      return false
    }

    var confirmText = $el.data('confirm-text') || 'Are you sure?'
    if (false === confirm(confirmText)) {
      return false
    }
  })

  $('.choose-image').on('change', 'input[type=file]', function(event) {
    var files = event.currentTarget.files
    if (!files.length) {
      return false
    }

    var $current = $(event.currentTarget),
        $delegate = $(event.delegateTarget)

    var ref = $delegate.data('target-ref'),
        url = $delegate.data('upload-url'),
        loadingClass = $delegate.data('loading-class'),
        uploadedClass = $delegate.data('uploaded-class')

    var formData = new FormData()
        formData.append('filedata', files[0])

    $.ajax({
      url: url,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      beforeSend: function() {
        $delegate
          .removeClass(uploadedClass)
          .addClass(loadingClass)
      }
    })
    .done(function(r) {
      $(ref).val(r.url)
      var $img = $delegate.children('img')
      if (!$img.length) {
        $img = $('<img />').appendTo($delegate)
      }

      $img.attr('src', r.url)
      $delegate.addClass(uploadedClass)
    })
    .fail(function(r) {
      var message = $.isPlainObject(r.responseJSON)
        ? r.responseJSON.message
        : r.statusText
      alert(message)
    })
    .always(function() {
      $delegate.removeClass(loadingClass)
      $current.resetElement()
    })
  })
})