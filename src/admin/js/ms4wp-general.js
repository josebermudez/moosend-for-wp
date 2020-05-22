(function($) {
    'use strict';

    let mergeValues = function(array){
        var mergedArray = new Object();
        var keys = Object.keys(array);
        for(var i = 0; i < keys.length; i++){
            var key = keys[i];
            var obj = {}
            /*if((array[key]['name'] === 'Password') || (array[key]['name'] === 'ConfirmPassword')){
                obj['ClearPassword'] = array[key]['value'];
            }else{
            }*/
            obj[array[key]['name']] = array[key]['value'];
            $.extend(mergedArray, obj);
        }
        return mergedArray;
    }

    let getGuid = function(str){
        return str.slice(0,8)+"-"+str.slice(8,12)+"-"+str.slice(12,16)+
        "-"+str.slice(16,20)+"-"+str.slice(20,str.length+1);
    }

    let deleteForms = function() {
        let data = {
            'action': 'delete_forms'
            };
            $.post(php_vars.ajax_url, data, function(response){
                console.log(response);
            }, 'json');
    }

    $(function() {

        $("#create-your-forms").click(function(){
            $("#create-your-forms-hidden")[0].click();
        })

        $("#api-key-form").submit(function(whattt) {
            if(php_vars.api_key != null && php_vars.api_key != $("#moosend-for-wp-api_key").val()){
                var c = confirm("By changing your current Moosend API Key your forms will be permanently deleted.\nAre you sure you want to proceed?");
                deleteForms();
                return c;
            }
        });

        /* Subdomain Modal */

        $("#opens_window").click(function(e) {
            e.preventDefault();       
            $('#modal').dialog({
                draggable: true,
                width: 400,
                modal: true,
                responsive: true,
                close: function(event, ui) { $('#wrap').show(); },
                open: function(event, ui) 
                { 
                    $('.ui-widget-overlay').bind('click', function()
                    { 
                        $("#modal").dialog('close'); 
                    }); 
                }
                });
        });

        $("#modal-button").click(function(){
            var subdomain = $('#user-subdomain').val();
            window.open("https://" + subdomain + ".moosend.com/#/settings/apikey");
        });

        let form = $("#sign-up-form");
        var body = mergeValues(form.serializeArray());

        $.validator.addMethod("remoteValidate", function(value, element)
        {
            var data = {}
            data[element.name] = value;
            $.post(php_vars.registrationEndpoint, data, function(response) {
            }, 'json').error(function(xhr){
                var response = JSON.parse(xhr.responseText).ValidationErrors
                var keys = Object.keys(response);                        
                for(var i=0;i<keys.length;i++){
                    var key = keys[i];
                    if(response[i].Field == element.name)
                    {   
                        return false;
                    }
                }
                $("#"+element.id + "-error").hide();
                return true;
            });
        }, '');

        form.validate({
            onfocusout: false,
            onkeyup: false,
            onclick: false,
            rules: {
                ClearPassword: {
                    required: true,
                    minlength: 8,
                    maxlength: 50
                },
                Email: {
                    required: true,
                    email: true,
                    remoteValidate: true
                },
                Name: {
                    required: true
                },
                Company: {
                    required: true,
                    minlength: 2,
                    maxlength: 50
                },
                SiteName: {
                    required: true,
                    minlength: 2,
                    maxlength: 50,
                    remoteValidate: true
                }
            },
            messages: {
                Email: {
                    remoteValidate: "Email already exists, please use a different Email"
                },
                SiteName: {
                    remoteValidate: "Site already exists, please use a different Site"
                }
            }
        });

        /* Jquery Validate Initialize */
        
        form.submit(function(e){
            var body = mergeValues(form.serializeArray());
            $.post(php_vars.registrationEndpoint, body, function(response) {
                $('#moosend-for-wp-api_key').val(getGuid(response.ApiKey));
                $('#api-key-button').trigger('click');
            }, 'json')
            e.preventDefault();
        });

        //$( "#api-key-form" ).submit();

    });

})(jQuery);