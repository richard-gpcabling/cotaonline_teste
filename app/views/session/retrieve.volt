{% include "elements/message-texts.volt" %}

{{ content() }}

<div class="page-header"><h2>Alterar senha</h2></div>

{{ form('session/retrieve', 'id': 'retrieveForm', 'onbeforesubmit': 'return false') }}

	<fieldset>

		<div class="control-group">
			{{ form.label('email', ['class': 'control-label']) }}
			<div class="controls">
				{% if email is defined %}
					{{ form.render('email', ['class': 'form-control','value':email]) }}
				{% else %}
					{{ form.render('email', ['class': 'form-control']) }}
				{% endif %}
				<p class="help-block">(obrigatório)</p>
				<div class="alert alert-warning" id="email_alert" style="display:none">
					<span class="glyphicon glyphicon-warning-sign"></span>
					&nbsp;
					{{ message['session/retrieve:1'] }}
				</div>
			</div>
		</div>

		<div class="control-group">           
			{{ form.label('code', ['class': 'control-label']) }}
			<div class="controls">
				{% if code is defined %}
				 {{ form.render('code', ['class': 'form-control','value':code]) }}          
				{% else %}
				 {{ form.render('code', ['class': 'form-control']) }}          
				{% endif %}
				<p class="help-block">(obrigatório)</p>
				<div class="alert alert-warning" id="code_alert" style="display:none">
					<span class="glyphicon glyphicon-warning-sign"></span>
					&nbsp;
					{{ message['session/retrieve:2'] }}
				</div>
			</div>
		</div>
		<div class="control-group">
			{{ form.label('password', ['class': 'control-label']) }}
			<div class="controls">
				{{ form.render('password', ['class': 'form-control']) }}
				<p class="help-block">(minímo 8 caracteres)</p>
				<div class="alert alert-warning" id="password_alert" style="display:none">
					<span class="glyphicon glyphicon-warning-sign"></span>
					&nbsp;
					{{ message['session/retrieve:3'] }}
				</div>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="repeatPassword">Confirme</label>
			<div class="controls">
				<input type="password" id="repeatPassword" name="repeatPassword" class="form-control">
				<p class="help-block">(obrigatório)</p>
				<div class="alert alert-warning" id="repeatPassword_alert" style="display:none">
					<span class="glyphicon glyphicon-warning-sign"></span>
					&nbsp;
					{{ message['session/retrieve:4'] }}
				</div>
			</div>
		</div>

		<br>

		<div class="form-actions">
			<button type="submit" id="mySubmit" value="" class="btn btn-primary" onclick="return SignIn.retrieve(this);">
				<span class="glyphicon glyphicon-pencil"></span>
				&nbsp;
				Alterar
			</button>
		</div>

	</fieldset>
</form>
