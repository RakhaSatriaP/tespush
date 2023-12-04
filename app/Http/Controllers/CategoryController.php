<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\DB; 

class CategoryController extends Controller
{
    //
    public function index(){
        $categories = Category::all();

        return view('category.index', compact('categories'));
    }

    public function create(Request $request){
        $validateData = $request->validate([
            'name' => 'required|unique:categories|max:255',
        ]);
        $category = Category::create($validateData);
        if(isset($category)){
            return redirect('/category') ->with('success','Data Berhasil Dimasukan');
        }else{
            return redirect('/category') ->with('failed','Data gagal Dimasukan');
        }
    }

    public function destroy(Request $request){
        $category = Category::find($request->id);
        $category->delete();

        return response()->json(['status' => 'success', 'message' => 'Data Berhasil Dihapus']);
    }

    public function update(Request $request){
        $validateData = $request->validate([
            
            'name' => 'required|unique:categories|max:255',
        ]);
        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->save();

        return redirect('/category') ->with('success','Data Berhasil Diubah');
    }
}
