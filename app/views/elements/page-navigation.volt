<!-- BEGIN NAVIGATION -->
<nav aria-label="Page navigation">
	<ul class="pagination">
		{% set offset = 3 %}
		{% set shownHellip = false %}
		{% set hRefPath = '' %}
		{% for index in page.first..page.last %}
			{% if (index > page.current - offset - 1 and index < page.current + offset + 1) or index == page.first or index == page.last %}

				{% set hRefPath = '?page=' ~ index %}
				{% if query is defined %}
					{% set hRefPath = hRefPath ~ '&query=' ~ query %}
				{% endif %}
				{% if searchquery is defined %}
					{% set hRefPath = hRefPath ~ '&searchquery=' ~ searchquery %}
				{% endif %}
				{% if fabr is defined %}
					{% set hRefPath = hRefPath ~ '&fabr=' ~ fabr %}
				{% endif %}
				{% if initdate is defined %}
					{% set hRefPath = hRefPath ~ '&initdate=' ~ initdate %}
				{% endif %}
				{% if enddate is defined %}
					{% set hRefPath = hRefPath ~ '&enddate=' ~ enddate %}
				{% endif %}
				{% if status is defined %}
					{% set hRefPath = hRefPath ~ '&status=' ~ status %}
				{% endif %}
				{% if period is defined %}
					{% set hRefPath = hRefPath ~ '&period=' ~ period %}
				{% endif %}
				{% if client is defined %}
					{% set hRefPath = hRefPath ~ '&client=' ~ client %}
				{% endif %}

				{% if page.current == index %}
					<li class="active"><a href="{{ hRefPath }}">{{ index }}</a></li>
				{% else %}
					<li><a href="{{ hRefPath }}">{{ index }}</a></li>{% set shownHellip = false %}
				{% endif %}
			{% elseif shownHellip == false %}
				<li class="disabled"><a>&hellip;</a></li>{% set shownHellip = true %}
			{% endif %}
		{% endfor %}
	</ul>
</nav>
<!-- END NAVIGATION -->