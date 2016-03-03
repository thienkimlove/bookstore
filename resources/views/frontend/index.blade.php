@extends('frontend')

@section('content')

<div class="site-inner">
    <div class="content-sidebar-wrap">
        <main class="content">
            <div class="home-top widget-area">
                <section id="featured-post-2" class="widget featured-content featuredpost">
                    <div class="widget-wrap"><h4 class="widget-title widgettitle">New Books</h4>
                        <article
                                class="post-700 post type-post status-publish format-standard has-post-thumbnail category-general pmpro-has-access entry">
                            <a href="http://sciencebasic.com/grays-anatomy-the-anatomical-basis-of-clinical-practice-41st-edition-2015"
                               class="alignnone" aria-hidden="true"><img
                                        src="{{url('frontend/images/51FIvINn2cL.jpg')}}"
                                        class="entry-image attachment-post"
                                        alt="Grays Anatomy-The Anatomical Basis of Clinical Practice – 41st Edition (2015)"
                                        itemprop="image" height="150" width="150"></a>
                            <header class="entry-header"><h2 class="entry-title"><a
                                            href="http://sciencebasic.com/grays-anatomy-the-anatomical-basis-of-clinical-practice-41st-edition-2015">Grays
                                        Anatomy-The Anatomical Basis of Clinical Practice – 41st Edition (2015)</a></h2>
                            </header>
                        </article>

                    </div>
                </section>
                <section id="featured-post-3" class="widget featured-content featuredpost">
                    <div class="widget-wrap"><h4 class="widget-title widgettitle">Random Books</h4>
                        <article
                                class="post-597 post type-post status-publish format-standard has-post-thumbnail category-general pmpro-has-access entry">
                            <a href="http://sciencebasic.com/advanced-biofuels-using-catalytic-routes-for-the-conversion-of-biomass-platform-molecules-2015"
                               class="alignnone" aria-hidden="true"><img
                                        src="{{url('frontend/images/51xBIyG2C-L.jpg')}}"
                                        class="entry-image attachment-post"
                                        alt="Advanced Biofuels: Using Catalytic Routes for the Conversion of Biomass Platform Molecules (2015)"
                                        itemprop="image" height="150" width="150"></a>
                            <header class="entry-header"><h2 class="entry-title"><a
                                            href="http://sciencebasic.com/advanced-biofuels-using-catalytic-routes-for-the-conversion-of-biomass-platform-molecules-2015">Advanced
                                        Biofuels: Using Catalytic Routes for the Conversion of Biomass Platform Molecules
                                        (2015)</a></h2></header>
                        </article>

                    </div>
                </section>
            </div>
        </main>
        <aside class="sidebar sidebar-primary widget-area" role="complementary" aria-label="Primary Sidebar"
               itemscope="" itemtype="http://schema.org/WPSideBar">
            <section id="search-4" class="widget widget_search">
                <div class="widget-wrap">
                    <form class="search-form" itemprop="potentialAction" itemscope=""
                          itemtype="http://schema.org/SearchAction" method="get" action="http://sciencebasic.com/"
                          role="search">
                        <meta itemprop="target" content="http://sciencebasic.com/?s={s}">
                        <input itemprop="query-input" name="s" placeholder="Search the site ..."
                               type="search"><input value="Search" type="submit"></form>
                </div>
            </section>
            <section id="recent-posts-4" class="widget widget_recent_entries">
                <div class="widget-wrap"><h4 class="widget-title widgettitle">Recent Posts</h4>
                    <ul>
                        <li>
                            <a href="http://sciencebasic.com/grays-anatomy-the-anatomical-basis-of-clinical-practice-41st-edition-2015">Grays
                                Anatomy-The Anatomical Basis of Clinical Practice – 41st Edition (2015)</a>
                        </li>
                    </ul>
                </div>
            </section>
        </aside>
    </div>
</div>

@endsection