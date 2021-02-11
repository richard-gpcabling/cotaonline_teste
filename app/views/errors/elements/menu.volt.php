




<?php if ($auth['id']) { ?>
	<div class="hidden-xs" id="pages-list" style="overflow:hidden;">
		<div class="panel panel-primary panel-primary-info">
			<ul class="list-group _small">
				<li class="list-group-item"><a class="" href="/">
					<span class="glyphicon glyphicon-home"></span>
						&nbsp;
						Início
				</a></li>
				<li class="list-group-item"><a class="" href="/dashboard/index">
					<span class="glyphicon glyphicon-dashboard"></span>
						&nbsp;
						Dashboard
				</a></li>
				<li class="list-group-item"><a class="" href="/conta/index">
					<span class="glyphicon glyphicon-cog"></span>
					&nbsp;
					Minha conta
					&nbsp;
					<!-- <span id="accountTasks" class="badge" style="float:right;">&hellip;</span> -->
				</a></li>
				<!-- <li class="list-group-item"><a class="" href="#">
					<span class="glyphicon glyphicon-lock"></span>
					&nbsp;
					Meus dados de acesso
				</a></li> -->
				<li class="list-group-item"><a class="" href="/contato/search">
					<span class="glyphicon glyphicon-bell"></span>
					&nbsp;
					Mensagens
					<span id="msgtasks" class="badge" style="float:right;"></span>
				</a></li>
				<li class="list-group-item"><a class="" href="/cliente/index">
					<span class="glyphicon glyphicon-user"></span>
					&nbsp;
					Clientes
					<span id="clientstasks" class="badge" style="float:right;"></span>
				</a></li>
				<li class="list-group-item"><a class="" href="/usuario/index">
					<span class="glyphicon glyphicon-certificate"></span>
					&nbsp;
					Usuários
					<span id="userstasks" class="badge" style="float:right;"></span>
				</a></li>
				<li class="list-group-item"><a class="" href="/orcamento/index">
					<span class="glyphicon glyphicon-list-alt"></span>
					&nbsp;
					Orçamentos
					<span id="invoicestasks" class="badge" style="float:right;"></span>
				</a></li>
				<li class="list-group-item"><a class="" href="/produto/admin">
					<span class="glyphicon glyphicon-tags"></span>
					&nbsp;
					Produtos
				</a></li>
				<li class="list-group-item"><a class="" href="#">
					<span class="glyphicon glyphicon-book"></span>
					&nbsp;
					Logs
				</a></li>
			</ul>
		</div>
	</div>
<?php } ?>

<div id="categories-list" class="list-group _small hidden-xs" style="overflow:hidden;">
	<?php foreach ($categoriesArray as $category) { ?>
		<?php if ($category['status'] == 0) { ?>
			<?php $itemCount = 0; ?><?php foreach ($subCategorieslvl1Array as $subcategorylvl1) { ?><?php if ($subcategorylvl1['parent'] == $category['id']) { ?><?php if ($subcategorylvl1['status'] == 0) { ?><?php $itemCount = $itemCount + 1; ?><?php } ?><?php } ?><?php } ?>
			<a href="/produto/category/<?= $category['id'] ?>"  id="category-00-<?= $category['id'] ?>" class="list-group-item menu-visible level-0 no-parent" data-toggle-target=".parent-00-<?= $category['id'] ?>" data-item-count="<?= $itemCount ?>">
			<?php if ($itemCount > 0) { ?>
				<span class="glyphicon glyphicon-triangle-right ">&nbsp;</span>
			<?php } ?>
				<?= $category['nome'] ?>
			</a>
			<?php foreach ($subCategorieslvl1Array as $subcategorylvl1) { ?>
				<?php if ($subcategorylvl1['parent'] == $category['id']) { ?>
					<?php if ($subcategorylvl1['status'] == 0) { ?>
						<?php $itemCount = 0; ?><?php foreach ($subCategorieslvl2Array as $subcategorylvl2) { ?><?php if ($subcategorylvl2['parent'] == $subcategorylvl1['id']) { ?><?php if ($subcategorylvl2['status'] == 0) { ?><?php $itemCount = $itemCount + 1; ?><?php } ?><?php } ?><?php } ?>
						<a href="/produto/subcategory1/<?= $subcategorylvl1['id'] ?>"  id="category-01-<?= $subcategorylvl1['id'] ?>" class="list-group-item menu-collapsed level-1 parent-00-<?= $category['id'] ?>" data-toggle-target=".parent-01-<?= $subcategorylvl1['id'] ?>" data-item-count="<?= $itemCount ?>" data-parent="category-00-<?= $category['id'] ?>">
						<?php if ($itemCount > 0) { ?>
							<span class="glyphicon glyphicon-triangle-right ">&nbsp;</span>
						<?php } ?>
							<?= $subcategorylvl1['nome'] ?>
						</a>
						<?php foreach ($subCategorieslvl2Array as $subcategorylvl2) { ?>
							<?php if ($subcategorylvl2['parent'] == $subcategorylvl1['id']) { ?>
								<?php if ($subcategorylvl2['status'] == 0) { ?>
									<?php $itemCount = 0; ?><?php foreach ($subCategorieslvl3Array as $subcategorylvl3) { ?><?php if ($subcategorylvl3['parent'] == $subcategorylvl2['id']) { ?><?php if ($subcategorylvl3['status'] == 0) { ?><?php $itemCount = $itemCount + 1; ?><?php } ?><?php } ?><?php } ?>
									<a href="/produto/subcategory2/<?= $subcategorylvl2['id'] ?>"  id="category-02-<?= $subcategorylvl2['id'] ?>" class="list-group-item menu-collapsed level-2 parent-01-<?= $subcategorylvl1['id'] ?>" data-toggle-target=".parent-02-<?= $subcategorylvl2['id'] ?>" data-item-count="<?= $itemCount ?>" data-parent="category-01-<?= $subcategorylvl1['id'] ?>">
										<?php if ($itemCount > 0) { ?>
											<span class="glyphicon glyphicon-triangle-right ">&nbsp;</span>
										<?php } ?>
										<?= $subcategorylvl2['nome'] ?>
									</a>
									<?php foreach ($subCategorieslvl3Array as $subcategorylvl3) { ?>
										<?php if ($subcategorylvl3['parent'] == $subcategorylvl2['id']) { ?>
											<?php if ($subcategorylvl3['status'] == 0) { ?>
												<a href-"/produto/subcategory3/<?= $subcategorylvl3['id'] ?>" id="category-03-<?= $subcategorylvl3['id'] ?>" class=" glyphicon glyphicon-triangle-right list-group-item menu-collapsed level-3 parent-02-<?= $subcategorylvl2['id'] ?>" data-toggle-target="" data-item-count="0" data-parent="category-02-<?= $subcategorylvl2['id'] ?>">
													<?= $subcategorylvl3['nome'] ?>
												</a>
											<?php } ?>
										<?php } ?>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	<?php } ?>
</div>



<!--
<div class="panel panel-default">
	<ul class="list-group">
		<li class="list-group-item"><a href="#">Sugestões de lista</a></li>
		<li class="list-group-item"><a href="#">Fabricantes</a></li>
		<li class="list-group-item"><a href="#">Mapa do site</a></li>
	</ul>
</div>
-->