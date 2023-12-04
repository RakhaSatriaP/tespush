<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Models\Transaction;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(){
        $this->middleware(['auth', 'role:customer'])->only(['index', 'addToCart']);
    }
    public function index()
    {
        //
        $cartProducts = Cart::session(auth()->user()->id)->getContent();
        $count = $cartProducts->count();
        $total = Cart::session(auth()->user()->id)->getTotal();
        // return $cartProducts->count();
        return view('transaction.index', compact('cartProducts', 'count','total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $cartCollection = Cart::session(auth()->user()->id)->getContent();
        $cart = $cartCollection->toArray();
        foreach ($cart as $item) {
            $transaction = new Transaction;
            $transaction->user_id = auth()->user()->id;
            $transaction->product_id = $item['id'];
            $transaction->quantity = $item['quantity'];
            $transaction->total = $item['quantity'] * $item['price'];
            $transaction->save();

            $product = Products::find($item['id']);
            $product->stock = $product->stock - $item['quantity'];
            $product->save();
        }
        Cart::session(auth()->user()->id)->clear();
        

        return response()->json([
            'success' => true,
            'message' => 'Transaction success',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function addToCart(Request $request, string $id)
    {
        //
        $product = Products::find($id);
        $userID = auth()->user()->id;

        // check first is item exist, update qty
        $isItemExist = Cart::session($userID)->get($product->id);
        if ($isItemExist) {
            Cart::session($userID)->update($product->id, array(
                'quantity' => 1,
            ));
        } else {
            Cart::session($userID)->add(array(
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'attributes' => [
                    'image' => $product->image,
                ],
                'quantity' => 1,
            ));
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            // 'cart' => Cart::session($userID)->getContent(),
        ]);
        
    }

    public function deleteFromCart(Request $request, string $id)
    {
        //
        $product = Products::find($id);
        $userID = auth()->user()->id;

        // check first is item exist, update qty
        $isItemExist = Cart::session($userID)->get($product->id);
        if ($isItemExist) {
            Cart::session($userID)->remove($product->id);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product deleted from cart',
            // 'cart' => Cart::session($userID)->getContent(),
        ]);
        
    }

    public function minFromCart(Request $request, string $id)
    {
        //
        $product = Products::find($id);
        $userID = auth()->user()->id;

        // check first is item exist, update qty
        $isItemExist = Cart::session($userID)->get($product->id);
        if ($isItemExist) {
            if($isItemExist->quantity == 1) {
                Cart::session($userID)->remove($product->id);
                
            } else {
                Cart::session($userID)->update($product->id, array(
                    'quantity' => -1,
                ));
            }

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product reduce from cart',
            // 'cart' => Cart::session($userID)->getContent(),
        ]);
        
    }
}
