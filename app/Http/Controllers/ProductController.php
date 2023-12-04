<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Products;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(){
        $this->middleware(['auth', 'role:customer'])->only(['index']);
        //owner all access
        // $this->middleware(['auth', 'role:owner'])->only(['index', 'store', 'update', 'destroy']);




    }
     public function index()
    {
        //return view
        $category = Category::all();
        $products = Products::all();
        return view('products.index', compact('category', 'products') );
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
        $validatedData = $request->validate([
            'name' => 'required|unique:products|max:255',
            'price' => 'required',
            'stock' => 'required',
            'category_id' => 'required',
            'image' => 'required',
        ]);

        if($request->hasFile('image') ){

            $filename = $request->file('image')->getClientOriginalName();
            // return $filename;
            $validatedData['image'] = $request->file('image')->storeAs('Product-image',  time() .'-' . $filename );

        }
        $product = Products::create($validatedData);
        if(isset($product)){
            return redirect('/product') ->with('success','Data Berhasil Dimasukan');
        }else{
            return redirect('/product') ->with('failed','Data gagal Dimasukan');
        }
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
        $product = Products::find($id);

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required',
            'stock' => 'required',
            'category_id' => 'required',
            'image' => 'image|file|max:8000',
        ]);
        if($request->hasFile('image') ){
                
                $filename = $request->file('image')->getClientOriginalName();
                // return $filename;
                $validatedData['image'] = $request->file('image')->storeAs('Product-image',  time() .'-' . $filename );
                
            }
        
            $product = Products::where('id', $request->id)->update($validatedData);
            if(isset($product)){
                return redirect('/product') ->with('success','Data Berhasil Diubah');
            }else{
                return redirect('/product') ->with('failed','Data gagal Diubah');
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $product = Products::find($id);
        $product->delete();
        return response()->json(['status' => 'success', 'message' => 'Data Berhasil Dihapus']);
    }
}
