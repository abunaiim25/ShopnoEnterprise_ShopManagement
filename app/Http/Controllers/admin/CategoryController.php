<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        //$categories = Category::latest()->simplePaginate(2);
        $categories = Category::latest()->paginate(20);
       // $categories = Category::latest()->paginate(4)->withQueryString();
        return view('admin.category.index',compact('categories'));
    }


     // ============ store category ========= 
    public function Store(Request $request){
        $request->validate([
            'category_name' => 'required|unique:categories,category_name'
        ]);
        
       
        Category::insert([
            'category_name' => $request->category_name,
            'created_at' => Carbon::now()
        ]);

        return Redirect()->back()->with('status_swal','Category Added Successfully');
    }


    // ========= edit category data =============
    public function admin_category_seen($id)
    {
        $Category = Category::find($id);
        return response()->json([
            'status' => 200,
            'Category' => $Category,
        ]);
    }

    public function update(Request $request){      
        $cat_id = $request->id;

        $Category = Category::find($cat_id);
        $Category->category_name = $request->category_name;
        $Category->update();
        return Redirect('admin_category')->with('status_swal','Category Updated Successfully');
    }

    // Delete category 
    public function Delete($id){
        Category::find($id)->delete();
        return Redirect()->back()->with('status_swal','Category Deleted Success');
    }


    // status inactive 
    public function Inactive($id){
        $Category = Category::find($id);
        $Category->status = "0";
        $Category->update();
        return Redirect()->back()->with('status_swal', 'Category Inactive');

    }

    // status active 
    public function Active($id){
        $Category = Category::find($id);
        $Category->status = "1";
        $Category->update();
        return Redirect()->back()->with('status_swal','Category Activated');
    }

     //searching catagory
     public function category_search(Request $request)
     {
         $categories = Category::
         where('category_name','like','%'.$request->search.'%')
         ->paginate(20);
         return view('admin.category.index',compact('categories'));
     }


    public function category_autocomplete_search_ajax()
    {
        $category = Category::get();
        $data = [];

        foreach ($category as $item) {
            $data[] = $item['category_name'];
        }
        return $data;
    }
}