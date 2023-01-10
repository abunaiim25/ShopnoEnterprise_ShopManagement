<?php

namespace App\Http\Controllers;

use App\Models\ShopStock;
use Illuminate\Http\Request;

class SearchControll extends Controller
{
    public function product_list_ajax(){
        $product = ShopStock::select('product_name')->where('status', '1')->where('product_quantity', '>', 0)->get();
        $data = [];
        
        foreach ($product as $item){
            $data[] = $item['product_name'];
        }
        return $data;
    }

    public function autocompleteSearch(Request $request)
    {
        $query = $request->get('query');
        //$filterResult = ShopStock::select('product_name')->where('status', '1')->where('product_quantity', '>', 0)->where('product_name', 'LIKE', '%' . $query . '%')->get();
        $filterResult = ShopStock::where('product_name', 'LIKE', '%' . $query . '%')->get();
        return response()->json($filterResult);
    } 
}