<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * 话题帖子页
     *
     * @param Category $category
     * @return
     */
    public function show(Category $category, Request $request, Topic $topic)
    {
        // 读取分类 ID 下的话题，按每 20 条分页
        $topics = $topic->withOrder($request->order)
                        ->where('category_id', $category->id)
                        ->paginate(20);

        // 传参变量话题和分类到模板中
        return view('topics.index', compact('topics', 'category'));
    }
}
