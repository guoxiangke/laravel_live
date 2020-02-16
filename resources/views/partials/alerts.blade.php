<div class="flash-message"  role="alert">
  @foreach (['primary', 'link', 'info', 'success', 'warning', 'danger', 'status'] as $type)
    @if(session($type))
    <div class="notification is-{{ $type }}">
        <button class="delete"></button>
        {{ session($type) }}
        @if(session($type . '-info'))
            <pre class="alert-pre border bg-light p-2"><code>{{ session($type . '-info') }}</code></pre>
        @endif
    </div>
    @endif
  @endforeach
</div>