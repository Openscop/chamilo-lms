$(function() {
    color_tuile();
    var is_display = 0;
    $(".tuile").click(function(){
        if(is_display===0) {
            $(this).effect("bounce", {times: 1}, 500);
            $('.tuile').not(this).each(function () {
                $(this).css("display", "none");
            });
            $(this).find($(".tuile_description").fadeIn().css("display", "flex"));
            $(this).find($(".triangle").fadeIn());
            is_display = 1;
            return is_display;
        }
    });

    $(".close_tuile_description").click(function (){

        $(".tuile_description").css("display", "none");
        $(".tuile").fadeIn();
        $(".triangle").css("display", "none");
        event.stopPropagation();
        is_display = 0;
        return is_display;
    });

    $(".ranking").click(function(){
        event.stopPropagation();
    });
});

let nmb = 0;

function color_tuile(){
    let color = ["background_1", "background_2", "background_3", "background_4"];
    for( var i =0; i < $(".tuile").length; i += 1){
        let ancient_nmb = 6;
        nmb = Math.floor(Math.random() * 4);
        if(nmb === ancient_nmb){
            nmb = Math.floor(Math.random() * 4);
        }
        $(".tuile").eq( i ).find(".block-title").addClass(color[nmb]);
        ancient_nmb = nmb;
    }
}