{% include "elements/message-texts.volt" %}

{{ content() }}

<div class="page-header"><h1>Cadastre-se</h1></div>

<div class="well">
	<form action="/register"
				id="registerForm"
				method="post"
				data-recaptcha-action="Signup">
		<fieldset>

			<div class="control-group">
				{{ form.label('name', ['class': 'control-label']) }}
				<div class="controls">
					{{ form.render('name', ['class': 'form-control']) }}
					<p class="help-block">(obrigatório)</p>
					<div class="alert alert-danger" id="name_alert">
						<span class="glyphicon glyphicon-warning-sign"></span>
						&nbsp;
						{{ message['register/index:1'] }}
					</div>
				</div>
			</div>

			<div class="control-group">
				{{ form.label('email', ['class': 'control-label']) }}
				<div class="controls">
					{{ form.render('email', ['class': 'form-control']) }}
					<p class="help-block">(obrigatório)</p>
					<div class="alert alert-danger" id="email_alert">
						<span class="glyphicon glyphicon-warning-sign"></span>
						&nbsp;
						{{ message['register/index:2'] }}
					</div>
				</div>
			</div>

			<div class="control-group">
				{{ form.label('password', ['class': 'control-label']) }}
				<div class="controls">
					{{ form.render('password', ['class': 'form-control']) }}
					<p class="help-block">(minímo 8 caracteres)</p>
					<div class="alert alert-danger" id="password_alert">
						<span class="glyphicon glyphicon-warning-sign"></span>
						&nbsp;
						{{ message['register/index:3'] }}
					</div>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="repeatPassword">Confirme</label>
				<div class="controls">
					{{ password_field('repeatPassword', 'class': 'form-control') }}
					<p class="help-block"></p>
					<div class="alert alert-danger" id="repeatPassword_alert">
						<span class="glyphicon glyphicon-warning-sign"></span>
						&nbsp;
						{{ message['register/index:4'] }}
					</div>
				</div>
			</div>

			{#
			Não consigo utilizar, de jeito algum, o Recaptcha do Google... 08/01/2020

			<div class="control-group">
				<label class="control-label" for="repeatPassword">Verificação</label>
				<input type="text" id="verificacao" name="verificacao" class="form-control">
			</div>
			#}
			<br>

			<div class="form-actions">
				<button type="submit"
								id="mySubmit"
								class="btn btn-primary"
								onclick="return SignUp.validate(this);">
					<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
					&nbsp;
					Cadastrar
				</button>
			</div>
		</fieldset>
		<br>

	</form>

</div>

<p class="text-muted">
	<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
	&nbsp;
	Ao se cadastrar você está automaticamente aceitando os <a href="#">termos de uso e privacidade</a>.
</p>

{% include "elements/message-grecaptcha.volt" %}
