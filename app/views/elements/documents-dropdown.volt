<div class="thumbnail__documents dropdown">
  <button class="btn"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false">
    <i class="glyphicon glyphicon-book"></i>
    Documentos
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    {% for doc in documents %}
      <li>
        <a href="{{ doc['path'] }}" title="{{ doc['filename'] }}" target="_blank">
          {{ doc['filename'] }}
        </a>
      </li>
    {% endfor %}
  </ul>
</div>