<!DOCTYPE html>
<!--[if lt IE 7]> <html lang="{{document_language}}" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html lang="{{document_language}}" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html lang="{{document_language}}" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--><html lang="{{document_language}}" class="no-js"> <!--<![endif]-->
<head>
{% include 'layout/head.tpl'|get_template %}

    <style>
        html, body, .learnpath-container, .content-scorm, .content-scorm > *,  .lp-view-tabs, #tab-iframe, #tab-iframe > *, #wrapper-iframe, #wrapper-iframe > iframe{
            height:100%;
            background-color: #240A5F;
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