{% extends 'layout/blank.tpl'|get_template %}
{% block body %}

{% for item in hot_courses %}
{% if item.title %}

<div class="col-xs-12 col-sm-5 col-md-4 tuile">
    <div class="thumbnail">
    <div class="items items-hotcourse">
        <div class="image card-img-top">
            <a title="{{ item.title }}" href="#">
                <img src="{{ item.course_image_large }}" class="img-responsive" alt="{{ item.title }}">
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
                        {{ item.title}}
                    </a>
                    <i class="gg-chevron-right-o"></i>
                    {% endif %}
                </h5>
                <div style="padding: 0 15px 0 15px">
                    {% if item.is_course_student and item.is_course_teacher == False %}
                    {{ item.progress }}
                    {% endif %}
                </div>
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
    <div class="tuile_description container" style="display: none; background-color: white; position: relative; flex-wrap: wrap; margin-top: 65px !important;">
        <div class="row">
        <div class="col-md-6" style=" border-right: 1px solid black; font-weight: bold" " >
            <p style="margin: 15px 5px 15px 5px">{{item.description}}</p>
        </div>
        <div class="col-md-6" style=" padding-top: 60px; padding-left: 25px;" >
            <p>Passez à l'action sur votre territoire. Trouvez les solutions pour agir ?
                Voici l'intention de ce parcours. </p>
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
        <button class="close_tuile_description" style="position: absolute;top: -8px;right: -8px;width: 34px;height: 34px;border-radius: 17px;border: solid 1px gray;background-color: white;z-index: 2600;">X</button>
        </div>
</div>
    </div>



{% endif %}

{% endfor %}

<script src="main/template/overrides/javascript/acceuille_tuile.js"></script>
<style>

</style>
{% endblock %}