<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@section('content')
  <!-- Your html goes here -->
  <div class='panel panel-default'>
    <div class='panel-heading'>Edit Form</div>
    <div class='panel-body'>
      <form method="post" action="{{ url('/admin/update-photos',$postPhoto->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class='form-group'>
          <label>Title</label>
          <input type='text' name='title' value="{{ $postPhoto->title }}"  class='form-control'/>
          <input type='hidden' name='posts_id' value="{{ $postPhoto->posts_id }}"/>

          <label>Image</label>
          <input type='file' name='image'  class='form-control'/>
          <br>

          <label>Status : </label>  
          @if($postPhoto->status == 1)          
            <input type = "radio" name = "status" value = "1" checked/>
            <label for = "sizeSmall">Active</label>
            <input type = "radio" name = "status" value = "0" />
            <label for = "sizeMed">Inactive</label>
          @else
            <input type = "radio" name = "status" value = "1"/>
            <label for = "sizeSmall">Active</label>
            <input type = "radio" name = "status" value = "0" checked/>
            <label for = "sizeMed">Inactive</label>
          @endif

        </div>

        <div class='panel-footer'>
          <input type='submit' class='btn btn-primary' value='Save changes'/>
        </div>
        
      </form>
    </div>
    
  </div>
@endsection