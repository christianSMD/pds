(function ($) {
    
    $('.delete-child').on('click', function () {

        var r = confirm('Are you sure you want to delete this child product?')

        if(r == false) {
            return false
        }

    })

    /**
     * Delete a product
     * Wholesale deletes a product and any children from the database
     */
    $('#delete-product').on('click', function () {

        var r = confirm('Are you sure you want to delete this product? This will remove the product and any linked children from the database entirely. This cannot be undone.')

        if(r == false) {
            return false
        }

    })
    
})(jQuery)

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