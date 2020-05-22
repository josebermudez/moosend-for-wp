(function($) {
    'use strict';

    

    function rgb2hex(rgb){
        rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
        return (rgb && rgb.length === 4) ? "#" +
        ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
        ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
        ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
    }

    let preFillValues = function (){
        let buttonBorderSize = parseInt($("#ms-sub-form").find("button").css("border-width"));
        let buttonBorderRadius = parseInt($("#ms-sub-form").find("button").css("border-radius"));
        var buttonBorderColor = rgb2hex($("#ms-sub-form").find("button").css("border-radius"));

        /* Title Styles */

        let titleFontSize = parseInt($("#ms-form-title").css('font-size'));

        let fontSize = parseInt($("#ms-sub-form").find("label").css("font-size"));
        let borderRadius = parseInt($("#ms-sub-form").find("input").css("border-radius"));
        let borderSize = parseInt($("#ms-sub-form").find("input").css("border-width"));
        let marginTop = parseInt($('.form-block').css("margin-top"));
        
        $("#font-size").val(fontSize);
        $("#field-corner-radius").val(borderRadius);
        $("#field-border-size").val(borderSize);
        $("#field-margin-top").val(marginTop);
        $("#button-border-thickness").val(buttonBorderSize);
        $("#button-corner-radius").val(buttonBorderRadius);
        $("#button-corner-radius").val(buttonBorderRadius);
        $("#title-font-size").val(titleFontSize);
    }

    let appendStyles = function(params){
        /* title styles */
        $('#ms-form-title').css("text-align", params.styleSettings.titleSettings[0].value);
        $('#ms-form-title').css("font-size", params.styleSettings.titleSettings[1].value + 'px');
        $('#ms-form-title').css("font-style", params.styleSettings.titleSettings[2].value);
        $('#ms-form-title').css("font-weight", params.styleSettings.titleSettings[3].value);
        $('#ms-form-title').css("color", params.styleSettings.titleSettings[4].value);
        
        /* field styles */
        $('#ms-sub-form').find('input, select').css("background-color", params.styleSettings.fieldSettings[0].value);
        $('#ms-sub-form').find('input, select').css("border-radius", params.styleSettings.fieldSettings[1].value + 'px');
        $('#ms-sub-form').find('input, select').css("border-width", params.styleSettings.fieldSettings[2].value + 'px');
        $('#ms-sub-form').find('input, select').css("border-color", params.styleSettings.fieldSettings[4].value);

        $('.form-block').css("margin-top", params.styleSettings.fieldSettings[3].value + 'px');

        /* button styles */
        $('#sub-button').css("background-color", params.styleSettings.buttonSettings[0].value);
        $('#sub-button').css("color", params.styleSettings.buttonSettings[1].value);
        $('#sub-button').css("border-radius", params.styleSettings.buttonSettings[2].value + 'px');
        $('#sub-button').css("border-width", params.styleSettings.buttonSettings[3].value + 'px');
        $('#sub-button').css("border-color", params.styleSettings.buttonSettings[4].value);

        /* label styles */
        $('#ms-sub-form').find('label span').css("font-size", params.styleSettings.labelSettings[0].value + 'px');
        $('#ms-sub-form').find('label span').css("font-style", params.styleSettings.labelSettings[1].value);
        $('#ms-sub-form').find('label span').css("font-weight", params.styleSettings.labelSettings[2].value);
        $('#ms-sub-form').find('label span').css("font-variant", params.styleSettings.labelSettings[3].value);
        $('#ms-sub-form').find('label span').css("color", params.styleSettings.labelSettings[4].value);
    }

    let formBlockGenerator = function(inputField, fieldName, required) {
        let required_char = (required) ? '*' : '';
        return  '<div class="form-block">' +
                '<label for="' + fieldName + '">' +
                '<span>' + fieldName + required_char +': </span>' +
                '</label>' +
                inputField +
                '</div>';
    }

    let renderPreview = function(mailingListObj, params) {

        let preFillFlag = sessionStorage.getItem('preFillFlag');
        let html = "";
        let required = "";

        let inputTypeMap = {
            0: 'text',
            1: 'number',
            2: 'date',
            3: 'dropdown',
            5: 'checkbox'
        };

        console.log(params);

        $("#preview").empty();
        
        html += '<form id="ms-sub-form" target="_blank" style="all:revert;">'+
        '<h3 id="ms-form-title">' + params.title + '</h3>';
        
        html += formBlockGenerator('<input type="email" name="email" id="email" required/>', 'email', true);

        
        if(params.memberName){
            html += formBlockGenerator('<input type="text" name="name" id="name"/>', 'name', false);
        }

        $.each(params.customFields, function(i, selectedField) {
            $.each(mailingListObj.CustomFieldsDefinition, function(j, remoteField) {
                if (remoteField.ID === selectedField.value) {
                    if (remoteField.Type === 5) {

                        required = (remoteField.IsRequired == true) ? 'required' : '';

                        html += formBlockGenerator(
                        '<label class="checkbox-label" style="width: 16px;height: 16px;">' +
                        '<input type="' + inputTypeMap[remoteField.Type] +
                        '" name="' + remoteField.Name +
                        '" id="' + remoteField.Name +
                        '" value="true" onchange="document.getElementById(\'h_' + remoteField.Name + '\').name=(this.checked ? \'\' : this.name)"/>' +
                        '<input id="h_' + remoteField.Name + '" name="' + remoteField.Name + '" type="hidden" value="false"/>' +
                        '</label>', remoteField.Name, remoteField.IsRequired);

                    } else if (remoteField.Type === 3) {
                        let finalOptions;

                        let xml = remoteField.Context,
                          xmlDoc = $.parseXML( xml ),
                          $xml = $( xmlDoc ),
                          $options = $xml.find("items"),
                          $option = $xml.find("item");

                        $.each($option, function () {
                            finalOptions += '<option>' + $(this).find("value").text() + '</option>';
                        });
                        
                        html += formBlockGenerator(
                            '<select name="' + remoteField.Name + '" id="' + remoteField.Name +'"' + required + '>' +
                            finalOptions +
                            '</select>', remoteField.Name, remoteField.IsRequired);
                    } else {

                        html += formBlockGenerator('<input type="' + inputTypeMap[remoteField.Type] + '" name="' + remoteField.Name + '" id="' + remoteField.Name + '"' + required + '/>', remoteField.Name, remoteField.IsRequired);
                    }
                }
            });
        });
        
        //let buttonLabel =  ( !== "") ? params.styleSettings.buttonSettings[5].value : "Subscribe";

        html += '<div class="ms-form-submit-container"><button id="sub-button" type="submit" style="margin-top: 10px;border: solid;" disabled>'+
            params.styleSettings.buttonSettings[5].value +
            '</button></div>' +
            '<div id="powered-by-container" style="margin-top: 10px;">' +
            '<a href="https://moosend.com/?utm_source=poweredby&utm_medium=forms&utm_campaign=user.moosend.com"  id="powered-by" target="_blank">' +
            '<img alt="powered by moosend" src="https://moosend.com/images/poweredby.png" /></a></div>' +
            '</form>' +
            '<a href="#" id="fake-close-button" class="fake-close-button">Close</a>';

        $("#preview").html(html);

        appendStyles(params);

        if(preFillFlag == 0){
            preFillValues();
            sessionStorage.setItem('preFillFlag', 1);
        }
    }

    let getMailingListPromise = function(list) {
        let data = {};

        let base_url = php_vars.endpoint + "members/lists/";
        let url = base_url.concat(list);
        return Promise.resolve($.ajax({
            url: url,
            type: "GET",
            data: data,
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-ApiKey', php_vars.api_key);
            }
        }));
    }

    let getMailingListObj = async function(list) {
        try {
            let mailingListObj = await getMailingListPromise(list);
            return mailingListObj;
        } catch (error) {
            console.log('Error:', error);
        };
    }


    $.fn.serializeObject = function() {
        let dataObj = {};
        let dataArray = this.serializeArray();
        $.each(dataArray, function() {
            if (dataObj[this.name] !== undefined) {
                if (!dataObj[this.name].push) {
                    dataObj[this.name] = [dataObj[this.name]];
                }
                dataObj[this.name].push(this.value || '');
            } else {
                dataObj[this.name] = this.value || '';
            }
        });
        return dataObj;
    };

    let getParameterByName = function(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        let regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }


    let formJSON = function(params) {
        let data = {
            'action': 'form_params_json',
            'params': params
        };
        $.ajax({
            type: 'POST',
            url: php_vars.ajax_url,
            data: data,
            success: true,
            dataType: 'json',
            async:false
        });
    }

    let editFormJSON = function(params) {
        let data = {
            'action': 'edit_form_params_json',
            'params': params,
            'formID': getParameterByName('form')
        };

        $.ajax({
            type: 'POST',
            url: php_vars.ajax_url,
            data: data,
            success: true,
            dataType: 'json',
            async:false
        });
    }

    let sendMailingList = function(selectedList) {
        let data = {
            'action': 'selected_list',
            'params': selectedList
        };

        $.post(php_vars.ajax_url, data, function(response) {
            console.log(response);
        }, 'json');
    }

    function setGetParameter(url, paramName, paramValue) {
        let hash = location.hash;
        url = url.replace(hash, '');
        if (url.indexOf(paramName + "=") >= 0) {
            let prefix = url.substring(0, url.indexOf(paramName));
            let suffix = url.substring(url.indexOf(paramName));
            suffix = suffix.substring(suffix.indexOf("=") + 1);
            suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
            url = prefix + paramName + "=" + paramValue + suffix;
        } else {
            if (url.indexOf("?") < 0)
                url += "?" + paramName + "=" + paramValue;
            else
                url += "&" + paramName + "=" + paramValue;
        }
        window.location.href = url + hash;
    }

    let getPathFromUrl = function() {
        return window.location.href.split("?")[0];
    }

    /* 
     * Document Ready
     */

    $(function() {

        sessionStorage.setItem('preFillFlag', 0);

        let globalVars = {
            'title': "",
            'name': "",
            'selectedList': "",
            'afterSubscription': "",
            'newTab': "",
            'formType': "",
            'memberName': "",
            'customFields': "",
            'theme': "",
            'popupSettings': "",
            'styleSettings': ""
        }

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
            window.open("https://" + subdomain + ".moosend.com/#/settings/mailing-lists");
        });

        let mailingListObj = null;

        if (getParameterByName('list') === null) {
            //resolve the edit-form occasion
            globalVars.selectedList = $("#lists-dropdown option:selected").val();
            getMailingListObj(globalVars.selectedList).then(function(object) {
                mailingListObj = object;
                $('#form').trigger('change');
            });
        } else {
            globalVars.selectedList = getParameterByName('list');
            getMailingListObj(globalVars.selectedList).then(function(object) {
                mailingListObj = object;
                $('#form').trigger('change');
            });
        }

        let form = $("#form");

        /* Jquery Validate Initialize */

        form.validate({ // initialize the plugin
            rules: {
                "lists-dropdown": {
                    required: true
                },
                "form-type": {
                    required: true
                },
                "after-subscription": {
                    required: true,
                    url: true
                }
            },
            messages: {
                "lists-dropdown": {
                    required: "You have to choose a mailing list to continue"
                },
                "form-type": {
                    required: "You have to choose a form type"
                },
                "after-subscription": {
                    required: "Please enter a valid url",
                    url: "Please enter a valid url"
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") == "form-type") {
                    error.appendTo("#type-error-span");
                } else if (element.attr("name") == "lists-dropdown") {
                    error.appendTo("#lists-error-span");
                }else if (element.attr("name") == "after-subscription") {
                    error.appendTo("#as-error-span");
                }
            }
        });

        /* Wizard Functionality */

        form.steps({
            headerTag: "h2",
            bodyTag: "section",
            transitionEffect: "fade",
            autoFocus: true,
            onStepChanging: function(event, currentIndex, newIndex) {
                if (currentIndex > newIndex) {
                    return true;
                }

                if (currentIndex === 0 && Number($("#lists-dropdown").val()) === "") {
                    return false;
                }

                if (currentIndex === 0 && Number($("#form-type-field input:checked").val()) === 0) {
                    return false;
                }

                if (currentIndex < newIndex) {
                    // To remove error styles
                    form.find(".body:eq(" + newIndex + ") label.error").remove();
                    form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }

                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            },

            onFinished: function(event, currentIndex) {
                if (getParameterByName('action') === 'edit') {
                    $('#success-edit').show();
                    editFormJSON(globalVars);
                } else {
                    $('#success-new').show();
                    formJSON(globalVars);
                }
                window.location = php_vars.redirect;
            }
        });

        //$('#tabs').tabs();
        $('#tabs').responsiveTabs({
            startCollapsed: false,
            collapsible: false,
            setHash: false,
        });

        let cpOptions = {
            change: function(event, ui) {
                setTimeout(function(){
                    $('#form').trigger('change');
                },1);
            }
        };

        $('.color-picker').wpColorPicker(cpOptions);

        $('.iris-palette').on('click', function () {
            $('.iris-palette').dblclick();
        });

        $("#form input").on('input', function() {
            $('#form').trigger('change');
        });

        $("#lists-dropdown").change(function() {
            globalVars.selectedList = $("#lists-dropdown option:selected").val();
            setGetParameter(window.location.href, 'list', globalVars.selectedList);
        });

        $("#form-type-field").change(function(){
            if ($("#form-type-field input:checked").val() == 'popup-form') {
                    $("#preview").addClass("modal");
                    $("#fake-close-button").show();
                    $("#popup-settings").fadeIn(500);
            }else{
                    $("#preview").removeClass("modal");
                    $("#fake-close-button").hide();
                    $("#popup-settings").fadeOut(400);
            }
        }).trigger('change');

        $("#theme-dropdown").change(function() {
            $("#preview-basic").attr("disabled", "disabled");
            $("#preview-valign").attr("disabled", "disabled");
            switch ($('#theme-dropdown option:selected').val()) {
                case 'basic':                
                    $("#preview-basic").removeAttr("disabled");
                    break;
                case 'valign':
                    $("#preview-valign").removeAttr("disabled");
                    break;
                default:
                    break;
            }
        }).trigger('change');

        $('#form').change(function() {
            globalVars.afterSubscription = $('#after-subscription').val();
            globalVars.newTab = $('#new-tab').prop('checked');
            globalVars.title = $('#form-title').val();
            globalVars.name = $('#form-name').val();
            globalVars.theme = $("#theme-dropdown option:selected").val();

            globalVars.memberName = $('#member-name').is(":checked");
            globalVars.customFields = $("#custom-fields *").serializeArray();
            globalVars.popupSettings = {
                "popupDelay": $('#popup-delay').val(),
                "exitIntent": $('#exit-intent').is(":checked"),
                "popupFrequency": $('#popup-frequency').val()
            }

            globalVars.styleSettings = {
                "titleSettings": $('#title-settings *').serializeArray(),
                "fieldSettings": $('#field-settings *').serializeArray(),
                "buttonSettings": $('#button-settings *').serializeArray(),
                "labelSettings": $('#label-settings *').serializeArray()
            }
            
            globalVars.formType = $("#form-type-field input:checked").val();

            renderPreview(mailingListObj, globalVars);
        });
    });

})(jQuery);