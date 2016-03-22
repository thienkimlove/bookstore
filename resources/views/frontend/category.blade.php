@extends('frontend')

@section('content')
    <div class="site-inner">
        <div class="content-sidebar-wrap">
            <main class="content">
                <div class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">You are here:
                    <span
                            class="breadcrumb-link-wrap"
                            itemprop="itemListElement"
                            itemscope=""
                            itemtype="http://schema.org/ListItem">
                        <a href="{{url('/')}}" itemprop="item">
                            <span itemprop="name">Home</span>
                        </a>
                    </span> <span aria-label="breadcrumb separator">/</span>
                   {{$category->name}}
                </div>
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
                        class="post-{{$post->id}} post type-post status-publish format-standard has-post-thumbnail category-anatomy category-biochemistry category-general category-genetics category-histology category-immunology category-uncategorized category-microbiology category-pathology category-pathophysiology category-physiology pmpro-has-access entry"
                        itemscope=""
                        itemtype="http://schema.org/CreativeWork">
                    <header class="entry-header">
                        <h2 class="entry-title" itemprop="headline">
                            <a href="{{url($post->slug.'.html')}}"  rel="bookmark">{{$post->title}}</a>
                        </h2>
                        <p class="entry-meta">
                            <time class="entry-time" itemprop="datePublished" datetime="{{$post->updated_at->format('Y/m/d')}}">
                                {{$post->updated_at->toFormattedDateString()}}
                            </time>
                    </header>
                    <div class="entry-content" itemprop="text">
                        <a href="{{url($post->slug.'.html')}}"  aria-hidden="true">
                            <img
                                    src="{{$post->image}}"
                                    class="alignleft post-image entry-image"
                                    alt="{{url($post->title)}}"
                                    itemprop="image"
                                    height="150"
                                    width="150">
                        </a>
                        <p>
                            {{$post->desc}}
                            <a href="{{url($post->slug.'.html#download-free')}}"><h3 style="float: right"><img width="30" src="{{url('download.jpg')}}" />Download</h3></a>
                        </p>
                    </div>
                </article>
                @endforeach
                <div class="archive-pagination pagination">
                    {!! $posts->render() !!}
                </div>
            </main>
           @include('frontend.aside')
        </div>
    </div>
@endsection