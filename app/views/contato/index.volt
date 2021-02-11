<div class="page-header"><h1>Fale conosco</h1></div>

<p>Preencha os dados abaixo para nos enviar uma mensagem</p>
{{ form("contato/send", 'role': 'form', 'data-recaptcha-action': 'Contact') }}
	<div class="well">
		<fieldset>
			{% for element in form %}
				{% if is_a(element, 'Phalcon\Forms\Element\Hidden') %}
					{{ element }}
				{% elseif is_a(element, 'Phalcon\Forms\Element\Check')  %}
					{{ element }}
					{{ element.label() }}
				{% else %}
					<div class="form-group">
						{{ element.label() }}
						{{ element.render(['class': 'form-control']) }}
					</div>
				{% endif %}
			{% endfor %}
		</fieldset>

		<br>

		<button type="submit" class="btn btn-success">
			<span class="glyphicon glyphicon-envelope"></span>
			&nbsp;
			Enviar
		</button>
	</div>

	</div>
</form>
