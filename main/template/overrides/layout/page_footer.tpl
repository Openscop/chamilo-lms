<div id="footer">
    {% if plugin_pre_footer is not null %}
    <div id="plugin_pre_footer">
        {{ plugin_pre_footer }}
    </div>
    {% endif %}
    <section class="sub-footer">
        <img src="http://pros.superdemain.fr/main/img/gallery/logo_footer.png" />
        <p><strong><a href="https://www.frequence-ecoles.org/" title="Site de Fréquence Écoles" target="_blank">Fréquence Écoles</a></strong> vous donne l'autorisation de copier et d'utiliser l'ensemble des contenus pédagogiques développés pour <strong><a href="https://www.superdemain.fr/" title="Site de Super Demain" target="_blank">Super Demain</a>.</strong></p>
        {% if footer_extra_content  %}
            {{ footer_extra_content }}
        {% endif %}
    </section>
</div>
{{ execution_stats }}