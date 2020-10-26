$(function() {
    // color_tuile();
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

            // add selected on tuile
            $(this).addClass("select");
            $('.tuile').not(this).each(function () {
                $(this).addClass("hide");
            });

            // display tuile_description
            $(this).find($(".tuile_description").addClass("show-flex"));
            $(this).find($(".triangle").addClass("show"));
        }
    });

    $(".close_tuile_description").click(function (){

        const tuiles = $(".tuile");
        tuiles.removeClass('hide');
        tuiles.removeClass('select');

        $(".tuile_description").removeClass('show-flex');
        $(".triangle").removeClass('show');

        event.stopPropagation();
    });

    $(".ranking").click(function(){
        event.stopPropagation();
    });
});

let nmb = 0;

// function color_tuile(){
//     let color = ["background_1", "background_2", "background_3", "background_4", "background_5", "background_6"];
//     // for( var i =0; i < $(".tuile").length; i += 1){
//     //     let ancient_nmb = 6;
//     //     nmb = Math.floor(Math.random() * 4);
//     //     if(nmb === ancient_nmb){
//     //         nmb = Math.floor(Math.random() * 4);
//     //     }
//     //     $(".tuile").eq( i ).find(".block-title").addClass(color[nmb]);
//     //     ancient_nmb = nmb;
//     // }
//     for( var i =0; i < 6; i += 1) {
//         // $(".container-fluid .tuile:nth-child(i)").find(".block-title").addClass(color[i]);
//         $(".tuile").eq( i ).find(".block-title").addClass(color[i]);
//     }
// }
