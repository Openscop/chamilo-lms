{% extends 'layout/blank.tpl'|get_template %}
{% block body %}

{% if hot_courses|length > 0 %}
    {% if access_url_description matches '{\w*emain\w*}' %}
        {% set itemsPerLine = 3 %}
    {% elseif access_url_description matches '{\w*ommun\w*}' %}
        {% set itemsPerLine = 4 %}
    {% else %}
        {% set itemsPerLine = 3 %}
    {% endif %}
    <div id="toggle-card-listing" class="toggle-cards-{{ itemsPerLine }}-columns">
        {% for key, item in hot_courses %}
            {% set line = (key/itemsPerLine)|round(0, 'floor') + 1 %}


            <!-- Create a new line very each "itemsPerLine" items -->
            <!-- Open the line if it's the first item -->
            {% if(key%itemsPerLine == 0) %}
                <div class="toggle-card-line">
            {% endif %}
                <div class="toggle-card-item modulo-2-{{ key%2 }} modulo-3-{{ key%3 }} modulo-4-{{ key%4 }} modulo-5-{{ key%5 }} modulo-6-{{ key%6 }}">
                    <!-- Small part of toggle-card -->
                    <div class="toggle-card-summary">
                        <div class="toggle-card-summary-container">
                            <div class="toggle-card-summary-image">
                                <img src="{{ item.course_image_large }}" style="object-fit: cover;" alt="{{ item.title }}">
                            </div>
                            <h2 class="toggle-card-summary-title">{{ item.title }}</h2>
                            {% if item.is_course_student or item.is_course_teacher %}
                            {% endif %}
                            <i class="gg-chevron-right-o"></i>
                            {% if item.is_course_teacher %}
                            {% elseif item.is_course_student %}
                                <span class="registred">Inscrit·e</span>
                            {% endif %}
                            <div class="toggle-card-summary-tags">
                                {% for tag in item.tags%}
                                <span>{{ tag.tag }}</span>
                                {% endfor %}
                            </div>
                        </div>
                    </div>
                    <!-- Grande partie de la toggle-card (full) -->
                    <div class="toggle-card-details">
                        <div class="toggle-card-details-container">
                            <div class="toggle-card-details-left">
                                {{ item.description }}
                            </div>
                            <div class="toggle-card-details-right">
                                {{ item.details }}
                                <div class="toggle-card-details-buttons">
                                    {{ item.go_to_course_button }}
                                    {{ item.register_button }}
                                    {{ item.unsubscribe_button }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Close the line if it's the last and fourth item of the line -->
            {% if(key%itemsPerLine == itemsPerLine - 1) %}
                </div>
            <!-- Cline the line and add missing toggle-cards if it's the last item (but if it's not the fourth item of the line) -->
            {% elseif(key + 1 == hot_courses|length) %}
                    {% set missing_cards = (itemsPerLine * (line + 1) - hot_courses|length) %}
                    {% for missing_card in missing_cards %}
                        <div class="toggle-card-item"></div>
                    {% endfor %}
                </div>
            {% endif %}
        {% endfor %}
    </div>
{% endif %}

{% for item in hot_courses %}
{% if item.title %}

<div class="col-xs-12 col-sm-5 col-md-4 tuile">
    <div class="thumbnail">
        <div class="items items-hotcourse">
            <div class="image card-img-top">
                <a title="{{ item.title }}" href="#">
                    <img src="{{ item.course_image_large }}" class="img-responsive" style="object-fit: cover;" alt="{{ item.title }}">
                </a>
            </div>

            <div class="block-title">
                <h5 class="title">
                    {% if item.is_course_student or item.is_course_teacher %}
                    <a alt="{{ item.title }}" title="#" class="title-text">
                        {{ item.title}}
                    </a>
                    <i class="gg-chevron-right-o"></i>
                    {% else %}
                    <a alt="{{ item.title }}" title="#" class="title-text">
                        {{ item.title }}
                    </a>
                    <i class="gg-chevron-right-o"></i>
                    {% endif %}
                </h5>
                {% if item.is_course_teacher %}
                {% elseif item.is_course_student %}
                    <span class="registred">Inscrit·e</span>
                {% endif %}
                <div class="block-tag">
                    {% for tag in item.tags%}
                    <span>{{ tag.tag }}</span>
                    {% endfor %}
                </div>
            </div>
        </div>
    </div>
    <div class="triangle">
    </div>
    <div class="tuile_description container">
        <div class="row">
            <div class="tuile_description-col tuile_description-col-left" >
                {{item.description}}
            </div>
            <div class="tuile_description-line"></div>
            <div class="tuile_description-col tuile_description-col-right">
                {{item.details }}
                <div class="course_button">
                    {{ item.go_to_course_button }}
                </div>
                <div class="course_button">
                {{ item.register_button }}

                </div>
                <div class="unsubscribe_button">
                {{ item.unsubscribe_button }}
                </div>
            </div>
            <button class="close_tuile_description"><i class="fa fa-times" aria-hidden="true"></i></button>
        </div>
