<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\PurchaseReturn;
use App\Models\InvoiceBillItem;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\PurchaseReturnCustomer;

class PurchaseReturnController extends Controller
{
    public function index()
    {
        /*
        $return_product = DB::table('purchase_returns')
        ->join('categories', 'purchase_returns.category_id', 'categories.id')
        ->select("purchase_returns.*", "categories.category_name as category_name")
        ->latest()->paginate(20);
         */
        $customer = DB::table('purchase_return_customers')->where('return_status', '=', 'Pending')->latest()->paginate(20);
        return view('admin.return_product.index', compact('customer'));
    }

    public function purchase_return_back()
    {
        $customer = DB::table('purchase_return_customers')->where('return_status', '=', 'Done')->latest()->paginate(20);
        return view('admin.return_product.return_back', compact('customer'));
    }

    public function add_purchase_return_page()
    {
        $categories = Category::get();
        $stock = DB::table('shop_stocks')->latest()->get(); 
        return view('admin.return_product.add', compact('categories', 'stock'));
    }

    public function store_purchase_return(Request $request)
    {
        $order_id = PurchaseReturnCustomer::insertGetId([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'created_at' => Carbon::now(),
        ]);

        $product_name = $request->product_name;
        $category_id = $request->category_id;
        $brand = $request->brand;
        $product_quantity = $request->product_quantity;
        $warranty = $request->warranty;
        $warranty_duration = $request->warranty_duration;
        $used = $request->used;
        $return_reason = $request->return_reason;
        $comment = $request->comment;

        for ($i = 0; $i < count($product_name); $i++) {
            $datasave = [
                'order_id' => $order_id,
                'product_name' => $product_name[$i],
                'category_id' => $category_id[$i],
                'brand' => $brand[$i],
                'product_quantity' => $product_quantity[$i],
                'warranty' => $warranty[$i],
                'warranty_duration' => $warranty_duration[$i],
                'used' => $used[$i],
                'return_reason' => $return_reason[$i],
                'comment' => $comment[$i],
            ];
            DB::table('purchase_returns')->insert($datasave);
        }
        return Redirect()->to('/purchase_return')->with('status_swal', 'Purchase return Added');
    }

    public function purchase_return_edit($id)
    {
        $customer = PurchaseReturnCustomer::find($id);
        $return_product = DB::table('purchase_returns')->where('order_id', $id)->get();
        $categories = Category::get();
        $stock = DB::table('shop_stocks')->latest()->get(); 
        return view('admin.return_product.edit', compact('categories', 'return_product', 'customer', 'stock'));
    }

    public function update_purchase_return(Request $request, $id)
    {
        PurchaseReturnCustomer::where('id', $id)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'updated_at' => Carbon::now(),
        ]);

        foreach ($request->prodId as $key => $value) {
            $data = array(
                'product_name' => $request->product_name[$key],
                'brand' => $request->brand[$key],
                'product_quantity' => $request->product_quantity[$key],
                'warranty' => $request->warranty[$key],
                'warranty_duration' => $request->warranty_duration[$key],
                'used' => $request->used[$key],
                'return_reason' => $request->return_reason[$key],
                'comment' => $request->comment[$key],
            );
            PurchaseReturn::where('id', $request->prodId[$key])->update($data);
        }

        return Redirect()->to('/purchase_return')->with('status_swal', 'Purchase Return Updated Successfully');
    }

    public function purchase_return_seen($id)
    {
        /*
        $return_product = DB::table('purchase_return_customers')->where('purchase_return_customers.id', $id)
        ->join('purchase_returns', 'purchase_return_customers.id', 'purchase_returns.order_id') //join
        ->first();
         */
        // $return_product = PurchaseReturn::findOrFail($id);
        $product = DB::table('purchase_returns')->where('order_id', $id)
            ->join('categories', 'purchase_returns.category_id', 'categories.id')
            ->select("purchase_returns.*", "categories.category_name as category_name")
            ->get();

        $customer = PurchaseReturnCustomer::find($id);

        return view('admin.return_product.seen', compact('product', 'customer'));
    }

    public function purchase_return_delete($id)
    {
        DB::table('purchase_return_customers')->where('id', $id)->delete();
        DB::table('purchase_returns')->where('order_id', $id)->delete();
        return Redirect()->back()->with('status_swal', 'Purchase Return Deleted Successfully');
    }

    public function purchase_return_done($id)
    {
        $product = PurchaseReturnCustomer::find($id);
        $product->return_status = 'Done';
        $product->save();
        return redirect()->back()->with('status_swal', 'Purchase Return Done Successfully');
    }

    //searching product
    public function purchase_return_search(Request $request)
    {
        $customer = DB::table('purchase_return_customers')
            ->where('name', 'like', '%' . $request->search . '%')
            ->orWhere('email', 'like', '%' . $request->search . '%')
            ->orWhere('phone', 'like', '%' . $request->search . '%')
            ->paginate(20);
        return view('admin.return_product.index', compact('customer'));
    }

    public function purchase_return_autocomplete_search_ajax()
    {
        $customer = PurchaseReturnCustomer::get();
        $data = [];

        foreach ($customer as $item) {
            $data[] = $item['name'];
            $data[] = $item['email'];
            $data[] = $item['phone'];
        }
        return $data;
    }
}
