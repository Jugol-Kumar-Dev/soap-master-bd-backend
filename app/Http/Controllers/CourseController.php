<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\ChapterItem;
use App\Models\MoktestLink;
use DateTime;
use DateTimeZone;
use App\Models\Course;
use App\Models\Category;
use App\Models\Lesson;
use App\Models\Mocktest;
use App\Models\Zoom;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Mockery\Mock;
use Vimeo\Laravel\Facades\Vimeo;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        return inertia('Course/List', [
            'courses' => Course::query()->withCount('mocktests')
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->select("id", "name", 'cover', 'price')
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($course) => [
                    'id' => $course->id,
                    'name' => $course->name,
                    'cover' => $course->cover,
                    'price' => $course->price,
                    'show_url' => URL::route('courses.show', $course->id),
                    'edit_url' => URL::route('courses.edit', $course->id)
                ]),
            'filters' => Request::only(['search','perPage']),
            'url' => URL::route('courses.index')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        return inertia('Course/Create', [

            'categories' => Category::select('id', 'name')->get(),
            'url' => URL::route('courses.index')
            // 'can' => [
            //     'createUser' => Auth::user()->can('create', User::class)
            // ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        Request::validate([
            'cover' => "required",
            'name' => "required",
            'description' => "required",
            'price' => "required",
        ]);

        $image_path = '';
        if (Request::hasFile('cover')) {
            $image_path = Request::file('cover')->store('image', 'public');
        }

        $course = new Course();
        $course->title = Request::input('title');
        $course->name = Request::input('name');
        $course->description = Request::input('description');
        $course->achievement = Request::input('achievement');
        $course->finishing_achievement = Request::input('finishing_achievement');
        $course->video = Request::input('video');
        $course->cover = $image_path;
        $course->old_price = Request::input('old_price');
        $course->price = Request::input('price');
        $course->user_id = Auth::id();
        $course->time = Request::input('course_time');
        $course->save();

        return Redirect::route('courses.index');
    }


    public function show(Course $course)
    {
        $mainMock = array();
        $cMock = array();
        $course = Course::with(['mocktests', 'lessons.chapterItem.chapter', 'orders.user'])
            ->withCount('orders')
            ->findOrFail($course->id);

        $allMocktest = Mocktest::where('status', 1)->get();


        return inertia('Course/Show',[
            'course'    => $course ?? null,
            'mocktests' => $allMocktest ?? [],

            'lessons'   => Lesson::with('chapterItem.chapter')->where('course_id', $course->id)
                    ->orderBy('id', 'desc')->get()
                ?? [],

            'chapters'   => Chapter::query()->with('chapterItems')->get() ?? [],
            'chapters_items'   => ChapterItem::query()->with('chapter')->get() ?? [],


            'url'       => URL::route('courses.index'),
            'lesson_store_url' => URL::route('lessons.index'),
            "lesson_index" => URL::route('lessons.index'),
            'mocktests_create' => URL::route('mocktests.create'),
            'save_mocktest' => URL::route('save_mocktest', $course->id),
            'add_course_mitting' => URL::route('add_course_mitting'),
            'main_url' => URL::route('meetings.index'),
            'mock_delete_url' => URL::route('deleteMockFormCourse'),
            'chapter_url' => URL::route('chapter.index')

        ]);
    }

    public function saveMocktst(Request $request, $id){
        Request::validate([
//            "mock_id" => 'integer|unique:course_mocktest,moktest_link_id,'.Request::input('mock_id'),
            "mock_id" => 'integer|unique:course_mocktest,mocktest_id,NULL,id,course_id,'.Request::input('course_id'),
        ],[
            'mock_id.unique'=> "This Mock-test Already Exist In This Courses"
        ]);

        $mock = Mocktest::findOrFail(Request::input('mock_id'));
        $mock->courses()->attach(Request::input('course_id'));
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function edit(Course $course)
    {
        return inertia('Course/Update', [
            'course' => $course,
            'url' => URL::route('courses.index'),
            'categories' => Category::select('id', 'name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \never|void
     */
    public function update()
    {
        //
    }


    // course update methods

    public function updateCourses(Request $request, $id){

        $course = Course::findOrFail($id);

        $image_path = '';
        $files_path = '';

        if (Request::hasFile('cover')) {
            $image_path = public_path().'/'.$course->cover;
            if ($image_path){
                @unlink($image_path);
            }
            $image_path = Request::file('cover')->store('image', 'public');
        }else{
            if (Request::input('cover') != null){
                $old_path = explode('/', Request::input('cover'));
                $image_path = $old_path[2]."/".$old_path[3];
            }else{
                $image_path = NULL;
            }
        }


        if (Request::hasFile('files')) {
            $delete_path = public_path().'/'.$course->files;
            if ($delete_path){
                @unlink($delete_path);
            }
            $files_path = Request::file('files')->store('image', 'public');
        }else{
            if (Request::input('files') != null){
                $old_path = explode('/', Request::input('files'));
                $files_path = $old_path ? $old_path[2]."/".$old_path[3] : null;
            }else{
                $files_path = NULL;
            }
        }

        $course->name = Request::input('name');
        $course->description = Request::input('description');
        $course->content = Request::input('content');
        $course->cover = $image_path;
        $course->files = $files_path ?? null;
        $course->price = Request::input('price');
        $course->user_id = Auth::user()->id;
        $course->category_id = Request::input('category_id');
        $course->active_on = date("Y-m-d", strtotime(Request::input('active_on')));
        $course->access_time = Request::input('access_time');
        $course->access_type = Request::input('access_type') != "" ? Request::input('access_type') : NULL;
        $course->save();


        $vimeo_url = '';
        if (Request::hasFile('video')) {
            $vimeo_url = Vimeo::upload(Request::file('video'), [
                'name' => Request::input('name'),
                'description' => Request::input('description')
            ]);

            $course->video = $vimeo_url;
            $course->save();
        }



        return Redirect::route('courses.index');
    }


    public function updateCourseStatus($status, $id){
        Course::findOrFail($id)->update([
            'status' => $status
        ]);
        return back();
    }






    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return back();
    }



    public function deleteMockFormCourse(){
        $course = Course::findOrFail((int)Request::input('course_id'));
        $temp = $course->mocktests()->firstWhere('mocktest_id', Request::input('mock_id'));
        $temp->pivot->delete();
        return back();
    }

}
