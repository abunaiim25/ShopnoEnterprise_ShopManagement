<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerInformation;
use App\Models\FrontControl;
use App\Models\InvoiceBill;
use App\Models\InvoiceBillItem;
use App\Models\ProductInvoice;
use App\Models\ShopStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceBillController extends Controller
{
    public function index()
    {
        //$invoice_bill = DB::table('customer_information')->join('invoice_bills', 'customer_information.order_id', 'invoice_bills.id')->get();
        //or
        $invoice_bill = DB::table('invoice_bills')
            ->join('customer_information', 'invoice_bills.id', 'customer_information.order_id') //join
            ->select("invoice_bills.*", "customer_information.name as name", "customer_information.date as date", "customer_information.phone as phone") //if same column in a table
            ->orderBy('invoice_bills.id', 'DESC')->paginate(20);

        $subtotal = DB::table('invoice_bills')->select('subtotal')->sum('subtotal');
        $bill_count = DB::table('invoice_bills')->select('subtotal')->count('subtotal');

        //dd($invoice_bill);
        return view("admin.InvoiceBill.index", compact('invoice_bill', 'subtotal', 'bill_count'));
    }
    
    //========================PDF Seen============================
    public function seen_invoicebill($id)
    {
        //$invoice=InvoiceBill::find($id);
        $invoice = DB::table('invoice_bills')->where('invoice_bills.id', $id)
            ->join('customer_information', 'invoice_bills.id', 'customer_information.order_id') //join
            ->first();

        $product = DB::table('invoice_bill_items')->where('order_id', $id)->get();
        $front = FrontControl::First();

        return view("admin.InvoiceBill.seen_invoicebill", compact('invoice', 'product', 'front'));
    }

    //====================ADD Invoice Page=====================
    public function admin_add_invoice()
    {
        $product_invoice = ProductInvoice::where('user_ip', request()->ip())->latest()->get();
        $subtotal = ProductInvoice::all()->where('user_ip', request()->ip())->sum(function ($t) {
            return $t->price * $t->qty;
        });
        $stock = DB::table('shop_stocks')->latest()->get(); 
        $product_stock = ShopStock::select('product_name')->where('status', '1')->where('product_quantity', '>', 0)->get();

        return view("admin.InvoiceBill.add_invoice", compact('product_invoice', 'subtotal', 'stock', 'product_stock'));
    }

    //======================product_invoice_store======================
    public function product_invoice_store(Request $request)
    {
        $request->validate([
            'qty' => 'integer',
            'price' => 'integer',
            'warranty' => 'integer',
        ]);

        ProductInvoice::insert([
            'product_desc' => $request->product_desc,
            'warranty' => $request->warranty,
            'qty' => $request->qty,
            'price' => $request->price,
            'user_ip' => request()->ip(),
        ]);

        return Redirect()->back()->with('status_swal', 'Product Added');
    }
    //======================admin_product_invoice_delete======================
    public function admin_product_invoice_delete($id)
    {
        $item = ProductInvoice::findOrFail($id);
        $item->delete();
        return Redirect()->back()->with('status_swal', 'Product Successfully Deleted');
    }


    //=====================place_order_invoice======================
    public function place_order_invoice(Request $request)
    {
        $order_id = InvoiceBill::insertGetId([
            'invoice_no' => 'SHOPNO-' . (mt_rand(10000000, 99999999)),
            'previous_due' => $request->previous_due,
            'subtotal' => $request->subtotal,
            'net_oustanding' => $request->previous_due + $request->subtotal - $request->collecton,
            'payment_status' => $request->payment_status,
            'payment_type' => $request->payment_type,
            'collecton' => $request->collecton,
            'created_at' => Carbon::now(),
        ]);


        $product_invoices = ProductInvoice::where('user_ip', request()->ip())->latest()->get();
        foreach ($product_invoices as $product) {
            InvoiceBillItem::insert([
                'order_id' => $order_id,
                'product_desc' => $product->product_desc,
                'warranty' => $product->warranty,
                'price' => $product->price,
                'product_qty' => $product->qty,
                'created_at' => Carbon::now(),
            ]);

            /*stock or outOfStock */
            $prod = ShopStock::where('product_name', $product->product_desc)->first();
            $prod->product_quantity = $prod->product_quantity - $product->qty;
            $prod->update();
        }

        CustomerInformation::insert([
            'order_id' => $order_id,
            'name' => $request->name,
            'date' => $request->date,
            'person' => $request->person,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'ref_by' => $request->ref_by,
            'sold_by' => $request->sold_by,
            'created_at' => Carbon::now(),
        ]);

        //delete from cart
        ProductInvoice::where('user_ip', request()->ip())->delete();

        return Redirect()->to('/admin_invoice_bill')->with('status_swal', 'Invoice/Bill Added Successfully');
    }

    //====================place_order_invoice_delete==========================
    public function place_order_invoice_delete($id)
    {

        DB::table('invoice_bills')->where('invoice_bills.id', $id)->delete();
        DB::table('customer_information')->where('customer_information.id', $id)->delete();
        DB::table('invoice_bill_items')->where('invoice_bill_items.order_id', $id)->delete();

        return Redirect()->back()->with('status_swal', 'Invoice/Bill Successfully Deleted');
    }

    public function admin_place_order_invoice_edit($id)
    {
        $invoice = InvoiceBill::find($id);
        $product  = DB::table('invoice_bill_items')->where('order_id', $id)->get();
        $customer_information = DB::table('customer_information')->where('order_id', $id)->first();
        $subtotal = DB::table("invoice_bill_items")->where('order_id', $id)->get()->sum(function ($item) {
            return $item->price * $item->product_qty;
        });
        return view("admin.InvoiceBill.edit_invoice", compact('invoice', 'product', 'customer_information', 'subtotal'));
    }

    //===========================Update Multiple table===================================
    public function place_order_invoice_updated(Request $request, $id)
    {
        $subtotal = DB::table("invoice_bill_items")->where('order_id', $id)->get()->sum(function ($item) {
            return $item->price * $item->product_qty;
        });

        InvoiceBill::where('id', $id)->update([ 
            'previous_due' => $request->previous_due,
            //'subtotal' => $subtotal,
            'subtotal' => $request->subtotal,
            'collecton' => $request->collecton,
            'updated_at' => Carbon::now(),
        ]);

        //===============InvoiceBillItem================
        foreach ($request->prodId as $key => $value) {
            $data = array(
                'product_desc' => $request->product_desc[$key],
                'warranty' => $request->warranty[$key],
                'price' => $request->price[$key],
                'product_qty' => $request->product_qty[$key],
            );
            InvoiceBillItem::where('id', $request->prodId[$key])
                ->update($data);
        }

        //===============CustomerInformation================
        CustomerInformation::where('order_id', $id)->update([
            'name' => $request->name,
            'date' => $request->date,
            'person' => $request->person,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'ref_by' => $request->ref_by,
            'sold_by' => $request->sold_by,
            'updated_at' => Carbon::now(),
        ]);

        return Redirect()->to('/admin_invoice_bill')->with('status_swal', 'Invoice/Bill updated Successfully');
    }


    //invoice_search
    public function invoice_search(Request $request)
    {
        $invoice_bill =  DB::table('customer_information')
            ->join('invoice_bills', 'customer_information.order_id', 'invoice_bills.id') //join
            //customer_information
            ->where('date', 'like',  $request->search )
            ->orWhere('name', 'like',  $request->search )
            //->orWhere('person', 'like', '%' . $request->search . '%')
            ->orWhere('phone', 'like',  $request->search )
            ->orWhere('email', 'like',  $request->search )
            ->orWhere('address', 'like',  $request->search )
            //->orWhere('ref_by', 'like', '%' . $request->search . '%')
            //->orWhere('sold_by', 'like', '%' . $request->search . '%')
            //invoice_bills
            ->orWhere('invoice_no', 'like',  $request->search )
            ->orWhere('subtotal', 'like',  $request->search )
            ->paginate(20);

        $query = DB::table('customer_information')->join('invoice_bills', 'customer_information.order_id', 'invoice_bills.id');
        $columns = ['date', 'name', 'address', 'phone', 'email', 'invoice_no'];
        foreach ($columns as $column) {
            $query->orWhere($column, $request->search);
        }
        $subtotal = $query->select('subtotal')->sum('subtotal');
        $bill_count = $query->select('subtotal')->count('subtotal');

        return view("admin.InvoiceBill.index", compact("invoice_bill", 'subtotal', 'bill_count'));
    }

    public function invoice_autocomplete_search_ajax()
    {
        $customer = CustomerInformation::get();
        $invoice = InvoiceBill::get();
        $data = [];

        foreach ($customer as $item) {
            $data[] = $item['name'];
            $data[] = $item['date'];
            //$data[] = $item['person'];
            $data[] = $item['phone'];
            $data[] = $item['email'];
            $data[] = $item['address'];
            //$data[] = $item['ref_by'];
            //$data[] = $item['sold_by'];
        }
        foreach ($invoice as $item) {
            $data[] = $item['invoice_no'];
            $data[] = $item['subtotal'];
        }
        return $data;
    }


    public function date_from_to_search(Request $request)
    {
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        $invoice_bill =  DB::table('customer_information')
            ->join('invoice_bills', 'customer_information.order_id', 'invoice_bills.id')
            ->whereBetween('date', [
                $fromDate,
                Carbon::parse($toDate)->endOfDay(),
            ])->paginate(20);

        $subtotal = DB::table('customer_information')
            ->join('invoice_bills', 'customer_information.order_id', 'invoice_bills.id')
            ->whereBetween('date', [$fromDate, Carbon::parse($toDate)->endOfDay(),])
            ->select('subtotal')->sum('subtotal');

        $bill_count = DB::table('customer_information')
            ->join('invoice_bills', 'customer_information.order_id', 'invoice_bills.id')
            ->whereBetween('date', [$fromDate, Carbon::parse($toDate)->endOfDay(),])
            ->select('subtotal')->count('subtotal');

        return view('admin.InvoiceBill.index', compact('invoice_bill', 'subtotal', 'bill_count'));
    }


//************************************************************************************************************************ */
    public function admin_payment_status()
    {
        $invoice_bill = DB::table('invoice_bills')
            ->join('customer_information', 'invoice_bills.id', 'customer_information.order_id') //join
            ->select("invoice_bills.*", "customer_information.name as name", "customer_information.date as date", "customer_information.phone as phone") //if same column in a table
            ->orderBy('invoice_bills.id', 'DESC')->where('payment_status', '=', 'Due')->paginate(20);

        $subtotal = DB::table('invoice_bills')->where('payment_status', '=', 'Due')->select('net_oustanding')->sum('net_oustanding');
        $bill_count = DB::table('invoice_bills')->where('payment_status', '=', 'Due')->select('net_oustanding')->count('net_oustanding');

        //dd($invoice_bill);
        return view("admin.InvoiceBill.due", compact('invoice_bill', 'subtotal', 'bill_count'));
    }

    public function admin_payment_status_paid()
    {
        $invoice_bill = DB::table('invoice_bills')
            ->join('customer_information', 'invoice_bills.id', 'customer_information.order_id') //join
            ->select("invoice_bills.*", "customer_information.name as name", "customer_information.date as date", "customer_information.phone as phone") //if same column in a table
            ->orderBy('invoice_bills.id', 'DESC')->where('payment_status', '=', 'Paid')->paginate(20);

        $subtotal = DB::table('invoice_bills')->where('payment_status', '=', 'Paid')->select('collecton')->sum('collecton');
        $bill_count = DB::table('invoice_bills')->where('payment_status', '=', 'Paid')->select('collecton')->count('collecton');

        //dd($invoice_bill);
        return view("admin.InvoiceBill.paid", compact('invoice_bill', 'subtotal', 'bill_count'));
    }


    public function invoice_search_payment_due(Request $request)
    {
        $invoice_bill =  DB::table('customer_information')
            ->join('invoice_bills', 'customer_information.order_id', 'invoice_bills.id') //join
            //customer_information
            ->where('date', 'like',  $request->search )
            ->orWhere('name', 'like',  $request->search )
            //->orWhere('person', 'like', '%' . $request->search . '%')
            ->orWhere('phone', 'like',  $request->search )
            ->orWhere('email', 'like',  $request->search )
            ->orWhere('address', 'like',  $request->search )
            //->orWhere('ref_by', 'like', '%' . $request->search . '%')
            //->orWhere('sold_by', 'like', '%' . $request->search . '%')
            //invoice_bills
            ->orWhere('invoice_no', 'like',  $request->search )
            ->orWhere('subtotal', 'like',  $request->search )
            ->where('invoice_bills.payment_status', '=', 'Due') ->paginate(20);
           
        $query = DB::table('customer_information')->join('invoice_bills', 'customer_information.order_id', 'invoice_bills.id');
        $columns = ['date', 'name', 'address', 'phone', 'email', 'invoice_no'];
        foreach ($columns as $column) {
            $query->orWhere($column, $request->search);
        }
        $subtotal = $query->where('payment_status', '=', 'Due')->select('net_oustanding')->sum('net_oustanding');
        $bill_count = $query->where('payment_status', '=', 'Due')->select('net_oustanding')->count('net_oustanding');

        return view("admin.InvoiceBill.due", compact("invoice_bill", 'subtotal', 'bill_count'));
    }


    public function invoice_search_payment_paid(Request $request)
    {
        $invoice_bill =  DB::table('customer_information')
            ->join('invoice_bills', 'customer_information.order_id', 'invoice_bills.id') //join
            //customer_information
            ->where('date', 'like',  $request->search )
            ->orWhere('name', 'like',  $request->search )
            //->orWhere('person', 'like', '%' . $request->search . '%')
            ->orWhere('phone', 'like',  $request->search )
            ->orWhere('email', 'like',  $request->search )
            ->orWhere('address', 'like',  $request->search )
            //->orWhere('ref_by', 'like', '%' . $request->search . '%')
            //->orWhere('sold_by', 'like', '%' . $request->search . '%')
            //invoice_bills
            ->orWhere('invoice_no', 'like',  $request->search )
            ->orWhere('subtotal', 'like',  $request->search )
            ->where('invoice_bills.payment_status', '=', 'Paid')->paginate(20);

        $query = DB::table('customer_information')->join('invoice_bills', 'customer_information.order_id', 'invoice_bills.id');
        $columns = ['date', 'name', 'address', 'phone', 'email', 'invoice_no'];
        foreach ($columns as $column) {
            $query->orWhere($column, $request->search);
        }
        $subtotal = $query->where('payment_status', '=', 'Paid')->select('collecton')->sum('collecton');
        $bill_count = $query->where('payment_status', '=', 'Paid')->select('collecton')->count('collecton');

        return view("admin.InvoiceBill.paid", compact("invoice_bill", 'subtotal', 'bill_count'));
    }

    public function add_due_payment($id)
    {
        $bill = InvoiceBill::find($id);
        return response()->json([
            'status' => 200,
            'bill' => $bill,
        ]);
    }

    public function due_payment_update(Request $request)
    {
        //find id
        $id = $request->input('id');
        $bill = InvoiceBill::find($id);
        $bill->collecton = $request->input('collecton') + $request->input('collecton_previous');
        $bill->net_oustanding = $request->input('net_oustanding') - $request->input('collecton');
        $bill->payment_status = $request->input('payment_status');
        $bill->update();
        
        return Redirect()->back()->with('status_swal', 'Payment Updated');
    }

}
