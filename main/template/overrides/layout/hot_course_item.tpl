{% extends 'layout/blank.tpl'|get_template %}
{% block body %}

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

{% endblock %}
