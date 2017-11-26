<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Topic;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(Category $category, Request $request, Topic $topic)
    {
        $topics = $topic->withOrder($request->order)->where('category_id', $category->id)->paginate(20);
        $active_users = (new User)->getActiveUsers();
        return view('topics.index', compact('topics', 'category', 'active_users'));
    }
}