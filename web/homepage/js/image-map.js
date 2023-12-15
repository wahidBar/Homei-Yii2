$(document).ready(function() {

    // set the image-map width and height to match the img size
    // $('#image-map').css({'width':$('#image-map img').width(),
    //                   'height':$('#image-map img').height()
    // })

    //tooltip direction
    var tooltipDirection;

    for (i = 0; i < $(".pin").length; i++) {
        // set tooltip direction type - up or down             
        if ($(".pin").eq(i).hasClass('pin-down')) {
            tooltipDirection = 'tooltip2-down';
        } else {
            tooltipDirection = 'tooltip2-up';
        }

        // append the tooltip
        $("#image-map").append("<div style='left:" + $(".pin").eq(i).data('xpos') + "%;top:" + $(".pin").eq(i).data('ypos') + "%' class='" + tooltipDirection + "'>\
                                            <div class='tooltip2'>" + $(".pin").eq(i).html() + "</div>\
                                    </div>");
    }

    // show/hide the tooltip
    $('.tooltip2-up, .tooltip2-down').mouseenter(function() {
        $(this).children('.tooltip2').fadeIn(100);
    }).mouseleave(function() {
        $(this).children('.tooltip2').fadeOut(100);
    })
});