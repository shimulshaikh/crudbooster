@extends('layout')
@section('content')

<div class="col-lg-8 col-md-10 mx-auto">

      @foreach($posts as $post)
        <div class="post-preview">
            <h2 class="post-title">
              <a href='{{ url("artical/$post->slug") }}' title="{{ $post->title }}">{{ $post->title }} </a>
            </h2>
            
              {{ str_limit(strip_tags($post->content),200) }}
              <p><a href='{{ url("artical/$post->slug") }}' title="{{ $post->title }}">Read More &raquo;</a></p>

          <p class="post-meta">Posted by
            {{ $post->name_author }}
            on {{ date('M,d y', strtotime($post->created_at)) }}</p>
        </div>
        <hr>
      @endforeach  

        <div class="clearfix">
          <a class="btn btn-primary float-right" href="#">Older Posts &rarr;</a>
        </div>

</div>
@endsection      