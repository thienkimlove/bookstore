<aside class="sidebar sidebar-primary widget-area" role="complementary" aria-label="Primary Sidebar"
       itemscope="" itemtype="http://schema.org/WPSideBar">
    <section id="search-4" class="widget widget_search">
        <div class="widget-wrap">
            <form class="search-form" itemprop="potentialAction" itemscope=""
                  itemtype="http://schema.org/SearchAction" method="get" action="{{url('/')}}"
                  role="search">
                <meta itemprop="target" content="?s={s}">
                <input itemprop="query-input" name="s" placeholder="Search the site ..."
                       type="search"><input value="Search" type="submit"></form>
        </div>
    </section>
    <section id="recent-posts-4" class="widget widget_recent_entries">
        <div class="widget-wrap"><h4 class="widget-title widgettitle">Recent Books</h4>
            <ul>
                @foreach ($recentPosts as $post)
                    <li>
                        <a href="{{url($post->slug. '.html')}}">{{$post->title}}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
</aside>