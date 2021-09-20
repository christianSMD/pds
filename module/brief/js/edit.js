(function ($) {
    
    showTypeFields()
    
})(jQuery)

function showTypeFields() {
    
    var type = $('#type').val()
    
    $('.sub-form tr').hide()
        
    if(type.length > 0) {

        $('.sub-form tr.' + type).fadeIn()
        
    }
    
}

function requiresPrinting() {
    
    var print = $('input[name="printing"]:checked').val()
    
    if(print == 1) {
        $('.printQty').fadeIn()
    } else {
        $('.printQty').hide()
    }
    
}