</div>
    </div>



{% endif %}

{% endfor %}

<script src="main/template/overrides/javascript/acceuille_tuile.js"></script>


<script type="text/javascript">
/**********************************************************************************************/
// TOGGLE CARDS
/**********************************************************************************************/

// Paramètre les marges intérieures, extérieures et les bordures
var paddingBottomForItem = 20;
var paddingsForDetails = 25;
var marginBottomForDetails = 20;
// var marginTop = 30;
// var borders = 2;

/**
 * closeAllToggleCards()
 * Referme toutes les toggle-cards
**/
function closeAllToggleCards()
{
    // Enlève la hauteur maximale (max-height) de tous les détails
    $('.toggle-card-details').css('maxHeight', '0px').css('overflow', 'hidden');

    // Enlève la classe opened de tous les summary
    $('.toggle-card-summary').removeClass('opened');

    // Enlève les marges intérieures de toutes les lignes
    $('.toggle-card-line').css('paddingBottom', '0px').css('marginBottom', '0px');

    // Paramètre les marges intérieures de toutes les toggle-cards
    $('.toggle-card-item').css('paddingBottom', paddingBottomForItem + 'px').css('marginBottom', '0px');
}


/**
 * openOrCloseToggleCard()
 * Ouvre ou ferme une toggle-card
 *
 * @that object Bouton sur lequel on clique
**/
function openOrCloseToggleCard(that)
{
    // Vérifier si c'est ouvert
    var is_open = that.hasClass('opened');
    console.log("is_open", is_open);

    // Ferme tout
    closeAllToggleCards();

    // Ouvre les détails
    if(!is_open)
    {
        // Paramètre les détails de la hauteur maximale (max-height)
        var item = that.parents('.toggle-card-item');
        var details = item.find('.toggle-card-details');
        var details_container = details.find('.toggle-card-details-container');
        var details_height = details_container.height() + paddingsForDetails * 2 + marginBottomForDetails; // + marginTop + marginBottom + borders * 2;

        // Paramètre les marges intérieures de la ligne
        var line = that.parents('.toggle-card-line');
        var summary = that.parents('.toggle-card-summary');

        // Choisit l'élément sur lequel appliquer une marge intérieure (pour ordinateur et tablettes : toggle-card-line // pour smartphones : toggle-card-item) + ajoute plus de marges intérieures pour les smartphones
        var item_with_padding = line;
        var more_paddings = 0;
        if($(window).width() <= 1000)
        {
            console.log('Inférieur à 1000');
            item_with_padding = item;
            more_paddings = 20;
        }

        // Paramètre la classe
        that.addClass('opened');

        // Paramètre la hauteur maximale (max-height) sur les détails courants
        details.css('maxHeight', details_height + 'px').css('overflow', 'visible');

        // Paramètre la marge intérieure pour l'élément courant
        item_with_padding.css('paddingBottom', details_height + more_paddings + 'px'); //.css('marginBottom', '10px');
    }
}
/*****************************************************/
// Toggle card
/*****************************************************/
// Quand un toggle-card doit être ouvert par défaut
if($('.toggle-card-id').length) {
    // var id = $(".toggle-card-id").data('id');
    // var button = $('#button-' + id);
    // openOrCloseToggleCard(button);
}


// Quand on clique sur le bouton d'une toggle-card pour l'ouvrir ou la fermer
if($("#toggle-card-listing").length)
{
    $('.toggle-card-summary .toggle-card-summary').on('click', function(e) {
        e.preventDefault();
    });

    // Si on n'est pas en train de charger de nouvelles cards
    if(!$('#toggle-card-listing').hasClass('load-new-cards')) {
        $("#toggle-card-listing").on('click', ".toggle-card-summary", function()
        {
            var that = $(this);
            openOrCloseToggleCard(that);
        });
    }
}
</script>

{% endblock %}
