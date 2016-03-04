<nav class="nav-primary" itemscope="" itemtype="http://schema.org/SiteNavigationElement">
    <div class="wrap">
        <div id="responsive-menu-icon"></div>
        <ul id="menu-menu" class="menu genesis-nav-menu menu-primary responsive-menu">
            <li id="menu-item-0"
                class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home menu-item-58">
                <a href="{{url('/')}}" itemprop="url"><span itemprop="name">Home</span></a>
            </li>

             @foreach ($categories->splice(0, 7) as $category)
            <li id="menu-item-{{$category->id}}"
                class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home menu-item-58">
                <a href="{{url($category->slug)}}" itemprop="url"><span itemprop="name">{{$category->name}}</span></a>
            </li>
             @endforeach
            <li id="menu-item-57"
                class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-has-children menu-item-57">
                <a href="http://sciencebasic.com/uncategorized" itemprop="url"><span itemprop="name">More >></span></a>
                <ul class="sub-menu">
                    @foreach ($categories as $category)
                        <li id="menu-item-{{$category->id + 58}}"
                            class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-13">
                            <a href="{{url($category->slug)}}" itemprop="url">
                                <span itemprop="name">{{$category->name}}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        </ul>
    </div>
</nav>
