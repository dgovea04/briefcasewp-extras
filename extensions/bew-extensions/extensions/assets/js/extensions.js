(function($){
 
    $( document ).on( 'click', '.button-extension-activate', function( e ){
      e.preventDefault();
      // Get the current extension ID
      var extension = $(this).attr('data-integration'),
          $this = $(this);
      
      if( extension ) {
        // Process AJAX
        $.ajax({
          url: extjsobject.ajax_url,
          dataType: 'json',
          type: 'POST',
          data: { action: extjsobject.actions.activate, extension: extension, nonce: extjsobject.nonce },
          success: function(resp) {
            if( resp.success ) {
              // On success, add the deactivate class and remove the activate class. 
              // Also remove the primary class so we have a gray button and not a blue one.
              $this.addClass('button-integration-deactivate')
                .addClass('button-default')
                .removeClass('button-integration-activate')
                .removeClass('button-primary')
                .html( extjsobject.text.deactivate );
            } 
          }
        });
      }

    });

    $( document ).on( 'click', '.button-extension-deactivate', function( e ){
      e.preventDefault();

      var extension = $(this).attr('data-integration'),
          $this = $(this);
      
      if( extension ) {
        $.ajax({
          url: extjsobject.ajax_url,
          dataType: 'json',
          type: 'POST',
          data: { action: extjsobject.actions.deactivate, extension: extension, nonce: extjsobject.nonce },
          success: function(resp) {
            if( resp.success ) {
              $this.removeClass('button-integration-deactivate')
              .removeClass('button-default')
              .addClass('button-integration-activate')
              .addClass('button-primary')
              .html( extjsobject.text.activate );
            } 
          }
        });
      }

    });


})(jQuery);