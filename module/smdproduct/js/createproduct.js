function metaTypeChange() {
    
    var metaTypeParentId = $('#metaTypeId').val()
        
    $.ajax({
        url: '/smdproduct-ajax',
        data: {
            method: 'getChildMetaTypes',
            metaTypeId: metaTypeParentId
        },
        type: 'post', // POST because Zentao is stripping out GET requests
        dataType: 'json',
        success: function (response) {
            
            populateMetaChildren(response)
            
        },
        error: function () {
            alert('There was an error making this request. Please try again.')
        }
    })
}

function populateMetaChildren(metaChildren) {
    
    var metaField = $('#metaTypeChildId')
    // Reset metaChild select
    metaField.chosen('destroy')
    metaField.html('<option value=""></option>')
    
    metaField.parent().parent().hide()
        
    // Repopulate the child select
    if(Object.keys(metaChildren).length > 0) {
        
        // cast object to an array
        var result = Object.values(metaChildren).map(function(key) {
            return key
        });
        
        $(result).each(function (idx, val) {
            metaField.prepend('<option value="' + val.id + '">' + val.name + '</option>' )
        })
        
        metaField.parent().parent().show()
        
    }
    
    // Re-init the select box
    metaField.chosen()
    
}

function addNewValue(metaFieldId) {
    
    var newFieldValue = prompt('Enter the new value for this field', '')
        
    
    if(newFieldValue == null || newFieldValue == '') {
        // No new value
        return false;
    } else {
        // Send new value to the database
        $.ajax({
            url: '/smdproduct-ajax',
            data: {
                method: 'setMetaFieldValue',
                metaFieldId: metaFieldId,
                metaValue: newFieldValue
            },
            type: 'post',
            dataType: 'json',
            success: function (response) {
                
                prependNewValue(metaFieldId, response, newFieldValue)
                
            },
            error: function () {
                
                alert('There was an error adding the value. Please try again.')
                
            }
        })
        
    }
    
}

function prependNewValue(metaFieldId, newFieldValueId, newFieldValue) {
    
    var metaField = $('#meta-' + metaFieldId)
    
    metaField.chosen('destroy')
    metaField.prepend('<option value="' + newFieldValueId + '">' + newFieldValue + '</option>' )
    metaField.val(newFieldValueId)
    metaField.chosen()
    
}