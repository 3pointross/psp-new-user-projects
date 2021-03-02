jQuery(document).ready(function($) {

     $('body').on( 'click', '.js-add-condition', function(e) {

          e.preventDefault();

          var role = $('select[name="psp-role"]').val();
          var project = $('select[name="psp-new-project"]').val();

          if( role == '' || project == '' ) {
               alert( 'You need to select a role and project' );
               return;
          }

          jQuery.ajax({
               url: ajaxurl + '?action=psp_nup_add_condition',
               type: 'post',
               data: {
                    role : role,
                    project: project
               },
               success: function( response ) {
                    $('.psp-new-project-existing').append( response.data.markup );
               }
          });


     });

     $('body').on( 'click', '.js-remove-cond', function(e) {

          e.preventDefault();

          var offset = $(this).parents('.psp-new-project-cond').index();
          var elm = $(this).parents('.psp-new-project-cond');

          jQuery.ajax({
               url: ajaxurl + '?action=psp_nup_remove_condition',
               type: 'post',
               data: {
                    offset : offset,
               },
               success: function( response ) {
                    $(elm).slideUp('fast', function() {
                         $(elm).remove();
                    });
               }
          });

     });

});
