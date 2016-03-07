@extends('frontend')

@section('content')

<div class="site-inner">
    <div class="content-sidebar-wrap">
        <main class="content">
            <div class="home-top widget-area">
                <section id="featured-post-2" class="widget featured-content featuredpost">
                    <div class="widget-wrap">
                        <h4 class="widget-title widgettitle">{{$page_title}}</h4>
                        <!-- Review -->
                        <ins class="adsbygoogle"
                             style="display:block"
                             data-ad-client="ca-pub-6984203717480217"
                             data-ad-slot="1810777884"
                             data-ad-format="auto"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                        @foreach ($posts as $post)
                        <article
                                class="post-{{$post->id}} post type-post status-publish format-standard has-post-thumbnail category-general pmpro-has-access entry">

                                <a href="{{url($post->slug. '.html')}}"
                                   class="alignnone" aria-hidden="true">
                                    <img
                                            src="{{ $post->image }}"
                                            class="entry-image attachment-post"
                                            style="height: 150px"
                                            alt="{{$post->title}}"
                                            itemprop="image"
                                            height="150"
                                            width="150">
                                </a>



                            <header class="entry-header">
                                <h2 class="entry-title">
                                    <a href="{{url($post->slug. '.html')}}">{{$post->title}}</a>
                                </h2>
                            </header>
                        </article>
                        @endforeach
                    </div>
                    @if ($page_title != 'New Books')
                        <div class="archive-pagination pagination">
                            {!! $posts->render() !!}
                        </div>
                    @endif

                </section>
                <section id="featured-post-3" class="widget featured-content featuredpost">
                    <div class="widget-wrap">
                        <h4 class="widget-title widgettitle">Random Books</h4>

                        @foreach ($randomPosts as $post)
                        <article class="post-{{$post->id}} post type-post status-publish format-standard has-post-thumbnail category-general pmpro-has-access entry">
                            <a href="{{url($post->slug. '.html')}}"
                               class="alignnone"
                               aria-hidden="true">
                                <img
                                        src="{{$post->image}}"
                                        class="entry-image attachment-post"
                                        alt="{{$post->title}}"
                                        style="height: 150px"
                                        itemprop="image"
                                        height="150"
                                        width="150">
                            </a>
                            <header class="entry-header">
                                <h2 class="entry-title">
                                    <a href="{{url($post->slug. '.html')}}">{{$post->title}}</a>
                                </h2>
                            </header>
                        </article>
                        @endforeach
                                <!-- Review -->
                            <ins class="adsbygoogle"
                                 style="display:block"
                                 data-ad-client="ca-pub-6984203717480217"
                                 data-ad-slot="1810777884"
                                 data-ad-format="auto"></ins>
                            <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                            </script>
                    </div>
                </section>
            </div>
        </main>
        @include('frontend.aside')
    </div>
</div>

@endsection