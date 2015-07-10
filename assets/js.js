/**
 * Created by NEOGEEK on 10/7/2015.
 */

;(function($){

    //Predefine Variables
    var $publishingBox = false,
        $menuId = false,
        $menuName = false,
        $saveAsButton = false;

    $('#wpbody').append($('<div id="new_menu_form" title="Naming Box"><label>Please insert a name for the new menu!</label><input type="text" class="widefat" id="new-menu-name"></div>'));

    var $modal = $("#new_menu_form");
    $modal.dialog({
        'dialogClass'   : 'wp-dialog',
        'modal'         : true,
        'autoOpen'      : false,
        'closeOnEscape' : true,
        'height' : 200,
        'width' : 400,
        'buttons'       : {
            "Save" : function(){
                var that = this;
                $menuId = $('input#menu').val();
                $menuName = $('#new-menu-name').val();

                ajaxcall( { 'id' : $menuId, 'name' : $menuName }, function(data){
                    $('select#menu').html( $('select#menu').html() + '<option value="'+data+'">'+$menuName+'</option>' )
                    $(that).dialog('close');
                })
            },
            "Close": function() {
                $(this).dialog('close');
            }
        }
    });

    //Check if there is the target container
    if($publishingBox = $('.publishing-action')){
        $publishingBox.append( $('<input type="button" name="save_as_menu" id="save_as_menu" class="button button-primary menu-save" value="Save As Menu">') );


        $saveAsButton = $('#save_as_menu');
        $saveAsButton.on('click', function(evt) {
            $( "#new_menu_form" ).dialog( "open" );
        });
    }


    //The Functionality Follows
    function ajaxcall( data, callback ) {

        $.ajax({
            type: "post",
            url: sm_vars.url,
            data: {
                'action': "sm_save_as_menu",
                'key' : sm_vars.sm_cnonce,
                'name': data['name'],
                'id': data['id']
            },
            success: function (data) {
                callback(data);
            }
        });
    }

})(jQuery);