{% if not courses is empty %}
<div class="classic-courses">
    <div class="panel panel-default">
        <div class="panel-body">
    {% for item in courses %}
    {% if item.title %}


    <div class="col-xs-12 col-sm-5 col-md-4 tuile" style="margin-left: 0px; margin-top: 0px; padding: 15px; height: 300px;">
        <div class="thumbnail" >
            <div class="items items-hotcourse">
                <div class="image card-img-top">
                    <a title="{{ item.title }}" href="{{ _p.web }}course/{{ item.real_id  }}/about">
                        <img src="{{ item.image }}" class="img-responsive" alt="{{ item.title }}">
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
                            {% if item.current_user_is_teacher %}
                            <a href="{{ item.edit_actions }}" style="color: initial !important;">
                                <em class="fa fa-pencil" style="color: initial !important;"></em>
                            </a>
                            {% endif %}
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
                <div class="unsubscribe_button">
                    {{ item.unsubscribe_button }}
                </div>

            </div>
        </div>
    </div>


    {% endif %}
    {% endfor %}
        </div>

    </div>
</div>
<script>
    $(function() {
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

        color_tuile();

    });

</script>
{% endif %}