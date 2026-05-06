<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            Inventory
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}">
                        Главная
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                       href="{{ route('products.index') }}">
                        Каталог
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('stock.*') ? 'active' : '' }}"
                       href="{{ route('stock.index') }}">
                        Остатки
                    </a>
                </li>

                @can('manage-inventory')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('incoming.*') || request()->routeIs('outgoing.*') ? 'active' : '' }}"
                           href="#" role="button" data-bs-toggle="dropdown">
                            Накладные
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('incoming.*') ? 'active' : '' }}"
                                   href="{{ route('incoming.invoices.index') }}">
                                    Приходные
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('outgoing.*') ? 'active' : '' }}"
                                   href="{{ route('outgoing.invoices.index') }}">
                                    Расходные
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('view-reports')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('history.*') ? 'active' : '' }}"
                           href="{{ route('history.index') }}">
                            История
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                           href="#" role="button" data-bs-toggle="dropdown">
                            Отчёты
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('reports.stock') ? 'active' : '' }}"
                                   href="{{ route('reports.stock') }}">
                                    Остатки
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('reports.incoming') ? 'active' : '' }}"
                                   href="{{ route('reports.incoming') }}">
                                    Приходы
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('reports.outgoing') ? 'active' : '' }}"
                                   href="{{ route('reports.outgoing') }}">
                                    Расходы
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('reports.suppliers') ? 'active' : '' }}"
                                   href="{{ route('reports.suppliers') }}">
                                    Поставщики
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('access-admin-panel')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}"
                           href="{{ route('admin.categories.index') }}">
                            Админ-панель
                        </a>
                    </li>
                @endcan
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#"
                       role="button" data-bs-toggle="dropdown">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                        <li>
                            <span class="dropdown-item-text text-white small">
                                {{ Auth::user()->email }}
                            </span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                Профиль
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    Выйти
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
