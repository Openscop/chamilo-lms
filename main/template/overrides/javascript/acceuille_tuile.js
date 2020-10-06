$(function() {
    color_tuile();
    var is_display = 0;
    $(".tuile").click(function(e){
        // stop listener si click sur bouton de rating :
        if(e.target.getAttribute('data-link')){
            return;
        }
        // redirection pour la page mes cours
        if(this.getAttribute('data-url')){
            window.location = this.getAttribute('data-url');
        }else {
            // gere l'animation pour la page home
            if (is_display === 0) {
                $(this).effect("bounce", {times: 1}, 500);
                $('.tuile').not(this).each(function () {
                    $(this).css("display", "none");
                });
                $(this).find($(".tuile_description").fadeIn().css("display", "flex"));
                $(this).find($(".triangle").fadeIn());
                is_display = 1;
                return is_display;
            }
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
    let color = ["#FF3246", "#E61983", "#20124DFF", "#6DEAEE"];
    for( var i =0; i < $(".tuile").length; i += 1){
        let ancient_nmb = 6;
        nmb = Math.floor(Math.random() * 4);
        if(nmb === ancient_nmb){
            nmb = Math.floor(Math.random() * 4);
        }
        $(".tuile").eq( i ).find(".block-title").css("background", color[nmb]);
        ancient_nmb = nmb;
    }
}