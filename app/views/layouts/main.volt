

<!-- BEGIN HEADER -->
{{ partial('elements/header') }}
<!-- END HEADER -->


<div id="main" class="container-fluid">
	<div class="row">
		<div id="menu" class="col-md-2 col-sm-4">


			<!-- BEGIN MENU -->
{{ partial('elements/menu') }}
			<!-- END MENU -->


		</div>
		<div id="contentContainer" class="col-md-10 col-sm-8">
			<div id="content">


				<!-- BEGIN FLASH -->
{{ flash.output() }}
				<!-- END FLASH -->

				<!-- BEGIN INNER CONTENTS -->
{{ content() }}
				<!-- END INNER CONTENTS -->


			</div>
		</div>
	</div>
</div>

<div>


	<!-- BEGIN FOOTER -->
{{ partial('elements/footer') }}
	<!-- END FOOTER -->


</div>
