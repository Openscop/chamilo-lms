<div class="row">
    {% for item in courses %}
    <div class="col">
        <p>courses :</p>
        {{ item|var_dump }}
    </div>
    {% endfor %}
    {% for item in session %}
    <div class="col">
        <p>session :</p>
        {{ item|var_dump }}
    </div>
    {% endfor %}
    {% for item in categories %}
    <div class="col">
        <p>categories :</p>
        {{ item|var_dump }}
    </div>
    {% endfor %}
</div>