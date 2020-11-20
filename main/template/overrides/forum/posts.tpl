{% import 'default/macro/macro.tpl' as display %}
{% extends 'layout/layout_1_col.tpl'|get_template %}

{% block content %}
    <div class="globalForumThread container container-970">
        {% if origin == 'learnpath' %}
            <div style="height:15px">&nbsp;</div>
        {% endif %}

        {% if forum_actions %}
            <div class="actions customActions">
                {{ forum_actions }}
            </div>
        {% endif %}

        <h1 class="globalForumThread-title"><p>{{ thread_title }}</p></h1>

        {% for post in posts %}
            {% set post_data %}
                <div class="row">
                    {% set highlight = '' %}
                    {% if post.current %}
                        {% set highlight = 'alert alert-danger' %}
                    {% endif %}

                    {% set highlight_revision = '' %}
                    {% if post.is_a_revision %}
                        {% set highlight_revision = 'forum_revision' %}
                    {% endif %}

                    <div class="col-md-12 {{ highlight }} ">
                        <div class="globalForumThread-userData">
                            <span class="globalForumThread-userData-image">{{ post.user_data.image }}</span>
                            <span class="globalForumThread-userData-name">{{ post.user_data.name }}</span>,&nbsp;
                            <span class="globalForumThread-userData-date">{{ post.user_data.date }}&nbsp;:</span>
                        </div>
                        {{ post.post_title }}

                        {% if post.is_a_revision %}
                           {{ 'ProposedRevision' | get_lang  }} {{ post.flag_revision }}
                        {% endif %}

                        <div class="{{ highlight_revision }} ">
                            {{ post.post_data }}
                        </div>

                        {{ post.post_attachments }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-8 text-right">
                        {{ post.post_buttons }}
                    </div>
                </div>
            {% endset %}

            {% if view_mode == 'nested' %}
                <div class="col-md-offset-{{ post.indent_cnt }} forum-post">
                    {{ display.panel('', post_data) }}
                </div>
            {% else %}
                <div class="col-md-12 forum-post">
                    {{ display.panel('', post_data) }}
                </div>
            {% endif %}
        {% endfor %}

        <div id="newResponse">
            <button class="btn btn-primary customBtn-large">Ajouter une réponse</button>
        </div>

        <div class="row hidden" id="responseForm">
            <div class="col-md-12">
                <label>Répondre au sujet :</label>
                {{ form }}
            </div>
        </div>

    </div>

    <script>
        $('#newResponse button').on('click', function(e) {
            e.preventDefault();
            $('#newResponse').addClass('hidden');
            $('#responseForm').removeClass('hidden');
        });

        $('#thread_Cancel').on('click', function(e) {
            e.preventDefault();
            $('#newResponse').removeClass('hidden');
            $('#responseForm').addClass('hidden');
        });
    </script>
{% endblock %}
