<?php $session = $this->session->get('auth'); ?>
<?php if ($session != null) { ?>
	<script type="text/javascript">
		document.addEventListener( 'DOMContentLoaded', function () {
			Header.update();
		}, false );
	</script>
<?php } ?>




<nav id="header" class="navbar navbar-default navbar-static-top">
	<div class="container-fluid">
		<div class="navbar-header">
			
			<button type="button" class="btn btn-primary navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse,#pages-list,#categories-list" aria-expanded="false">
				<span class="glyphicon glyphicon-menu-hamburger"></span>
				&nbsp;
				Menu
			</button>
			
			<a class="navbar-brand" href="/" style="padding:0;margin-left:0px;"><img alt="Brand" src="/img/logo-02.jpg"></a>
		</div>

		<div class="collapse navbar-collapse" id="bs-navbar-collapse">
			<ul class="nav navbar-nav navbar-left">
				<li class=""><a href="#"><strong>Sugestão de Listas</strong></a></li>
				<li class=""><a href="#"><strong>Todos os Fabricantes</strong></a></li>
				<li class=""><a href="#"><strong>Ajuda</strong></a></li>
				<li class=""><a href="/contato/index"><strong>Contatos</strong></a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right" style="margin-right:0px;">
				<li>
					<form action="/produto/customSearch" method="GET" class="navbar-form">
						<div class="form-group">
							<input name="searchquery" type="text" class="form-control" style="min-width:20em;" placeholder="procurar por…">
						</div>
						<button type="submit" class="btn btn-default">
							<span class="glyphicon glyphicon-search"></span>
							&nbsp;
							Busca
						</button>
					</form>
				</li>
				<li class="navbar-nav--actions">
					<div class="">
						<a href="/cart/index" role="button" class="btn btn-primary navbar-btn" id='cartButton' style="display: none" >
							<span class="glyphicon glyphicon-shopping-cart"></span>
							&nbsp;
							Orçamento
							&nbsp;
							<span class="badge" style="float:right;display:;"></span>
						</a>
					</div>
				</li>
				<?php if ($auth['id']) { ?>
					<li class="navbar-nav--actions">
						<div class="">
							<a href="/session/end" role="button" class="btn btn-default navbar-btn">
								<span class="glyphicon glyphicon-log-out"></span>
								&nbsp;
								Sair
							</a>
						</div>
					</li>
				<?php } ?>
				<?php if (!$auth['id']) { ?>
					<li class="navbar-nav--actions">
						<div>
							<a role="button" class="btn btn-success navbar-btn" href="/session/index">
								<span class="glyphicon glyphicon-lock" style="font-size:12px;"></span>
								&nbsp;
								Acessar
							</a>
						</div>
					</li>
				<?php } ?>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>




<?php if (0) { ?>
<nav id="header" class="navbar navbar-default navbar-static-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/" style="padding:0;"><img alt="Brand" src="/img/logo-02.jpg"></a>
		</div>

		<div class="collapse navbar-collapse" id="bs-navbar-collapse">
			<ul class="nav navbar-nav navbar-left">
				<li class=""><a href="#"><strong>Sugestão de Listas</strong></a></li>
				<li class=""><a href="#"><strong>Todos os Fabricantes</strong></a></li>
				<li class=""><a href="#"><strong>Ajuda</strong></a></li>
				<li class=""><a href="/contato/index"><strong>Contatos</strong></a></li>
			</ul>
			<ul class="nav navbar-nav navbar-left">
				<?php if (!$auth['id']) { ?>
					<li class="auth-loged-out" >
						<form action="/session/index" style="margin:0;">
							&nbsp;
							<button type="submit" class="btn btn-default navbar-btn">
								<span class="glyphicon glyphicon-user" style="font-size:12px;"></span>
								Acessar
							</button>
						</form>
					</li>
				<?php } ?>
				<?php if ($auth['id']) { ?>
					<li>
						&emsp;
						<div class="btn-group">

							<button onclick="location.href='/dashboard/index';" type="button" class="btn btn-default navbar-btn">
								<span class="glyphicon glyphicon-cog"></span>
								&nbsp;
								Dashboard
							</button>
							<!--
							<button onclick="location.href='/conta/index';" type="button" class="btn btn-default navbar-btn">
								<span class="glyphicon glyphicon-cog"></span>
								&nbsp;
								Minha conta
								&nbsp;
								<span id="accountTasks" class="badge">&hellip;</span>
							</button>
							-->
							<button type="button" class="btn btn-default navbar-btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							</button>
							<ul class="dropdown-menu">
								<!--
								<li><a href="/dashboard/index">
									<span class="glyphicon glyphicon-dashboard"></span>
									&nbsp;
									Dashboard
								</a></li>
								-->
								<!-- <li role="separator" class="divider"></li> -->
								<li><a href="/conta/index">
									<span class="glyphicon glyphicon-cog"></span>
									&nbsp;
									Minha conta
									&nbsp;
									<!-- <span id="accountTasks" class="badge" style="float:right;">&hellip;</span> -->
								</a></li>
								<li><a href="#">
									<span class="glyphicon glyphicon-lock"></span>
									&nbsp;
									Meus dados de acesso
								</a></li>
								<li><a href="/contato/search">
									<span class="glyphicon glyphicon-bell"></span>
									&nbsp;
									Mensagens
									<span id="msgtasks" class="badge" style="float:right;"></span>
								</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="/cliente/index">
									<span class="glyphicon glyphicon-user"></span>
									&nbsp;
									Clientes
									<span id="clientstasks" class="badge" style="float:right;"></span>
								</a></li>
								<li><a href="/usuario/index">
									<span class="glyphicon glyphicon-asterisk"></span>
									&nbsp;
									Usuários
									<span id="userstasks" class="badge" style="float:right;"></span>
								</a></li>
								<li><a href="/orcamento/index">
									<span class="glyphicon glyphicon-list-alt"></span>
									&nbsp;
									Orçamentos
									<span id="invoicestasks" class="badge" style="float:right;"></span>
								</a></li>
								<li><a href="/produto/admin">
									<span class="glyphicon glyphicon-tags"></span>
									&nbsp;
									Produtos
								</a></li>
								<li><a href="#">
									<span class="glyphicon glyphicon-book"></span>
									&nbsp;
									Logs
								</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="/session/end">
									<span class="glyphicon glyphicon-off"></span>
									&nbsp;
									Sair
								</a></li>
							</ul>
						</div>
					</li>
				<?php } ?>
				<li>
					<form action="/produto/customSearch" method="GET" class="navbar-form">
						<div class="form-group">
							<input name="searchquery" type="text" class="form-control" style="width:20em;" placeholder="procurar por…">
						</div>
						<button type="submit" class="btn btn-default">
							<span class="glyphicon glyphicon-search"></span>
							&nbsp;
							Busca
						</button>
					</form>
				</li>
				<li>
					&emsp;
					<div class="btn-group">
						<a href="/cart/index" role="button" class="btn btn-primary navbar-btn" id='cartButton' style="display: none" >
							<span class="glyphicon glyphicon-shopping-cart"></span>
							&nbsp;
							Orçamento
							&nbsp;
							<span class="badge" style="float:right;display: ;"></span>
						</a>
						<!-- <button type="button" class="btn btn-default navbar-btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="caret"></span>
							<span class="sr-only">Toggle Dropdown</span>
						</button>
						<ul class="dropdown-menu">
							<li><a href="/orcamento/index">Ver orçamentos</a></li>
						</ul> -->
					</div>
				</li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
<?php } ?>
