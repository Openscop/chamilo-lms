{% if access_url_description matches '{\w*emain\w*}' %}
    <img src="./web/img/themes/SuperDemain/logos-footer.png" alt="Partenaires de Super Demain" />
{% elseif access_url_description matches '{\w*ommun\w*}' %}
{% endif %}
<div id="footer">
    {% if plugin_pre_footer is not null %}
    <div id="plugin_pre_footer">
        {{ plugin_pre_footer }}
    </div>
    {% endif %}
    <section class="sub-footer">
        <img src="http://pros.superdemain.fr/main/img/gallery/logo_footer.png" />
        {% if access_url_description matches '{\w*emain\w*}' %}
            <p><a href="https://www.frequence-ecoles.org/" title="Site de Fréquence Écoles" target="_blank">Fréquence Écoles</a> vous donne l'autorisation de copier et d'utiliser l'ensemble des contenus pédagogiques développés pour <a href="https://www.superdemain.fr/" title="Site de Super Demain" target="_blank">Super Demain</a>. La plateforme a été développée grâce à <a href="https://chamilo.org/fr/chamilo/" title="Site de Chamilo" target="_blank">Chamilo</a>, et le soutien de <a href="https://www.zoomacom.org/" title="Site de Zoomacom" target="_blank">Zoomacom</a> et <a href="https://www.openscop.fr/" title="Site de Openscop" target="_blank">Openscop</a>.</p>
        {% elseif access_url_description matches '{\w*ommun\w*}' %}
            <p>Les ressources de la plateforme sont mises à disposition sous les termes de la licence CC BY-SA 2.0 dans le cadre de <a href="https://numerique-en-communs.fr/" title="Site de Numérique en Communs" target="_blank">Numérique en Commun[s]</a>. Elles ont été produites par <a href="https://www.frequence-ecoles.org/" title="Site de Fréquence Écoles" target="_blank">Fréquence Écoles</a>, avec le soutien financier du programme <a href="https://agence-cohesion-territoires.gouv.fr/societe-numerique-55" title="Site de Société Numérique de l’Agence Nationale de la Cohésion des Territoires" target="_blank">Société Numérique de l’Agence Nationale de la Cohésion des Territoires</a> et la mobilisation de <a href="https://lamednum.coop/" title="Site de La Mednum" target="_blank">La MedNum</a>. La plateforme a été développée grâce à <a href="https://chamilo.org/fr/chamilo/" title="Site de Chamilo" target="_blank">Chamilo</a>, et le soutien de <a href="https://www.zoomacom.org/" title="Site de Zoomacom" target="_blank">Zoomacom</a> et <a href="https://www.openscop.fr/" title="Site de Openscop" target="_blank">Openscop</a>.</p>
        {% else %}
            <p><a href="https://www.frequence-ecoles.org/" title="Site de Fréquence Écoles" target="_blank">Fréquence Écoles</a> vous donne l'autorisation de copier et d'utiliser l'ensemble des contenus pédagogiques. La plateforme a été développée grâce à <a href="https://chamilo.org/fr/chamilo/" title="Site de Chamilo" target="_blank">Chamilo</a>, et le soutien de <a href="https://www.zoomacom.org/" title="Site de Zoomacom" target="_blank">Zoomacom</a> et <a href="https://www.openscop.fr/" title="Site de Openscop" target="_blank">Openscop</a>. </p>
        {% endif %}

        {% if footer_extra_content  %}
            {{ footer_extra_content }}
        {% endif %}
    </section>
</div>
{{ execution_stats }}
