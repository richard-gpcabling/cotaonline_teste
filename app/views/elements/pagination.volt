{% if page.numPages > 1 %}
    <ul class="pagination">
        {% if page.prevUrl %}
            <li><a href="{{ page.prevUrl }}">&laquo; Previous</a></li>
        {% endif %}

        {% for pageItem in page.pages %}
            {% if pageItem.url %}
                <li {{ pageItem.isCurrent ? 'class="active"' : '' }}><a href="{{ pageItem.url }}">{{ pageItem.num }}</a></li>
            {% else %}
                <li class="disabled"><span>{{ pageItem.num }}</span></li>
            {% endif %}
        {% endfor %}

        {% if page.nextUrl %}
            <li><a href="{{ page.nextUrl }}">Next &raquo;</a></li>
        {% endif %}
    </ul>
{% endif %}