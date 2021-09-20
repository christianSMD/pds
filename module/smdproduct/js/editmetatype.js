function updateMetaTypeFields() {
    
    $.ajax({
        url: '/smdproduct-ajax',
        data: {
            method: "getChildMetaFieldOptions",
            metaParentId: $('#parentId').val() 
        },
        type: 'post',
        dataType: 'json',
        success: function (response) {
            
           prependNewValues(response)
            
        },
        error: function () {
            
        }
    })
    
}

function prependNewValues(newFieldValues) {
    
    var metaField = $('#fields')
    // Destroy the "chosen" instance
    metaField.chosen('destroy')
    // Set a default field
    metaField.html('<option value="0"></option>')
    // Create new elements
    $(newFieldValues).each(function (idx, val) {
                
        metaField.append('<option value="' + val.id + '">' + val.name + '</option>')
        
    })
    // Reinstantiate the chosen instance
    metaField.chosen()
}