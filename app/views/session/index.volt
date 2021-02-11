
{{ content() }}

<div class="row">

	<div class="col-md-6">
		<div class="well well-sm">
		<div class="page-header"><h1>Acessar</h1></div>
			<fieldset>
				<form action="/session/start" role="form" method="post">
					<div class="form-group">
						<label for="email">Email</label>
						<div class="controls">
							<input type="text" id="email" name="email" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label for="password">Senha</label>
						<div class="controls">
							<input type="password" id="password" name="password" class="form-control">
						</div>
					</div>
					<br>
					<div class="form-group text-center">
						<button type="submit" class="btn btn-primary btn-large">
							<span class="glyphicon glyphicon-lock"></span>
							&nbsp;
							Login
						</button>
						<br><br>
						<a href="/session/forgot" role="submit" class="btn btn-text btn-large">
							<span class="text-danger">
								<span class="glyphicon glyphicon-envelope"></span>
								&nbsp;
								Esqueci minha senha
							</span>
						</a>
					</div>
				</form>
			</fieldset>
		</div>
	</div>

	<div class="col-md-6">
		<div class="unassociated well">
			<h1><b>Ainda não possui um cadastro?</b></h1>
			<br>
			<h5>Criar uma conta lhe dá os seguintes benefícios:</h5>
			<br>
			<p>
				<ul>
					<li><b>Visualizar os valores de seu orçamento</b></li>
					<li>Acompanhar seus orçamentos</li>
					<li>Acesso a todos os produtos oferecidos pelo GP Cabling</li>
				</ul>
			</p>
			<br><br>
			<p class="text-center">
				<a href="/register" role="button" class="btn btn-success btn-lg">
					<span class="glyphicon glyphicon-thumbs-up"></span>
					&nbsp;
					Cadastre-se
					&nbsp;
				</a>
			</p>
		</div>
	</div>
</div>
