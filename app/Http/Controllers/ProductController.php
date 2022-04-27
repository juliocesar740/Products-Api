<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    // ------------------
    // Web routes methods
    // ------------------

    /**
     * Show all products
     */
    public function display(Request $request)
    {
        // Input sortByDate
        $order = ['newest' => 'desc', 'oldest' => 'asc'];
        $products_order = $order[$request->input('sortByDate')] ?? 'desc';
        $sortByDate = $request->has('sortByDate') ? $request->input('sortByDate') : 'newest';

        // Input Search
        $search = $request->input('search');
        $products_search = $request->has('search') ?
            Product::select('*')->where('name', 'like', '%' . $search . '%')->orderBy('created_at', $products_order)->paginate(5) :
            null;

        $products = $products_search ?? Product::select('*')->orderBy('created_at', $products_order)->paginate(5);

        return view('products', [
            'products' => $products,
            'products_json' => json_decode($products->toJson()),
            'sortByDate' => $sortByDate,
            'search' => $search
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate the input fields
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'unique:products'],
            'price' => ['required', 'digits_between:3,6'],
            'description' => ['max:150','nullable'],
            'category' => ['required'],
            'picture' => ['file','nullable']
        ]);

        // return the errors of the input fields
        if ($validator->fails()) {
            return back()->withErrors($validator, 'productCreate');
        }

        $validated_data = $validator->validated();
        $product_data = [...$validated_data];

        $product_data['category'] = Category::where('name', $product_data['category'])->first()['category_id'];

        // handle file input
        if ($request->hasFile('picture')) {

            $filename = explode('\\', $request['file_path'])[2];
            $product_data['file_path'] = "images/$filename";

            Storage::putFileAs('/public/images', $product_data['picture'], $filename);
            unset($product_data['picture']);
        }

        // create new product
        $product = Product::create(['id' => Str::uuid(), ...$product_data]);

        return redirect('/')->with('product_msg', "Product $product[name] was created successfully");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validate the input fields
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'price' => ['required','digits_between:1,7'],
            'description' => ['max:150','nullable'],
            'category' => ['required'],
            'picture' => ['file','nullable']
        ]);

        // return the errors of the input fields
        if ($validator->fails()) {
            return back()->withErrors($validator, 'productUpdate');
        }

        $validated_data = $validator->validated();

        $product_data = $validated_data;
        $product_data['category'] = Category::where('name', $product_data['category'])->first()['category_id'];

        // Check the name
        if($request->has('name') && Product::where('id',$id)->first()['name'] === $request->input('name') ){
            $product_data['name'] = $request->input('name');
        }
        else if($request->has('name') && Product::where('name',$request->input('name'))->first()['name'] === $request->input('name')){
            return back()->with('product_error_msg','The name was already taken');
        }
        else{
            $product_data['name'] = $request->input('name');
        }

        // handle file input
        if ($request->hasFile('picture')) {

            $filename = explode('\\', $request['file_path'])[2];
            $product_data['file_path'] = "images/$filename";

            Storage::putFileAs('/public/images', $product_data['picture'], $filename);
            unset($product_data['picture']);
        }

        // update product
        Product::where('id', $id)->update([...$product_data]);

        $product = Product::where('id', $id)->first();

        return redirect('/')->with('product_msg', "Product $product[name]  was updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::where('id', $id)->first();

        // redirect to the homepage if there is not product
        if (!$product) {
            return redirect('/');
        }

        // delete image
        if ($product['file_path']) {
            Storage::delete('public/' . $product['file_path']);
        }

        // delete product
        Product::where('id', $id)->delete();

        return redirect('/')->with('product_msg', "Product $product[name] was deleted successfully");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('addProduct', ['categories' => Category::all()]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        return response()->json(['product' => Product::where('name', $name)->first()], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($name)
    {
        return view('updateProduct', [
            'product' => Product::where('name', Str::replace('-', ' ', $name))->first(),
            'categories' => Category::all()
        ]);
    }
}
