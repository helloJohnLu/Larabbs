<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
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

		return redirect()->route('topics.show', $topic->id)->with('message', '成功创建话题');
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

		return redirect()->route('topics.show', $topic->id)->with('message', '更新成功');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('message', '删除成功');
	}

    /**
     * 图片上传
     *
     * @param Request $request                      获取表单上传图片
     * @param ImageUploadHandler $uploadHandler     自定义的图片上传处理类
     * @return array                                返回数组，会解析成 JSON
     */
    public function uploadImage(Request $request, ImageUploadHandler $uploadHandler)
    {
        // 初始化返回数据，默认是失败的
        $data = [
            'success'       =>  false,
            'msg'           =>  '上传失败！',
            'file_path'     =>  ''
        ];

        // 判断是否有上传文件，并赋值给 $file
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $result = $uploadHandler->save($request->upload_file, 'topics', \Auth::id(), 1024);

            // 图片保存成功的话
            if ($result) {
                $data['file_path']  = $result['path'];
                $data['msg']        = '上传成功';
                $data['success']    = true;
            }
        }

        return $data;
    }
}