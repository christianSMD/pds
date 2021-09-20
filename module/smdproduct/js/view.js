(function ($) {
    
    var images = JSON.parse($('#imageContent').val());
    
    $.each(images, function (idx, val) {
        $.each(val, function (i, v) {
            
            var imgId = idx + '' + i;
            
            $('#imageContainer').append('<img id="' + imgId + '" />')
            
            $.ajax({
                url: '/smdproduct-img',
                data: {
                    path: v.url
                },
                type: 'post',
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    $('#' + imgId).attr('src', response)
                    
                }
            })
            
        })
    })
    
})(jQuery)

