<div class="sidebar" data-color="white" data-active-color="primary">
    <div class="logo">
        <a href="{{ route('admin.dashboard') }}" class="simple-text logo-normal">
            <img src="{{ asset('media/logo.jpeg') }}">
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            @if (auth()->user()->is_admin)
                <li class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="nc-icon nc-bank"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>
                <li class="{{ request()->is('admin/products*') ? 'active' : '' }}">
                    <a class="{{ request()->is('admin/products*') ? '' : 'collapsed' }}" data-toggle="collapse" aria-expanded="{{ request()->is('admin/products*') ? 'true' : 'false' }}" href="#productDropdown">
                        <i class="nc-icon nc-box-2"></i>
                        <p class="position-relative">
                                {{ __('Products') }}
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse {{ request()->is('admin/products*') ? 'show' : '' }}" id="productDropdown">
                        <ul class="nav">
                            <li class="{{ request()->is('admin/products') ? 'active' : '' }}">
                                <a href="{{ route('admin.products.list') }}">
                                    <span class="sidebar-mini-icon">{{ __('PL') }}</span>
                                    <span class="sidebar-normal">{{ __('Products List') }}</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/products/archived') ? 'active' : '' }}">
                                <a href="{{ route('admin.products.archived') }}">
                                    <span class="sidebar-mini-icon">{{ __('APL') }}</span>
                                    <span class="sidebar-normal">{{ __('Archived Product List') }}</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/products/raw-materials') ? 'active' : '' }}">
                                <a href="{{ route('admin.materials.list') }}">
                                    <span class="sidebar-mini-icon">{{ __('RML') }}</span>
                                    <span class="sidebar-normal">{{ __('Raw Materials List') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="{{ request()->is('admin/orders*') ? 'active' : '' }}">
                    <a class="{{ request()->is('admin/orders*') ? '' : 'collapsed' }}" data-toggle="collapse" aria-expanded="{{ request()->is('admin/orders*') ? 'true' : 'false' }}" href="#orderDropdown">
                        <i class="nc-icon nc-basket"></i>
                        <p class="position-relative">
                            {{ __('Orders') }}
                            {!! array_sum($orders_count) ? "<span class='badge badge-danger p-1 border position-absolute top-50 translate-middle-y' style='right: 25px;'>" .array_sum($orders_count) ."</span>" : '' !!}
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse {{ request()->is('admin/orders*') ? 'show' : '' }}" id="orderDropdown">
                        <ul class="nav">
                            <li class="{{ request()->is('admin/orders/pending') ? 'active' : '' }}">
                                <a href="{{ route('admin.orders.list', 'pending') }}">
                                    {!! !empty($orders_count['pending']) ? "<span class='badge badge-danger p-1 border float-end'>" .$orders_count['pending'] ."</span>" : '' !!}
                                    <span class="sidebar-mini-icon">{{ __('PO') }}</span>
                                    <span class="sidebar-normal">{{ __('Pending Orders') }}</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/orders/to-pay') ? 'active' : '' }}">
                                <a href="{{ route('admin.orders.list', 'to-pay') }}">
                                    <span class="sidebar-mini-icon">{{ __('TPO') }}</span>
                                    <span class="sidebar-normal">{{ __('To Pay Orders') }}</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/orders/to-review-payment') ? 'active' : '' }}">
                                <a href="{{ route('admin.orders.list', 'to-review-payment') }}">
                                    {!! !empty($orders_count['to_review']) ? "<span class='badge badge-danger p-1 border float-end'>" .$orders_count['to_review'] ."</span>" : '' !!}
                                    <span class="sidebar-mini-icon">{{ __('TRO') }}</span>
                                    <span class="sidebar-normal">{{ __('To Review Orders') }}</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/orders/on-process') ? 'active' : '' }}">
                                <a href="{{ route('admin.orders.list', 'on-process') }}">
                                    {!! !empty($orders_count['on_process']) ? "<span class='badge badge-danger p-1 border float-end'>" .$orders_count['on_process'] ."</span>" : '' !!}
                                    <span class="sidebar-mini-icon">{{ __('OPO') }}</span>
                                    <span class="sidebar-normal">{{ __('On Process Orders') }}</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/orders/to-deliver') ? 'active' : '' }}">
                                <a href="{{ route('admin.orders.list', 'to-deliver') }}">
                                    {!! !empty($orders_count['to_deliver']) ? "<span class='badge badge-danger p-1 border float-end'>" .$orders_count['to_deliver'] ."</span>" : '' !!}
                                    <span class="sidebar-mini-icon">{{ __('TDO') }}</span>
                                    <span class="sidebar-normal">{{ __('To Deliver Orders') }}</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/orders/cancelled') ? 'active' : '' }}">
                                <a href="{{ route('admin.orders.list', 'cancelled') }}">
                                    <span class="sidebar-mini-icon">{{ __('CAO') }}</span>
                                    <span class="sidebar-normal">{{ __('Cancelled Orders') }}</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/orders/rejected') ? 'active' : '' }}">
                                <a href="{{ route('admin.orders.list', 'rejected') }}">
                                    <span class="sidebar-mini-icon">{{ __('RO') }}</span>
                                    <span class="sidebar-normal">{{ __('Rejected Orders') }}</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/orders/completed') ? 'active' : '' }}">
                                <a href="{{ route('admin.orders.list', 'completed') }}">
                                    <span class="sidebar-mini-icon">{{ __('COO') }}</span>
                                    <span class="sidebar-normal">{{ __('Completed Orders') }}</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/orders') ? 'active' : '' }}">
                                <a href="{{ route('admin.orders.list') }}">
                                    <span class="sidebar-mini-icon">{{ __('AO') }}</span>
                                    <span class="sidebar-normal">{{ __('All Orders') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @if (!Auth::user()->is_staff)
                    <li class="{{ request()->is('admin/reports*') ? 'active' : '' }}">
                        <a class="{{ request()->is('admin/reports*') ? '' : 'collapsed' }}" data-toggle="collapse" aria-expanded="{{ request()->is('admin/reports*') ? 'true' : 'false' }}" href="#reportDropdown">
                            <i class="nc-icon nc-chart-bar-32"></i>
                            <p class="position-relative">
                                    {{ __('Reports') }}
                                <b class="caret"></b>
                            </p>
                        </a>
                        <div class="collapse {{ request()->is('admin/reports*') ? 'show' : '' }}" id="reportDropdown">
                            <ul class="nav">
                                <li class="{{ request()->is('admin/reports/sales') ? 'active' : '' }}">
                                    <a href="{{ route('admin.reports.sales') }}">
                                        <span class="sidebar-mini-icon">{{ __('SR') }}</span>
                                        <span class="sidebar-normal">{{ __('Sales Report') }}</span>
                                    </a>
                                </li>
                                <li class="{{ request()->is('admin/reports/inventory') ? 'active' : '' }}">
                                    <a href="{{ route('admin.reports.product.inventory') }}">
                                        <div class="sidebar-mini-icon">{{ __('IR') }}</div>
                                        <div class="sidebar-normal">{{ __('Inventory Report') }}</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                        <a class="{{ request()->is('admin/users*') ? '' : 'collapsed' }}" data-toggle="collapse" aria-expanded="{{ request()->is('admin/users*') ? 'true' : 'false' }}" href="#userDropdown">
                            <i class="nc-icon nc-single-02"></i>
                            <p class="position-relative">
                                    {{ __('Users') }}
                                <b class="caret"></b>
                            </p>
                        </a>
                        <div class="collapse {{ request()->is('admin/users*') ? 'show' : '' }}" id="userDropdown">
                            <ul class="nav">
                                <li class="{{ request()->is('admin/users') ? 'active' : '' }}">
                                    <a href="{{ route('admin.users.list') }}">
                                        <span class="sidebar-mini-icon">{{ __('CU') }}</span>
                                        <span class="sidebar-normal">{{ __('Customer User') }}</span>
                                    </a>
                                </li>
                                <li class="{{ request()->is('admin/users/admin') ? 'active' : '' }}">
                                    <a href="{{ route('admin.users.admin') }}">
                                        <span class="sidebar-mini-icon">{{ __('AU') }}</span>
                                        <span class="sidebar-normal">{{ __('Admin User') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="{{ request()->is('admin/settings') ? 'active' : '' }}">
                        <a href="{{ route('admin.settings.index') }}">
                            <i class="nc-icon nc-settings-gear-65"></i>
                            <p>{{ __('Settings') }}</p>
                        </a>
                    </li>
                @endif
            @endif
        </ul>
    </div>
</div>
