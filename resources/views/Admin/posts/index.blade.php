<!-- First you need to extend the CB layout -->
@extends('crudbooster::admin_template')
@section('content')

              @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {{ Session::get('success') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              @endif

<a href="{{ url('/admin/photos',$parent_id) }}" style="max-width: 150px; float: right; display: inline-block;" class="btn btn-block btn-success">Add Photos</a>
<!-- Your custom  HTML goes here -->
<table class='table table-striped table-bordered'>
  <thead>
      <tr>
        <th>Title</th>
        <th>Image</th>
        <th>post title</th>
        <th>Action</th>
       </tr>
  </thead>
  <tbody>
    @foreach($result as $row)
      <tr>
        <td>{{$row->title}}</td>
        <td>
            @if(!empty($row->image))
              <img style="width: 100px;" src="{{ asset('/storage/post') }}/{{ $row->image  }}">
            @else
              <img style="width: 100px;" src="{{asset('backend/dist/img/No_Image.jpg')}}">
            @endif 
        </td>
        <td>{{$row->post->title}}</td>
        <td>
            <a title="Edit" href="{{ url('/admin/edit-photos',$row->id) }}" class="btn btn-sm btn-info">Edit</a>
            &nbsp;&nbsp;
            <a title="Delete" class="btn btn-sm btn-danger" onclick="return confirm('Are You sure want to delete !')" href="{{ url('/admin/delete-photos',$row->id) }}">Delete</a>

            @if($row->status == 1)
            <a href="{{ url('/admin/photos-status-update',$row->id) }}" class="btn btn-sm btn-success">Active</a>
          @else
            <a href="{{ url('/admin/photos-status-update',$row->id) }}" class="btn btn-sm btn-warning">Inactive</a>
          @endif

        </td>
       </tr>
    @endforeach
  </tbody>
</table>

<!-- ADD A PAGINATION -->
<p>{!! urldecode(str_replace("/?","?",$result->appends(Request::all())->render())) !!}</p>
@endsection