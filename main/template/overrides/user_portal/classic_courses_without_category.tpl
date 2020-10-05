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
        <div class="triangle" style="width: 150px; height: 150px; position: absolute; z-index: 0; background-color: white; transform: skew(-45deg, 45deg); margin-left: 100px; top: 440px; display: none;">

        </div>
        <div class="tuile_description " style="display: none; background-color: white; position: relative;">
            <div style="width: 450px; border-right: 1px solid black">
                <h2>Description :</h2>
                <p>L’État soutient le déploiement national du dispositif de Pass numériques afin de garantir et de favoriser l’accès aux usages numériques de tous les Français, notamment les plus éloignés. Le dispositif de Pass numériques donne le droit d’accéder - dans des structures de proximité, préalablement qualifiées et mettant à disposition des professionnels de qualité - à des services d’accompagnement numérique avec une prise en charge totale ou partielle par un tiers-payeur.</p>
            </div>
            <div style="width: 450px; padding-top: 60px; padding-left: 25px">
                <p>Passez à l'action sur votre territoire. Trouvez les solutions pour agir ?
                    Voici l'intention de ce parcours. </p>
                {{ item.go_to_course_button }}
            </div>
            <button class="close_tuile_description" style="position: absolute;top: -8px;right: -8px;width: 34px;height: 34px;border-radius: 17px;border: solid 1px gray;background-color: white;z-index: 2600;">X</button>

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