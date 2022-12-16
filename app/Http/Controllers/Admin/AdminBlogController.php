<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Cat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AdminBlogController extends Controller
{
    // 블로그 리스트 보여주는 화면
    public function index(Request $request)
    {
        $search = $request->input('search');    // 検索フォームで入力された値を取得する
        $filter = $request->input('filter');    // filter form의 입력값을 획득
        $query = Blog::query();  // query builder 쿼리 빌더
        $data = $request->all(); // paginate의 다음 버튼을 눌러도 작동하게하는 코드 (To hold all request as the filter and pass this data to view) 
       
        
       // もし検索フォームにキーワードが入力されたら
       if ($search) {

            // 全角スペースを半角に変換
            $spaceConversion = mb_convert_kana($search, 's');

            // 単語を半角スペースで区切り、配列にする（例："山田 翔" → ["山田", "翔"]）
            $wordArraySearched = preg_split('/[\s,]+/', $spaceConversion, -1, PREG_SPLIT_NO_EMPTY);

            // 単語をループで回し、ユーザーネームと部分一致するものがあれば、$queryとして保持される
            foreach($wordArraySearched as $value) {
                $query->where('title', 'like', '%'.$value.'%');
            }

            // 上記で取得した$queryをページネートにし、変数$usersに代入
            $blogs = $query->paginate(10);

        }

        // filter 값이 있을 경우
        if ($filter) {

            if ($filter = "0"){
                $blogs = Blog::latest('updated_at')->paginate(10);
            } elseif ($filter = "1") {
                $blogs = Blog::oldest('updated_at')->paginate(10);
            }
        }   // filter 값이 없을 경우
        else{
            $blogs = Blog::latest('updated_at')->paginate(10);
        }

        $user = Auth::user();
        return view('admin.blogs.index', ['blogs' => $blogs, 'user' => $user, 'data' => $data]);
    }

    // 블로그 등록 화면
    public function create()
    {
        $categories = Category::all(); // 카테고리 보여주는 코드
        $cats = Cat::all();
        return view('admin.blogs.create', ['categories' => $categories, 'cats'=>$cats]);
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
    public function edit(Blog $blog)
    {
        // 페이지 찾는 메소드 = find
        // $blog = Blog::find($id);
        // 페이지 찾았는데 데이터 베이스에 없으면 없다고 404 에러 띄게 만들기
        // $blog = Blog::findOrFail($id);
        $categories = Category::all(); // 카테고리 보여주는 코드
        $cats = Cat::all();
        return view('admin.blogs.edit', ['blog' => $blog, 'categories' => $categories, 'cats'=>$cats]);
    }

    // 지정한 ID의 블로그 업데이트 처리
    public function update(UpdateBlogRequest $request, $id)
    {
        $blog = Blog::findOrFail($id);  // 데이터 베이스에서 지정한 ID 가져오기
        $updateData = $request->validated();

        //  이미지를 변경할 경우
        if ($request->has('image')){
            // 변경전 이미지 삭제
            Storage::disk('public')->delete($blog->image);
            // 변경후의 이미지 업데이트, 저장path를 업데이트대상 데이터에 세트
            $updateData['image'] = $request->file('image')->store('blogs', 'public');
        }

        $blog->category()->associate($updateData['category_id']);
        $blog->cats()->sync($updateData['cats']);
        $blog->update($updateData);

        // 완료되면 블로그 리스트 화면으로 이동
        return to_route('admin.blogs.index')->with('success', '블로그 업데이트 완료');
    }

    // 지정한 ID의 블로그 삭제 처리
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id); 
        $blog -> delete();  # delete 메소드 불러오기
        Storage::disk('public')->delete($blog->image); # 필요없어진 사진도 지우기
        

        
        // 완료되면 블로그 리스트 화면으로 이동
        return to_route('admin.blogs.index')->with('success', '블로그 지우기 완료');
    }
}
