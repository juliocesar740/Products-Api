<?php

use App\Models\Category;

/**
 * Get the category by the id
 */
function getCategory($category_id)
{
    return Category::where('category_id',($category_id))->first();
}

/**
 * Format the route parameter
 */
function formatRouteParameter($string){
    return join('-',explode(' ',$string));
}
