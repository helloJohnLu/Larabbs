<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * 话题列表
     *
     * @param Request $request
     * @param Topic $topic
     * @return
     */
	public function index(Request $request, Topic $topic)
	{
		$topics = $topic->withOrder($request->order)->paginate(20);

		return view('topics.index', compact('topics'));
	}

    /**
     * 话题详情页
     *
     * @param Topic $topic
     * @return
     */
    public function show(Topic $topic)
    {
        return view('topics.show', compact('topic'));
    }

    /**
     * 新建话题
     *
     * @param Topic $topic
     * @return
     */
	public function create(Topic $topic)
	{
	    // 读取所有分类
	    $categories = Category::all();

		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

    /**
     * 处理 新建或编辑话题 表单提交
     *
     * @param TopicRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
	public function store(TopicRequest $request, Topic $topic)
	{
		$topic->fill($request->all());
		$topic->user_id = \Auth::id();
		$topic->save();

		return redirect()->route('topics.show', $topic->id)->with('message', 'Created successfully.');
	}

	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
		return view('topics.create_and_edit', compact('topic'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		return redirect()->route('topics.show', $topic->id)->with('message', 'Updated successfully.');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('message', 'Deleted successfully.');
	}
}