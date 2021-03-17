@extends('layout')
@section('content')

<div class="col-lg-8 col-md-10 mx-auto">

        <div class="post-preview">
            <h1 class="post-title"> {{ $post->title }} </h1>
            
              {!! $post->content !!}

          <p class="post-meta">Posted by
            {{ $post->name_author }}
            on {{ date('M,d y', strtotime($post->created_at)) }}</p>
        </div>
        <hr>

</div>
@endsection      