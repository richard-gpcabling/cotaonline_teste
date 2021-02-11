
{{ content() }}

<div class="page-header"><h1>Esqueci minha senha</h1></div>


<div class="well">
	<form action="/session/forgot" role="form" method="post">   
		<div class="form-group">
			<label for="email">Email</label>
			<div class="controls">
				{{ text_field('email', 'class': "form-control") }}
			</div>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary btn-large">
				<span class="glyphicon glyphicon-envelope"></span>
				&nbsp;
				Recuperar
			</button>
		</div>
	</form>
</div>
