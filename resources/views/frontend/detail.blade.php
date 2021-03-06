@extends('frontend')

@section('content')
    <div class="site-inner">
        <div class="content-sidebar-wrap">
            <main class="content">
                <div class="breadcrumb"
                     itemscope=""
                     itemtype="http://schema.org/BreadcrumbList">
                    You are here: <span  class="breadcrumb-link-wrap"
                                         itemprop="itemListElement"
                                         itemscope=""
                                         itemtype="http://schema.org/ListItem">
                        <a href="{{url('/')}}" itemprop="item">
                            <span itemprop="name">Home</span></a></span>
                            <span aria-label="breadcrumb separator">/</span>
                            <span class="breadcrumb-link-wrap"
                                  itemprop="itemListElement"
                                  itemscope=""
                                  itemtype="http://schema.org/ListItem">
                                <a href="{{url($post->category->slug)}}" itemprop="item">
                                    <span itemprop="name">{{$post->category->name}}</span></a></span>
                    <span aria-label="breadcrumb separator">/</span>
                    {{$post->title}}
                </div>
                <article  class="post-{{$post->id}} post type-post status-publish format-standard has-post-thumbnail category-general pmpro-has-access entry"
                          itemscope=""
                          itemtype="http://schema.org/CreativeWork">
                    <header class="entry-header">
                        <h1 class="entry-title" itemprop="headline">{{$post->title}}</h1>

                                <a href="#download-free"><h3 style="float: right"><img width="30" src="{{url('download.jpg')}}" />Go to Free Download Section</h3></a>

                        <p class="entry-meta">
                            <time class="entry-time" itemprop="datePublished" datetime="{{$post->updated_at->format('Y/m/d')}}">
                                {{$post->updated_at->toFormattedDateString()}}
                            </time>
                        </p>
                    </header>

                    <!-- Review -->
                    <ins class="adsbygoogle"
                         style="display:block"
                         data-ad-client="ca-pub-6984203717480217"
                         data-ad-slot="1810777884"
                         data-ad-format="auto"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                    <div class="entry-content" itemprop="text">
                        <div id="book-review"
                             itemscope=""
                             itemtype="http://schema.org/Review"
                             style="border-style: solid; border-width: 1px;">

                            <!-- Meta for schema.org -->
                            <meta itemprop="headline"
                                  content="{{$post->title}} by {{$post->publisher}}">
                            <!-- author is mandatory! -->
                            <meta itemprop="datePublished" content="{{$post->release_date}}">

                            <meta itemprop="author" content="{{$post->author}}">

                            <!-- Cover -->
                            <img itemprop="image"
                                 id="book_review_cover_image"
                                 class="cover"
                                 src="{{$post->image}}"
                                 alt="{{$post->title}} Book Cover">

                            <!-- Title -->
                            <label for="book_review_title">
                                Title:
                            </label>
                              <span itemprop="itemReviewed" itemscope="" itemtype="http://schema.org/Thing" id="book_review_title">
                                <span itemprop="name">{{$post->title}}</span>
                              </span>
                            <br>

                            <!-- Series -->

                            <!-- Author -->

                            <!-- Genre -->

                            <!-- Publisher -->
                            <label for="book_review_publisher">
                                Publisher:
                            </label>
                              <span itemprop="publisher" id="book_review_publisher">
                                {{$post->publisher}} by {{$post->author}} ({{ $post->release_date }})  </span>
                            <br>

                            <!-- Release Date -->
                            <label for="book_review_release_date">
                                Release Date:
                            </label>
                              <span id="book_review_release_date"> {{ $post->release_date }}  </span>
                            <br>

                            <!-- Format -->

                            <!-- Pages -->
                            <label for="book_review_pages">
                                Pages:
                            </label>
                              <span id="book_review_pages"> {{$post->pages}}  </span>
                            <br>

                            <!-- Source -->

                            <!-- Custom Fields -->

                            <!-- Rating -->
                            <div itemprop="reviewRating" itemscope="" itemtype="http://schema.org/Rating">
                                <meta itemprop="ratingValue" content="5">
                                <img id="book_review_rating_image" class="rating"
                                     src="{{url('frontend/images/five-star.png')}}">
                                <br>
                            </div>

                            <!-- Summary / Synopsis -->
                            <div id="book_review_summary">
                              <p>{!! $post->content !!}</p>
                            </div>
                            <div class="et-box et-download">
                                <div class="et-box-content" style="padding: 0px">

                                    <div id="download-free" class="sametitle">Direct Download Links </div>
                                    <div class="same">
                                        <ul style="margin-left: 0.1rem">
                                            @foreach ($post->downloads as $download)
                                                <li>
                                                    <a target="_blank" href="{{$download->link}}"
                                                       rel="bookmark"><strong>{{($download->title) ? $download->title.'.pdf' : $download->link}}</strong> </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sametitle">Related Books</div>
                    <div class="same">
                        <ul>
                            @foreach ($relatedPosts as $relatedPost)
                            <li>
                                <a href="{{url($relatedPost->slug.'.html')}}"
                                   rel="bookmark">{{$relatedPost->title}}</a>
                            </li>
                             @endforeach
                        </ul>
                    </div>

                    <footer class="entry-footer">
                        <p class="entry-meta">
                            <span class="entry-categories">Filed Under:
                                <a href="{{url($post->category->slug)}}" rel="category tag">{{$post->category->name}}</a>
                            </span>
                        </p>
                    </footer>
                </article>
            </main>
            @include('frontend.aside')
        </div>
    </div>
@endsection
