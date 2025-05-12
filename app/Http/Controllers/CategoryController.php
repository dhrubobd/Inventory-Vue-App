<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categoryPage(Request $request){
        $user_id = $request->header('id');
        $categories = Category::where('user_id', $user_id)->get();
        return Inertia::render('CategoryPage',['categories'=>$categories]);
    }

    public function categorySavePage(Request $request){
        $category_id = $request->query('id');
        $user_id = $request->header('id');
        $category = Category::where('id', $category_id)->where('user_id', $user_id)->first();
        return Inertia::render('CategorySavePage',['category'=>$category]);
    }
    public function createCategory(Request $request){
        $user_id = $request->header('id');

        Category::create([
            'name' => $request->name,
            'user_id' => $user_id
        ]);
        $data = ['message'=>'Category created successfully','status'=>true,'error'=>''];
        return redirect('/CategoryPage')->with($data);
    }
    public function categoryUpdate(Request $request){
        $user_id = $request->header('id');
        $id = $request->input('id');
        Category::where('id', $id)->where('user_id', $user_id)->update([
            'name' => $request->input('name')
        ]);
        $data = ['message'=>'Category Updaetd successfully','status'=>true,'error'=>''];
        return redirect('/CategoryPage')->with($data);
    }

    public function categoryDelete(Request $request,$id){
        $user_id = $request->header('id');
        Category::where('user_id', $user_id)->where('id', $id)->delete();

        $data = ['message'=>'Category Deleted successfully','status'=>true,'error'=>''];
        return redirect('/CategoryPage')->with($data);
    }
}
