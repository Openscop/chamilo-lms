{% extends 'layout/blank.tpl'|get_template %}
{% block body %}

{% for item in hot_courses %}
{% if item.title %}

<div class="col-xs-12 col-sm-5 col-md-4 tuile" style="margin-left: 0px; margin-top: 0px; padding: 15px">
    <div class="thumbnail" >
    <div class="items items-hotcourse">
        <div class="image card-img-top">
            <a title="{{ item.title }}" href="#">
                <img src="{{ item.course_image_large }}" class="img-responsive" alt="{{ item.title }}">
            </a>

        </div>

            <div class="block-title">
                <h5 class="title">
                    {% if item.is_course_student or item.is_course_teacher %}
                    <a alt="{{ item.title }}" title="{{ item.title }}" href="{{ _p.web }}courses/{{ item.directory  }}/">
                        {{ item.title}}
                    </a>
                    {% else %}
                    <a alt="{{ item.title }}" title="{{ item.title }}" href="{{ _p.web }}course/{{ item.real_id  }}/about">
                        {{ item.title}}
                    </a>
                    {% endif %}
                </h5>
                <div class="ranking">
                    {{ item.rating_html }}
                </div>
            </div>

            <div class="toolbar row">
                {{ item.tag }}
            </div>

    </div>
    </div>
    <div class="triangle" style="width: 50px; height: 50px; position: absolute; z-index: 0; background-color: white; transform: skew(-45deg, 45deg); margin-left: 100px; top: 440px; display: none;">

    </div>
    <div class="tuile_description container" style="display: none; background-color: white; position: relative; flex-wrap: wrap;">
        <div class="row">
        <div class="col-md-6" style=" border-right: 1px solid black;" " >
            <h2>Description :</h2>
            <p>L’État soutient le déploiement national du dispositif de Pass numériques afin de garantir et de favoriser l’accès aux usages numériques de tous les Français, notamment les plus éloignés. Le dispositif de Pass numériques donne le droit d’accéder - dans des structures de proximité, préalablement qualifiées et mettant à disposition des professionnels de qualité - à des services d’accompagnement numérique avec une prise en charge totale ou partielle par un tiers-payeur.</p>
        </div>
        <div class="col-md-6" style=" padding-top: 60px; padding-left: 25px;" >
            <p>Passez à l'action sur votre territoire. Trouvez les solutions pour agir ?
                Voici l'intention de ce parcours. </p>
            {{ item.go_to_course_button }}
            {{ item.register_button }}
            {{ item.unsubscribe_button }}
        </div>
        <button class="close_tuile_description" style="position: absolute;top: -8px;right: -8px;width: 34px;height: 34px;border-radius: 17px;border: solid 1px gray;background-color: white;z-index: 2600;">X</button>
        </div>
</div>
    </div>



{% endif %}

{% endfor %}

<script src="main/template/overrides/javascript/acceuille_tuile.js"></script>
{% endblock %}