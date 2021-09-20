$('.bulkTask').on('change', function (e) {
    
    e.preventDefault()
    
    emptyFields()    
    generateFields()
        
})

function emptyFields() {
    
    $('.main-form .hidden-fields').html('')
    
}

function generateFields() {
    
    var i = 0;
    
    $('.bulkTask:checked').each(function () {
        
        console.log($(this).val())
        
        var html = '<input type="hidden" name="module[' + i + ']" value="0" />\n\
<input type="hidden" name="parent[' + i + ']" value="0" />\n\
<input type="hidden" name="name[' + i + ']" value="' + $(this).val() + '" />\n\
<input type="hidden" name="color[' + i + ']" value="" />\n\
<input type="hidden" name="type[' + i + ']" value="' + $(this).data('type') + '" />\n\
<input type="hidden" name="estimate[' + i + ']" value="' + $(this).data('hours') + '" />\n\
<input type="hidden" name="estStarted[' + i + ']" value="" />\n\
<input type="hidden" name="deadline[' + i + ']" value="" />\n\
<input type="hidden" name="desc[' + i + '] value="" />\n\
<input type="hidden" name="pri[' + i + ']" value="3" />\n\
<input type="hidden" name="storyEstimate' + i + '" value="" />\n\
<input type="hidden" name="storyDesc' + i + '" value="" />\n\
<input type="hidden" name="storyPri' + i + '" value="" />';
        
        $('.main-form .hidden-fields').append(html)
        
        i++
        
        
    })
}