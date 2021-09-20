(function ($) {
    
    $('.productImages').each(function (index, value) {
        
        if($(value).val() !== "[]") {
            
        
        var images = JSON.parse($(value).val())
                
        $.each(images, function (idx, val) {
                        
            $.each(val, function (i, v) {

                var productId = $(value).data('product-id'),
                    imgId = productId + '' + idx + '' + i;

                $(value).parent().append('<img id="' + imgId + '" />')

                $.ajax({
                    url: '/smdproduct-img',
                    data: {
                        path: v.url
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function (response) {

                        $('#' + imgId).attr('src', response)

                    },
                    error: function ( jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR, textStatus, errorThrown)
                    }
                })

                return false
                
            })
            
            return false
            
        })
        
        }
        
        
    })
    
})(jQuery)
