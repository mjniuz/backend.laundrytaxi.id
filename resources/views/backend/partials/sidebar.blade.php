
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ url("dist/img/user2-160x160.jpg") }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ Sentinel::check()->first_name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            @foreach(nav_url() as $menu)
                <li class="{{ (empty($menu["link"]) OR $menu["link"] == "#") ? "treeview" : "" }} {{ $menu['is_open'] ? "active menu-open" : "" }}">
                    <a href="{{ !empty($menu["link"]) ? url("backend/" . $menu["link"] ) : "backend" }}">
                        <i class="fa fa-{{ !empty($menu["icon"]) ? $menu["icon"] : "" }}"></i>
                        <span>{{ !empty($menu["title"]) ? $menu["title"] : "" }}</span>

                        @if(empty($menu["link"]) OR $menu["link"] == "#")
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        @endif
                    </a>
                    @if(!empty($menu["sub"]) AND count($menu["sub"]) > 0)
                        <ul class="treeview-menu">
                            @foreach($menu["sub"] as $sub)
                                <li class="{{ $sub['is_active'] ? "active" : "" }}">
                                    <a href="{{ !empty($sub["link"]) ? url("backend/" . $sub["link"] ) : "" }}">
                                        <i class="fa fa-{{ !empty($sub["icon"]) ? $sub["icon"] : "" }}"></i>
                                        {{ !empty($sub["title"]) ? $sub["title"] : "" }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach

            {{--<li class="treeview">
                <a href="#">
                    <i class="fa fa-share"></i> <span>Multilevel</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-circle-o"></i> Level One
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                            <li class="treeview">
                                <a href="#"><i class="fa fa-circle-o"></i> Level Two
                                    <span class="pull-right-container">
                                      <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                </ul>
            </li>--}}
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>