
{{ content() }}

<div class="page-header"><h1>Novo produto</h1></div>

{{ form("produto/create", 'method': 'post', 'id': 'form-produto-create') }}

	<fieldset>

		{% for element in form %}
			{% if is_a(element, 'Phalcon\Forms\Element\Hidden') %}
				{{ element }}
			{% else %}
				<div class="form-group">
					{{ element.label() }}
					{{ element.render(['class': 'form-control']) }}
				</div>
			{% endif %}
		{% endfor %}



	</fieldset>

	<div class="btn-toolbar">
		<a class="btn btn-default" href="/produto/resetParams" role="button">
			<span class="glyphicon glyphicon-arrow-left"></span>
			&nbsp; 
			Voltar
		</a>
		<button type="submit" class="btn btn-success">
			<span class="glyphicon glyphicon-floppy-disk"></span>
			&nbsp; 
			Salvar
		</button>
	</div>

</form>
