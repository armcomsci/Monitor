<div class="sidebar-wrapper sidebar-theme">
            
    <nav id="sidebar">
        <div class="shadow-bottom"></div>

        <ul class="list-unstyled menu-categories" id="accordionExample">
           @php
               $MenuAll = MenuAll();
           @endphp
           @foreach ($MenuAll as $menu)
            <li class="menu">
                    <a href="#menu-{{ $menu->id }}" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            {!! $menu->menuIcon !!}
                            <span>{{ $menu->menuName }}</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                    @php
                        $MenuID = $menu->id;
                        $SubMenus = CheckSubMenu($MenuID);
                    @endphp
                    <ul class="collapse submenu list-unstyled" id="menu-{{ $menu->id }}" >
                    @foreach ($SubMenus as $SubMenu)
                            <li>
                                <a href="{{ url($SubMenu->menuUrl) }}">{{ $SubMenu->menuName }}</a>
                            </li>
                    @endforeach
                    </ul>
            </li>
           @endforeach
        </ul>
        
    </nav>

</div>
