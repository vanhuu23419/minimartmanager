<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Receipt;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ReceiptProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellController extends Controller
{
    public function index()
    {
        $categories = Category::get();
        $products = Category::first()->products->take(30);

        return view('sell.index',[
            'categories' => $categories,
            'products' => $products,
        ]);
    }

    public function products(Request $req)
    {
        $categoryId = $req->get('categoryId');
        $numProducts = $req->get('numProducts');
        $search =  $req->get('search');

        $query = 
            DB::table('Products')->select(['Products.*'])
            ->leftJoin('ProductCategories', 'Products.id', '=', 'product_id' )
            ->whereRaw("category_id = {$categoryId}")
            ->skip($numProducts)->take((30));
        if ($search)
        {
            $query->whereRaw("Products.name LIKE '%{$search}%'");
        }
        $products = $query->get();
        if ($products->count() > 0) {
            return view('sell.products', [ 'products' => $products ]);
        }
        return '';
    }

    public function addToReceipt(Request $req)
    {
        $product = Product::whereRaw("id = {$req->get('productId')}")->first();
        if ( ! $product ) 
        {
            return '';
        }
        return view('sell.receiptItem', [
            'product' => $product,
            'quantity' => $req->get('quantity') ?? 1,
        ]);
    }   

    public function saveReceipt(Request $req)
    {
        /*
        Create Receipt's Products
        */
        $receiptId = (Receipt::max('id')??0) + 1;
        $numProducts = 0;
        $total = 0;
        $receiptItems = json_decode($req->get('items'), true);
        
        foreach ($receiptItems as $item) {
            
            $product = Product::where('id', '=', $item['id'])->first();
            ReceiptProduct::create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_cost' => $product->cost,
                'product_price' => $product->price,
                'quantity' => $item['quantity'],
                'receipt_id' => $receiptId,
            ]);

            $numProducts += $item['quantity'];
            $total += $product->price * $item['quantity'];
        }

        /*
        Create Receipt
        */
        $received = floatval($req->get('received'));
        $change = ($received >= $total) ? $received - $total : 0;
        $status = ($received >= $total) ? 'payed' : 'saved';
        Receipt::create([
            'created_at' => date('Y-m-d H:i:s', strval(time())),
            'status' => $status,
            'total' => $total,
            'received' => $received,
            'change' => $change,
            'num_products' => $numProducts,
        ]); 

        return $receiptId;
    }

    public function printReceipt($id)
    {
        $receipt = Receipt::where('id', '=', $id)->first();
        $receiptItems = ReceiptProduct::where('receipt_id', '=', $id)->get();
        return view('sell.print', [
            'receipt' => $receipt,
            'receiptItems' => $receiptItems
        ]);
    }
}
