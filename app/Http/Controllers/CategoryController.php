<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // ------------------
    // Web routes methods
    // ------------------

    /**
     * Show all categories
     */
    public function display(request $request)
    {
        // Input sortByDate
        $order = ['newest' => 'desc', 'oldest' => 'asc'];
        $categories_order = $order[$request->input('sortByDate')] ?? 'desc';
        $sortByDate = $request->has('sortByDate') ? $request->input('sortByDate') : 'newest';

        // Input Search
        $search = $request->input('search');
        $categories_search = $request->has('search') ?
            Category::select('*')->where('name', 'like', '%' . $request->input('search') . '%')->orderBy('created_at', $categories_order)->paginate(5) :
            null;

        $categories = $categories_search ?? Category::select('*')->orderBy('created_at', $categories_order)->paginate(5);

        return view('categories', [
            'categories' => $categories,
            'categories_json' => json_decode($categories->toJson()),
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
            'name' => ['required', 'unique:categories']
        ]);

        // return the errors of the input fields
        if ($validator->fails()) {
            return back()->withErrors($validator, 'categoryCreate');
        }

        $validated_data = $validator->validated();

        // create new category
        $category = Category::create(['category_id' => Str::uuid(), ...$validated_data]);

        return redirect('/')->with('category_msg', "category $category[name] was created successfully");
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
        $validator = Validator::make($request->all(), ['name' => ['required']]);

         // return the errors of the input fields
        if ($validator->fails()) {
            return back()->withErrors($validator, 'categoryUpdate');
        }

        $validated_data = $validator->validated();
        $category_data = $validated_data;

        // Check the name
        if ($request->has('name') && Category::where('category_id', $id)->first()['name'] === $request->input('name')) {
            $category_data['name'] = $request->input('name');
        } 
        else if ($request->has('name') && Category::where('name', $request->input('name'))->first()['name'] === $request->input('name')) {
            return back()->with('category_error_msg', 'The name was already taken');
        } 
        else {
            $category_data['name'] = $request->input('name');
        }

        // update category
        Category::where('category_id', $id)->update(['name' => Str::lower($category_data['name'])]);

        $category = Category::where('category_id', $id)->first();

        return redirect('/')->with('category_msg', "Category $category[name] was updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::where('category_id', $id)->first();

        if (!$category) {
            return redirect('/');
        }

        Category::where('category_id', $id)->delete();

        return redirect('/')->with('category_msg', "Category $category[name] was deleted successfully");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('addCategory', ['categories' => Category::all()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        return Category::where('name', $name)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($name)
    {
        $name_formatted = Str::replace('-', ' ', $name);
        return view('updateCategory', ['category' => Category::where('name', $name_formatted)->first()]);
    }
}
