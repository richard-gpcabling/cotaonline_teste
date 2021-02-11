
<!-- BEGIN NAVIGATION -->
<nav aria-label="Page navigation">
	<ul class="pagination">
		<?php $offset = 3; ?>
		<?php $shownHellip = false; ?>
		<?php foreach (range($page->first, $page->last) as $index) { ?>
			<?php if (($index > $page->current - $offset - 1 && $index < $page->current + $offset + 1) || $index == $page->first || $index == $page->last) { ?>
				<?php if ($page->current == $index) { ?>
					<li class="active"><a href="?page=<?= $index ?><?php if (isset($searchquery)) { ?>&searchquery=<?= $searchquery ?><?php } ?><?php if (isset($initdate)) { ?>&initdate=<?= $initdate ?><?php } ?><?php if (isset($enddate)) { ?>&enddate=<?= $enddate ?><?php } ?><?php if (isset($status)) { ?>&status=<?= $status ?><?php } ?><?php if (isset($period)) { ?>&period=<?= $period ?><?php } ?><?php if (isset($client)) { ?>&client=<?= $client ?><?php } ?>"><?= $index ?></a></li>
				<?php } else { ?>
					<li><a href="?page=<?= $index ?><?php if (isset($searchquery)) { ?>&searchquery=<?= $searchquery ?><?php } ?><?php if (isset($initdate)) { ?>&initdate=<?= $initdate ?><?php } ?><?php if (isset($enddate)) { ?>&enddate=<?= $enddate ?><?php } ?><?php if (isset($status)) { ?>&status=<?= $status ?><?php } ?><?php if (isset($period)) { ?>&period=<?= $period ?><?php } ?><?php if (isset($client)) { ?>&client=<?= $client ?><?php } ?>"><?= $index ?></a></li><?php $shownHellip = false; ?>
				<?php } ?>
			<?php } elseif ($shownHellip == false) { ?>
				<li class="disabled"><a>&hellip;</a></li><?php $shownHellip = true; ?>
			<?php } ?>
		<?php } ?>
	</ul>
</nav>
<!-- END NAVIGATION -->
