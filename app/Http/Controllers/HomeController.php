<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Category;
use App\Models\CombinedLedger;
use App\Models\Contact;
use App\Models\GodownStock;
use App\Models\InvoiceBill;
use App\Models\InvoiceBillItem;
use App\Models\LedgerCustomer;
use App\Models\Order;
use App\Models\Product;
use App\Models\PurchaseReturn;
use App\Models\ShopStock;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{


    // after login -> user & admin
    public function redirect(Request $request)
    {
        if (Auth::id()) {
            if (Auth::user()->usertype == '0') // 0=>Frontend dashboard home
            {
                return view('frontend.index');
            } else {
                $invoice = InvoiceBill::count();
                $ledger_customer = LedgerCustomer::count();
                $category = Category::count();
                $stock = ShopStock::count();
                $godown = GodownStock::count();
                $contact = Contact::count();
                $contact_unseen = Contact::where('status', '=', 'Progress')->count();
                $user = User::where('usertype', '=', '0')->count();
                $admin = User::where('usertype', '=', '1')->count();
                $people = User::count();
                $godown = GodownStock::count();
                $purchase_return=PurchaseReturn::count();
                $subtotal = DB::table('invoice_bills')->select('subtotal')->sum('subtotal');


                $shop_stocks = DB::table('shop_stocks')->get('*')->toArray();
                foreach ($shop_stocks as $row) {
                    $data[] = array(
                        'label' => $row->product_name,
                        'y' => $row->per_selling_price
                    );
                }
                return view('admin.index', ['data' => $data], compact('invoice', 'ledger_customer', 'godown', 'stock', 'category', 'contact', 'contact_unseen', 'user', 'admin', 'subtotal', 'people', 'purchase_return'));
            }
        } else {

            return redirect()->back();
        }
    }


    public function index()
    {
        return view('frontend.index');
    }
}
