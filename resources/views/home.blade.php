<a class="dropdown-item" href="{{ route('logout') }}"
    onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
    <i class="ti-power-off text-primary"></i>
    {{ __('Logout') }}
</a>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
