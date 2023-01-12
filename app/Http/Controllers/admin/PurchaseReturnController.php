<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PurchaseReturn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseReturnController extends Controller
{
    public function index()
    {
        $return_product = DB::table('purchase_returns')
        ->join('categories', 'purchase_returns.category_id', 'categories.id')
        ->select("purchase_returns.*", "categories.category_name as category_name")
        ->latest()->paginate(20);
        return view('admin.return_product.index',compact('return_product'));
    }


    public function add_purchase_return_page()
    {
        $categories=Category::get();
        return view('admin.return_product.add', compact('categories'));
    }


    public function store_purchase_return(Request $request)
    {
        PurchaseReturn::insert([
            'product_name' => $request->product_name,
            'category_id' => $request->category_id,
            'brand' => $request->brand,
            'product_quantity' => $request->product_quantity,
            'return_reason' => $request->return_reason,
            'comment' => $request->comment,
            'created_at' => Carbon::now(),
        ]);
        return Redirect()->to('/purchase_Return')->with('status_swal', 'Purchase Return Added Successfully');
    }

    public function purchase_return_edit($id)
    {
        $return_product=PurchaseReturn::find($id);
        $categories = Category::get();
        return view('admin.return_product.edit', compact('categories', 'return_product'));
    }



    public function update_purchase_return(Request $request, $id)
    {
        $product = PurchaseReturn::findOrFail($id);

        $product->product_name = $request->product_name;
        $product->category_id = $request->category_id;
        $product->brand = $request->brand;
        $product->product_quantity = $request->product_quantity;
        $product->return_reason = $request->return_reason;
        $product->comment = $request->comment;
        //$product->created_at = \Carbon\Carbon::now();
        $product->update();

        return Redirect()->to('/purchase_return')->with('status_swal', 'Purchase Return Updated Successfully');
    }

    public function purchase_return_seen($id)
    {
        $product = PurchaseReturn::find($id);
        return response()->json([
            'status' => 200,
            'product' => $product,
        ]);
    }

    public function purchase_return_delete($id)
    {
        DB::table('purchase_returns')->where('id', $id)->delete();
        return Redirect()->back()->with('status_swal', 'Purchase Return Deleted Successfully');
    }

    public function purchase_return_done($id)
    {
        $product = PurchaseReturn::find($id);
        $product->return_status = 'Done';
        $product->save();
        return redirect()->back()->with('status_swal', 'Purchase Return Done Successfully');
    }

    //searching product
    public function purchase_return_search(Request $request)
    {
        $return_product = DB::table('purchase_returns')
        ->join('categories', 'purchase_returns.category_id', 'categories.id')
        ->select("purchase_returns.*", "categories.category_name as category_name")
        ->where('product_name', 'like', '%' . $request->search . '%')
            ->orWhere('brand', 'like', '%' . $request->search . '%')
            ->orWhere('product_quantity', 'like', '%' . $request->search . '%')
            ->orWhere('return_reason', 'like', '%' . $request->search . '%')
            ->orWhere('comment', 'like', '%' . $request->search . '%')
            ->orWhere('return_status', 'like', '%' . $request->search . '%')

            ->orWhere('category_name', 'like', '%' . $request->search . '%')
            ->paginate(20);
        return view('admin.return_product.index', compact('return_product'));
    }


    public function purchase_return_autocomplete_search_ajax()
    {
        $return_product = PurchaseReturn::get();
        $category = Category::get();
        $data = [];

        foreach ($return_product as $item) {
            $data[] = $item['product_name'];
            $data[] = $item['brand'];
            $data[] = $item['product_quantity'];
            $data[] = $item['return_reason'];
            $data[] = $item['comment'];
            $data[] = $item['return_status'];
        }
        foreach ($category as $item) {
            $data[] = $item['category_name'];
        }
        return $data;
    }

}
