$(function(){
    function resizeEvents(){
        var cw = $(".vivid-carousel").width();
        if($(window).width() < 1024){ //medium      
            var visibleSlides = $(".carousel-item-container").attr("data-medium-visible");
            var slideWidth = cw/visibleSlides;
            $(".carousel-item").width(slideWidth);
            if($(window).width() < 768){ //small        
                var visibleSlides = $(".carousel-item-container").attr("data-small-visible");
                var slideWidth = cw/visibleSlides;
                $(".carousel-item").width(slideWidth);
            }
        }           
        else{ //desktop
            var visibleSlides = $(".carousel-item-container").attr("data-large-visible");
            var slideWidth = cw/visibleSlides;
            $(".carousel-item").width(slideWidth);
        }
        
        //reset offsets
        var slideWidth = $(".carousel-item").outerWidth();
        var offset = $(".carousel-item-container").attr("data-offset");
        var containerOffset = offset*slideWidth;
        $(".carousel-overflow-wrap").attr("style", "transform: translate3d(-"+containerOffset+"px,0,0);");
    }
    $(window).resize(function(){
        resizeEvents();
    });
    resizeEvents();
    var timer = 100;
    $(".carousel-item").each(function(i){
        setTimeout(function(){
            $(".carousel-item").eq(i).addClass('vis');    
        },timer);
        timer = timer+150;
    });
    var slideCount = $(".carousel-item").length;
    $(".carousel-nav-button.next").click(function(){
        var offset = $(".carousel-item-container").attr("data-offset");
        var slideWidth = $(".carousel-item").outerWidth();
        offset =  parseInt(offset);
        if((slideCount-1)>offset){
            offset++;
            $(".carousel-item-container").attr("data-offset",offset);
        }
        var containerOffset = offset*slideWidth;
        $(".carousel-overflow-wrap").attr("style", "transform: translate3d(-"+containerOffset+"px,0,0);");
    });
    $(".carousel-nav-button.prev").click(function(){
        var offset = $(".carousel-item-container").attr("data-offset");
        var slideWidth = $(".carousel-item").outerWidth();
        offset =  parseInt(offset);
        if(0<offset){
            offset--;
            $(".carousel-item-container").attr("data-offset",offset);
        }
        var containerOffset = offset*slideWidth;
        $(".carousel-overflow-wrap").attr("style", "-ms-transform: translate3d(-"+containerOffset+"px,0,0); -moz-transform: translate3d(-"+containerOffset+"px,0,0); -webkit-transform: translate3d(-"+containerOffset+"px,0,0); transform: translate3d(-"+containerOffset+"px,0,0); ");
    });
});
