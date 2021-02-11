<h1>
    Criar Nova Lista
</h1>
<form action="/listasprontas/create" method="POST">
<div class="row">
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="sizing-addon1">Título</span>
      {% if titulo is defined %}
        <input type="text" name="titulo" id="titulo" value="{{titulo}}" class="form-control" placeholder="Título" aria-describedby="sizing-addon1" oninput="slugFy()">
      {% else %}
        <input type="text" name="titulo" id="titulo" class="form-control" placeholder="Título" aria-describedby="sizing-addon1" oninput="slugFy()">
      {% endif %}
    </div>
</div>

<br>

<div class="row">
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="sizing-addon2">Slug &nbsp;</span>
      {% if slug is defined %}
        <input type="text" name="slug" id="slug" value="{{slug}}" class="form-control" placeholder="Slug" aria-describedby="sizing-addon2" readonly>
      {% else %}
        <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug" aria-describedby="sizing-addon2" readonly>
      {% endif %}
    </div>
</div>

<br>

<div class="row">
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="sizing-addon3">Descricao &nbsp;</span>
      {% if descricao is defined %}
        <input type="text" name="descricao" id="descricao" value="{{descricao}}" class="form-control" placeholder="Descrição da lista para nossos clientes" aria-describedby="sizing-addon3">
      {% else %}
        <input type="text" name="descricao" id="descricao" class="form-control" placeholder="Descrição da lista para nossos clientes" aria-describedby="sizing-addon3">
      {% endif %}
    </div>
</div>

<br>

<div class="row">
  <h4>
    <input type="checkbox" name="status" value="1" style="transform : scale(3); margin-top:20px; margin-right:10px; margin-left:25px;"
    {% if status is not defined or status == 1 %}checked{% endif %}>
    Ativo
  </h4>
</div>

<br>

<div class="row">
    <h5>Adicionar produtos</h5>
    <div class="input-group-lg">
        <input type="text" id="myInput" onkeyup="buscaProduto()"
            placeholder="Buscar produto.." title="" class="form-control">
    </div>
</div>

<div id="selected" style="font-size: 18px; margin-top:10px;">

</div>

<br>

<div id="search_category_list" style="height: 250px; overflow-x: hidden; padding-left:30px;">
    <div class="row" id="myUL">
    {% for produto in produtos %}
        <div style="padding-bottom:10px;" >
            <a style="font-size:18px;" >
            <?
            /**
            * Incluir o value no on click showSelected
            */
            ?>
            <input type="checkbox" name="produtos[]" value="{{produto.codigo_produto}}" id="myCheck{{produto.codigo_produto}}"
            onclick="showSelected({{produto.codigo_produto}})" style="transform : scale(3); margin-top:20px; margin-right:15px;"
            <? if (isset($prod_array) AND in_array($produto->codigo_produto,$prod_array)) { echo 'checked'; }?> >
                {{produto.codigo_produto}} — {{produto.fabricante_nome}} — {{produto.descricao_site}}
            </input>
            </a>
        </div>
    {% endfor %}
    </div>
</div>

<br>

<small>
*Produtos seguirão a ordem de estoque por padrão
</small>

<br>
  <a href="/listasprontas/index" role="button" class="btn btn-danger btn-lg"  style="align:left;">
		<span class="glyphicon glyphicon-remove"></span>
		&nbsp; 
		CANCELAR
	</a>

	<button type="submit" class="btn btn-success btn-lg"  style="align:left;">
		<span class="glyphicon glyphicon-ok"></span>
		&nbsp; 
		SALVAR LISTA
	</button>
  
  <div style="clear:both;">
  </div>
</form>

<script>
var vals = "*<b>Selecionados</b>:";

{% if prod_array is defined %}
  {% for prod in prod_array%}
    vals += {{prod}} + ", ";
  {% endfor %}
  
  document.getElementById("selected").innerHTML = vals;
{% else %}

{% endif %}

function buscaProduto() {
  // Declare variables
  var input, filter, ul, li, a, i, txtValue;
  input = document.getElementById('myInput');
  filter = input.value.toUpperCase();
  ul = document.getElementById("myUL");
  li = ul.getElementsByTagName('div');

  // Loop through all list items, and hide those who don't match the search query
  for (i = 0; i < li.length; i++) {
    a = li[i].getElementsByTagName('a')[0];
    txtValue = a.textContent || a.innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      li[i].style.display = "";
    } else {
      li[i].style.display = "none";
    }
  }
}

function showSelected(codigo_produto) {
    // Get the checkbox
    var checkBox = document.getElementById("myCheck"+codigo_produto);
    // Get the output text
    var text = document.getElementById("selected");

    // If the checkbox is checked, display the output text
    if (checkBox.checked == true){
        vals += codigo_produto + ", ";
        document.getElementById("selected").innerHTML = vals;
    } else {
        vals = vals.replace(codigo_produto+", ","");
        document.getElementById("selected").innerHTML = vals.replace(codigo_produto+", ", "");
    }
}


function slugFy(){
  let str = document.getElementById("titulo").value
  str = str.replace(/^\s+|\s+$/g, ''); // trim
  str = str.toLowerCase();
  
  // remove accents, swap ñ for n, etc
  var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
  var to   = "aaaaeeeeiiiioooouuuunc------";
  for (var i=0, l=from.length ; i<l ; i++) {
      str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
  }

  str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
      .replace(/\s+/g, '-') // collapse whitespace and replace by -
      .replace(/-+/g, '-'); // collapse dashes

  document.getElementById("slug").value = str;
}


</script>