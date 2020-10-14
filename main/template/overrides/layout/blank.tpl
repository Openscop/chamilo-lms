<!DOCTYPE html>
<!--[if lt IE 7]> <html lang="{{ document_language }}" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html lang="{{ document_language }}" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html lang="{{ document_language }}" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="{{ document_language }}" class="no-js"> <!--<![endif]-->
<head>
    {% block head %}
    {% include 'layout/head.tpl'|get_template %}
    {% endblock %}
</head>
<body class="{{ 'page_origin' ? ('page_origin_' ~ page_origin) : '' }}">
<!-- START MAIN -->
<main id="main" dir="{{ text_direction }}" class="{{ section_name }} {{ login_class }}">
    <noscript>{{ "NoJavascript"|get_lang }}</noscript>

    {% if displayCookieUsageWarning == true %}
    <!-- START DISPLAY COOKIES VALIDATION -->
    <div class="toolbar-cookie alert-warning">
        <form onSubmit="$(this).toggle('slow')" action="" method="post">
            <input value=1 type="hidden" name="acceptCookies"/>
            <div class="cookieUsageValidation">
                {{ 'YouAcceptCookies' | get_lang }}
                <span style="margin-left:20px;" onclick="$(this).next().toggle('slow'); $(this).toggle('slow')">
                                ({{"More" | get_lang }})
                            </span>
                <div style="display:none; margin:20px 0;">
                    {{ "HelpCookieUsageValidation" | get_lang}}
                </div>
                <span style="margin-left:20px;" onclick="$(this).parent().parent().submit()">
                                ({{"Accept" | get_lang }})
                            </span>
            </div>
        </form>
    </div>
    <!-- END DISPLAY COOKIES VALIDATION -->
    {% endif %}

    {% if show_header == true %}
    <!-- START HEADER -->
    <header id="cm-header">
        {% include 'layout/page_header.tpl'|get_template %}
    </header>

    {% endif %}

    <!-- START CONTENT -->
    <section id="cm-content">
        <div class="container-fluid" style="margin: 0 40px;">
            <div class="row">
                <h1 class="homepage-title">Bienvenue dans l'espace pro de Super Demain produit pour vous permettre de suivre des parcours en ligne contenant des vidéos explicatives, des interviews, des exercices à faire et des fiches à télécharger...</h1>
            </div>
            <div class="row" style="display: flex;
                                    flex-wrap: wrap;">
                {% if show_course_shortcut is not null %}
                <!-- TOOLS SHOW COURSE -->
                {{ show_course_shortcut }}
                <!-- END TOOLS SHOW COURSE -->
                {% endif %}

                {% block breadcrumb %}
                {{ breadcrumb }}
                {% endblock %}

                {% block body %}
                {{ content }}
                {% endblock %}
            </div>
        </div>
    </section>
    <!-- END CONTENT -->

    {% if show_footer == true %}
    <!-- START FOOTER -->
    <footer class="footer">
        {% include 'layout/page_footer.tpl'|get_template %}
    </footer>
    <!-- END FOOTER -->
    {% endif %}

</main>
<!-- END MAIN -->

{% include 'layout/modals.tpl'|get_template %}
</body>
</html>
