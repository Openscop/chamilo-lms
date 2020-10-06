{% if not courses is empty %}
<div class="classic-courses">
    <div class="panel panel-default">
        <div class="panel-body">

            {% for item in courses %}
                {% if item.title %}
                    <div class="col-xs-12 col-sm-5 col-md-4 tuile" data-url="{{ item.public_url }}"
                         title="reprendre le cours"
                         style="margin-left: 0px; margin-top: 0px; padding: 15px; height: 300px;">
                        <div class="thumbnail">
                            <div class="items items-hotcourse">
                                <div class="image card-img-top">
                                    <img src="{{ item.course_image_large }}" class="img-responsive" alt="{{ item.title }}">
                                </div>
                                <div class="block-title">
                                    <h5 class="title">
                                        {{ item.title}}
                                    </h5>
                                    <div style="padding: 0 15px 0 15px">
                                        {% if item.is_course_student and item.is_course_teacher == False %}
                                        {{ item.progress }}
                                        {% endif %}
                                    </div>
                                    <div class="ranking">
                                        {{ item.rating_html }}
                                    </div>
                                </div>
                                <div class="toolbar row">
                                    {{ item.tag }}
                                </div>
                            </div>
                        </div>

                    </div>
                {% endif %}
            {% endfor %}
        </div>

    </div>
</div>

<script src="main/template/overrides/javascript/acceuille_tuile.js"></script>
{% endif %}