<?php namespace App\Http\Controllers;
	
	use Illuminate\Http\Request;
	use Session;
	use DB;
	use CRUDBooster;
	use CB;
	use Illuminate\Support\Str;
	use Illuminate\Support\Facades\Storage;
	use Image;
	use Carbon\Carbon;
	use App\PostPhoto;

	class AdminPostsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "title";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = true;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = true;
			$this->button_export = true;
			$this->table = "posts";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Title","name"=>"title"];
			$this->col[] = ["label"=>"Categories","name"=>"categories_id","join"=>"categories,name"];
			// $this->col[] = ["label"=>"Slug","name"=>"slug"];

			$this->col[] = ["label"=>"Tags","name"=>"tags"];
			$this->col[] = ["label"=>"Author","name"=>"cms_users_id","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Status","name"=>"status", "visible" => false];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Title','name'=>'title','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Categories','name'=>'categories_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'categories,name'];
			$this->form[] = ['label'=>'Content','name'=>'content','type'=>'wysiwyg','validation'=>'required|string','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Tags','name'=>'tags','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Status','name'=>'status','type'=>'radio','validation'=>'required','dataenum'=>'1|Active;0|Inactive'];

			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Title','name'=>'title','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Categories','name'=>'categories_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'categories,name'];
			//$this->form[] = ['label'=>'Content','name'=>'content','type'=>'wysiwyg','validation'=>'required|string','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Tags','name'=>'tags','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			# OLD END FORM

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
	        $this->sub_module = array();
	        $this->sub_module[] = ['label'=>'Photos','path'=>'getPhotos','parent_columns'=>'categories_id,title','foreign_key'=>'posts_id','button_color'=>'success','button_icon'=>'fa fa-bars'];


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
	        $this->addaction = array();
	        // $this->addaction[] = [
	        // 		'label'=>'Active',
	        // 		'url'=> url('set-status/[id]'),
         //            // 'url'=>CRUDBooster::mainpath('set-status/active/[id]'),
         //            'color'=>'',
         //            'icon'=>'fa fa-check',
         //            'showIf'=>"[status] == '1'", 'confirmation' => true
         //            ]; 
         //    $this->addaction[] = [
	        // 		'label'=>'Pending',
	        // 		'url'=> url('set-status/[id]'),
         //            // 'url'=>CRUDBooster::mainpath('set-status/active/[id]'),
         //            'color'=>'warning',
         //            'icon'=>'fa fa-ban',
         //            'showIf'=>"[status] == '0'", 'confirmation' => true
         //            ];         

	        $this->addaction[] = ['label'=>'Set Active','url'=>CRUDBooster::mainpath('set-status/active/[id]'),'icon'=>'fa fa-check','color'=>'success','showIf'=>"[status] == '1'", 'confirmation' => true];
			$this->addaction[] = ['label'=>'Set Pending','url'=>CRUDBooster::mainpath('set-status/pending/[id]'),'icon'=>'fa fa-ban','color'=>'warning','showIf'=>"[status] == '0'", 'confirmation' => true];


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
	        $this->button_selected = array();
	        $this->button_selected[] = ['label'=>'Mul Delete','icon'=>'fa fa-trash','name'=>'set_active'];

	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
	        $this->alert        = array();                

	        
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	        $this->index_button = array();
	        // $this->index_button[] = ['label'=>'Advanced Print','class'=>'btnprn','url'=>CRUDBooster::mainpath("print"), "icon"=>"fa fa-print"];

	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
	        $this->table_row_color = array();  	          

	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
	        $this->index_statistic = array();
	        $this->index_statistic[] = ['label'=>'Total Data','count'=>DB::table('posts')->count(),'icon'=>'fa fa-check','color'=>'success'];
	        


	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;


            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
	        // $this->load_js[] = asset("backend/backend.js");
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();

	        
	        
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here

	    	dd("hi");

	        if($button_name == 'set_active') {
			  	DB::table('posts')->whereIn('id',$id_selected)->delete();
			  }
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
	        //Your code here
	        if (!CRUDBooster::isSuperadmin()) {
	            	$query->where('cms_users_id',CRUDBooster::myId());
	            }    
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {        
	        //Your code here
	    	$postdata['slug'] = str_slug($postdata['title']);
	    	$postdata['cms_users_id'] = CRUDBooster::myId();
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here
	    	$postdata['slug'] = str_slug($postdata['title']);
	    	$postdata['cms_users_id'] = CRUDBooster::myId();
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }



	    //By the way, you can still create your own method in here... :) 

	    public function getSetStatus($status,$id) {
	    	//dd($status);
	    	$status = DB::table("posts")->find($id)->status;
	    	if ($status == 1) {
				$status = 0;
			}else{
				$status = 1;
			}
			//dd($status);

		   DB::table('posts')->where('id',$id)->update(['status'=>$status]);
		   
		   //This will redirect back and gives a message
		   CRUDBooster::redirect($_SERVER['HTTP_REFERER'],"The status post has been updated !","info");
		}

		//custom post status update
		public function setStatus($id)
		{
			$status = DB::table("posts")->find($id)->status;
			
			if ($status == 1) {
				$status = 0;
			}else{
				$status = 1;
			}

			DB::table("posts")->where("id", $id)->update(["status"=>$status]);
			// return redirect()->back();
			CRUDBooster::redirect($_SERVER['HTTP_REFERER'],"The post status has been updated !","info");
		}

		//For post data print
		public function printPost()
		{
			$data['posts'] = DB::table('posts')
	    	->join('categories','categories.id','=','categories_id')
	    	->join('cms_users','cms_users.id','=','cms_users_id')
	    	->select('posts.*','categories.name as name_categories','cms_users.name as name_author')
	    	->orderby('posts.id','asc')
	        ->where('posts.status',1)
	    	->get();

	    	return view('Admin.posts.print_post',$data);
		}

		public function getAddPhoto($id)
		{
			//dd($_GET['parent_id']);

			//Create an Auth
			if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {    
			   CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
			  
			$data = [];
			$parent_id = $id;
			$data['page_title'] = 'Add Photo';
			//dd($parent_id);
			//Please use cbView method instead view method from laravel
			
			//$this->cbView('admin.posts.create',$data);

			return view("Admin.posts.create",compact('parent_id'));
		}

		public function storeAddPhoto(Request $request, $id)
		{
			//dd($id);
			$data = $request->all();
	        //dd($data);
	        //validation customize
            $rule = [
                'title' => 'required',
                'status' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp'
            ];

            $customMessages = [
                'title.required' => 'Title is required',
                'status.required' => 'Status is required',
                'image.image' => 'Valid Image is required',
            ];

            $request->validate($rule, $customMessages);

            $postPhoto = new PostPhoto;

            $image = $request->file('image');
            if(isset($image)){

            //make unique nake for image
            $currentDate = Carbon::now()->toDateString();

            $imageName = '-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            //check post image dir is exists
            if (!Storage::disk('public')->exists('post')) 
            {
                Storage::disk('public')->makeDirectory('post');
            }

            //resize for post image and upload
            $img = Image::make($image)->resize(300,300)->save(storage_path('app/public/post').'/'.$imageName);
            Storage::disk('public')->put('post/'.$imageName,$img);

        }

            $postPhoto->title = $data['title'];
            $postPhoto->image = $imageName;
            $postPhoto->status = $data['status'];
            $postPhoto->posts_id = $id;
            //dd($id);
            $postPhoto->save();

            Session::flash('success', 'Post Image Added Successfully');
        	return redirect()->route('getPhoto','?parent_id='.$id);
		}

		public function getPhotos()
		{
			 if(!CRUDBooster::isView()) CRUDBooster::redirect(CRUDBooster::adminPath(),trans('crudbooster.denied_access'));

			$parent_id = $_GET['parent_id'];
			//dd($parent_id);
			$data = [];
			// $data['result'] = PostPhoto::orderby('id','desc')->paginate(10);
			$result = PostPhoto::with(['post'])->orderby('id','desc')->paginate(10);
	        // $postPhoto = json_decode(json_encode($postPhoto),true);
	        // echo "<pre>"; print_r($postPhoto); die;
	        //dd($result);
        	return view('Admin.posts.index',compact('result','parent_id'));
		}

		public function photoStatusUpdate($id)
		{
			$status = PostPhoto::find($id)->status;
			
			if ($status == 1) {
				$status = 0;
			}else{
				$status = 1;
			}

			PostPhoto::where('id', $id)->update(['status'=>$status]);
			return redirect()->back();
		}

		public function getEditPhoto($id)
		{
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {    
		    CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
		  	}
		  
			$postPhoto = PostPhoto::findorFail($id);

        	return view('Admin.posts.edit')->with(compact('postPhoto'));
		}

		public function UpdatePhoto(Request $request, $id)
		{
			$data = $request->all();
	        //dd($data);
	        //validation customize
            $rule = [
                'title' => 'required',
                'status' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp'
            ];

            $customMessages = [
                'title.required' => 'Title is required',
                'status.required' => 'Status is required',
                'image.image' => 'Valid Image is required',
            ];

            $request->validate($rule, $customMessages);

            $postPhoto = PostPhoto::findorFail($id);

            $image = $request->file('image');
            if(isset($image)){

            //make unique nake for image
            $currentDate = Carbon::now()->toDateString();

            $imageName = '-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            //check post image dir is exists
            if (!Storage::disk('public')->exists('post')) 
            {
                Storage::disk('public')->makeDirectory('post');
            }

             //delete old image
            if (Storage::disk('public')->exists('post/'.$postPhoto->image))
                    {
                Storage::disk('public')->delete('post/'.$postPhoto->image);
            }


            //resize for post image and upload
            $img = Image::make($image)->resize(300,300)->save(storage_path('app/public/post').'/'.$imageName);
            Storage::disk('public')->put('post/'.$imageName,$img);

        }else{
        	$imageName = $postPhoto->image;
        }

            $postPhoto->title = $data['title'];
            $postPhoto->image = $imageName;
            $postPhoto->status = $data['status'];
            $postPhoto->posts_id = $data['posts_id'];
            //dd($postPhoto);
            $postPhoto->save();

            Session::flash('success', 'Post Image updated Successfully');
        	return redirect()->route('getPhoto','?parent_id='.$data['posts_id']);
		}

		public function deletePhoto($id)
		{
			$postPhoto = PostPhoto::findorFail($id);

            if (Storage::disk('public')->exists('post/'.$postPhoto->image))
                    {
                Storage::disk('public')->delete('post/'.$postPhoto->image);
            } 

			$postPhoto->delete();    

	        Session::flash('success', 'Photo Deleted Successfully');
	        return redirect()->back();   
		}


	}