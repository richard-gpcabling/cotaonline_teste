<? $i=1 ?> 

<h1>
    Listas Prontas
</h1>

<br>

{% if listas is not defined or listas|length == 0 %}
    <h2>Ainda não há nenhuma lista criada</h2>
{% else %}
    {% for item in listas %}
    {% if i == 1%}
        <div class="row">
    {% endif %}
    <div class="col-sm-12 col-md-4">
    <div class="thumbnail" style="min-height:300px; background-color:#005ca9;">
        {# <img src="https://img.estadao.com.br/resources/jpg/1/0/1484933925101.jpg" alt="..."> #}
        <div class="caption">
        <h3 style="color:#fff">{{item.titulo}}</h3>

        <p style="font-family:Sans-serif,helvetica,arial; color:white;font-size:18px;letter-spacing:1px;">
        {{item.descricao}}
        </p><br>
        <p><a href="/listasprontas/list/{{item.slug}}" class="btn-lg btn-success" role="button" targe="_blank">
        Ver Lista
        </a>
        </div>
    </div>
    </div>
    {% if i == 3 %}
        </div>
    <? $i=0; ?>
    {% endif %}
    <? $i++; ?>
    {% endfor %}
{#
    <div class="jumbotron" style="background-color:#005ca9; max-width:250px;">
        <h1 style="color:#fff">{{item.titulo}}</h1>
        <p style="font-family:Sans-serif,helvetica,arial; color:white">
        {{item.descricao}}
        </p>
        <p><a class="btn btn-success btn-lg" href="/listasprontas/list/{{item.slug}}" role="button">Ver lista</a></p>
    </div>


#}
{% endif %}

