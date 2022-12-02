<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;

class AdminBlogController extends Controller
{
    // 블로그 내용 화면
    public function index()
    {
        $blogs = Blog::latest('updated_at')->paginate(10);
        return view('admin.blogs.index', ['blogs' => $blogs]);
    }

    // 블로그 등록 화면
    public function create()
    {
        return view('admin.blogs.create');
    }

    // 블로그 등록 처리
    public function store(StoreBlogRequest $request)
    {
        $savedImagePath = $request->file('image')->store('blogs', 'public');
        $blog = new Blog($request->validated());
        $blog->image = $savedImagePath;
        $blog->save();

        return to_route('admin.blogs.index')->with('success', '블로그 등록 했습니다.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    // 지정한 ID의 블로그 편집화면
    public function edit($id)
    {
        // 페이지 찾는 메소드 = find
        // $blog = Blog::find($id);
        // 페이지 찾았는데 데이터 베이스에 없으면 없다고 404 에러 띄게 만들기
        $blog = Blog::findOrFail($id);
        return view('admin.blogs.edit', ['blog' => $blog]);
    }

    // 지정한 ID의 블로그 업데이트 처리
    public function update(UpdateBlogRequest $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $updateData = $request->validated();

        if ($request->has('image')){
            // 변경전 이미지 삭제
            Storage::disk('public')->delete($blog->image);
            // 변경후의 이미지 업데이트, 저장path를 업데이트대상 데이터에 세트
            $updateData['image'] = $request->file('image')->store('blogs', 'public');
        }
        $blog->update($updateData);

        // 완료되면 블로그 리스트 화면으로 이동
        return to_route('admin.blogs.index')->with('success', '블로그 업데이트 완료');
    }

    // 지정한 ID의 블로그 삭제 처리
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog -> delete();
        Storage::disk('public')->delete($blog->image);
        
        // 완료되면 블로그 리스트 화면으로 이동
        return to_route('admin.blogs.index')->with('success', '블로그 지우기 완료');
    }
}
