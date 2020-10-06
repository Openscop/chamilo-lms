<!DOCTYPE html>
<!--[if lt IE 7]> <html lang="{{document_language}}" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html lang="{{document_language}}" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html lang="{{document_language}}" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--><html lang="{{document_language}}" class="no-js"> <!--<![endif]-->
<head>
{% include 'layout/head.tpl'|get_template %}
    <style>
        #learning_path_toc{
            position: relative !important;
            top: unset !important;
            left: unset !important;
            right: unset !important;
            bottom: unset !important;
        }

        .menu-button{
            top: 90px !important;
        }

        html, body, .learnpath-container, .content-scorm, .content-scorm > *,  .lp-view-tabs, #tab-iframe, #tab-iframe > *, #wrapper-iframe, #wrapper-iframe > iframe{
            height:100%;
        }
        #content-scorm{
            height: 90% !important;
        }
        .learnpath-container {
            display:table;
            width: 100%;
            padding: 0; /*set left/right padding according to needs*/
            box-sizing: border-box;
        }

        .row {
            height: 100%;
            display: table-row;
        }

        .row .no-float {
            display: table-cell;
            float: none;
        }
    </style>
</head>
<body dir="{{text_direction}}" class="{{section_name}}">
<header id="cm-header">
    {% include 'layout/page_header.tpl'|get_template %}
</header>
<section id="content-scorm">
{% block body %}
    {{ content }}
{% endblock %}
</section>
</body>
</html>