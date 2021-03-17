<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@section('content')
  <!-- Your html goes here -->
              @if ($errors->any())
                  <div class="alert alert-danger" style="margin-top: 10px;">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif

              @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {{ Session::get('success') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              @endif

  <div class='panel panel-default'>
    <div class='panel-heading'>Add Photo</div>
    <div class='panel-body'>
      <form method="post" action="{{ url('/admin/photos/add',$parent_id) }}" enctype="multipart/form-data">
        @csrf
        <div class='form-group'>
          <label>Title</label>
          <input type='text' name='title'  class='form-control'/>

          <label>Image</label>
          <input type='file' name='image'  class='form-control'/>
          <br>

          <label>Status : </label>            
          <input type = "radio" name = "status" value = "1" />
          <label for = "sizeSmall">Active</label>
          <input type = "radio" name = "status" value = "0" />
          <label for = "sizeMed">Inactive</label>

        </div>
         
        <!-- etc .... -->
        <div class='panel-footer'>
          <input type='submit' class='btn btn-primary' value='Save changes'/>
        </div>
        
      </form>
    </div>
    
  </div>
@endsection