<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class CategoryController extends Controller { public function index(){return view('admin.categories.index',['categories'=>Category::withCount('books')->latest()->paginate(15)]);} public function create(){return view('admin.categories.form',['category'=>new Category]);} public function store(Request $r){$data=$r->validate(['nama_kategori'=>'required|max:100','deskripsi'=>'nullable|string']);$data['slug']=Str::slug($data['nama_kategori']);Category::create($data);return redirect()->route('admin.categories.index')->with('success','Kategori ditambahkan.');} public function edit(Category $category){return view('admin.categories.form',compact('category'));} public function update(Request $r,Category $category){$data=$r->validate(['nama_kategori'=>'required|max:100','deskripsi'=>'nullable|string']);$data['slug']=Str::slug($data['nama_kategori']);$category->update($data);return redirect()->route('admin.categories.index')->with('success','Kategori diperbarui.');} public function destroy(Category $category){$category->delete();return back()->with('success','Kategori dihapus.');} }
