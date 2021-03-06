<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
js::import($jsRoot . 'jquery/validation/min.js');
?>
<style>label.error {color:red}</style>
<script> 
requiredFields = "<?php echo isset($config->{$this->app->getModuleName()}->{$this->app->getMethodName()}->requiredFields) ? $config->{$this->app->getModuleName()}->{$this->app->getMethodName()}->requiredFields : '';?>";
$(document).ready(function()
{
    if(typeOf requiredFields == 'unDefined') return;
    for(i in requiredFields)
    {
        $('form #' + requiredFields[i]).addClass('required');
    }
    initValidation();
    $('form').validate();
});

function initValidation()
{
    clientLang = "<?php echo $this->app->getClientLang();?>";

    if(clientLang == 'en')
    {
        $.extend($.validator, 
        { 
            messages: 
            {
                required: "This field is required.",
                remote: "Please fix this field.",
                email: "Please enter a valid email address.",
                url: "Please enter a valid URL.",
                date: "Please enter a valid date.",
                dateISO: "Please enter a valid date (ISO).",
                number: "Please enter a valid number.",
                digits: "Please enter only digits.",
                creditcard: "Please enter a valid credit card number.",
                equalTo: "Please enter the same value again.",
                accept: "Please enter a value with a valid extension.",
                maxlength: $.validator.format("Please enter no more than {0} characters."),
                minlength: $.validator.format("Please enter at least {0} characters."),
                rangelength: $.validator.format("Please enter a value between {0} and {1} characters long."),
                range: $.validator.format("Please enter a value between {0} and {1}."),
                max: $.validator.format("Please enter a value less than or equal to {0}."),
                min: $.validator.format("Please enter a value greater than or equal to {0}.")
            }
        });
    }
    else
    {
        $.extend($.validator, 
        {
            messages: 
            {
                required: "????????????????????????",
                remote: "Please fix this field",
                email: "??????????????????????????????",
                url: "??????????????????URL",
                date: "????????????????????????",
                dateISO: "???????????????????????????ISO???",
                number: "????????????????????????",
                digits: "??????????????????",
                creditcard: "???????????????????????????",
                equalTo: "????????????????????????",
                accept: "Please enter a value with a valid extension.",
                maxlength: $.validator.format("????????????{0}?????????"),
                minlength: $.validator.format("????????????{0}?????????"),
                rangelength: $.validator.format("????????????????????????{0}???{1}??????"),
                range: $.validator.format("???????????????{0}???{1}??????"),
                max: $.validator.format("?????????????????????{0}."),
                min: $.validator.format("?????????????????????{0}.")
            }
        });
    }


/*
    $.extend($.validator,
    {
            methods:
            {
                required: function(value, element, param) {
                            // check if dependency is met
                            if ( !this.depend(param, element) )
                                return "dependency-mismatch";
                            switch( element.nodeName.toLowerCase() ) {
                            case 'select':
                                // could be an array for select-multiple or a string, both are fine this way
                                var val = $(element).val();
                                return val && val.length > 0;
                            case 'input':
                                if ( this.checkable(element) )
                                    return this.getLength(value, element) > 0;
                            default:
                                return $.trim(value).length > 0;
                            }
                        }
            }
    });
*/
}

</script>
