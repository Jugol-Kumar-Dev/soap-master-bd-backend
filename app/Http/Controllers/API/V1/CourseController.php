<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CourseCollection;
use App\Http\Resources\V1\CourseModuleCollection;
use App\Http\Resources\V1\CourseResource;
use App\Http\Resources\V1\CourseVideoCollection;
use App\Http\Resources\V1\LessonResource;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Order;
use App\Models\Zoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $courses = Course::query()->with(['chapters.chapterItems.lessons'])->get();

        return response()->json([
           "data" => $courses
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Course $course)
    {

        $course->load(['chapters.chapterItems.lessons']); //->get();

//        $course = Course::with(['chapters:id,course_id,chapter_title,chapter_details', 'chapters.videos:chapter_id,id,name', 'user:id,name,email'])
//            ->withCount(['mocktests', 'zoomes', 'chapters', 'lessons', 'orders'])
//            ->findOrFail($course->id);
//
//        $course->courseVideos = $course->lessons()->orderBy('id', 'desc')->doesntHave('chapter')->get();
//

        return response()->json($course, 200);
//        return new CourseResource($course->load('chapters', 'chapters.videos', 'user')->loadCount(['chapters', 'chapterVideos']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return CourseCollection
     */
    public function myCourses(Request $request)
    {
        $courses = Order::query()->with('course')->where('user_id', Auth::user()->id)->get();

//        $courses = Order::query()->where('user_id', $request->user()->id)->pluck('course_id')->toArray();
//        if(count($courses)){
//            $courses = Course::query()->whereIn('id', array_unique($courses))->get();
//        }
        return new CourseCollection($courses);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */


    public function courseZoomClasses($id){
        return $id;
    }



    public function singleCourse($id){

//        $course = Course::with([
//            'chapters' => function ($query) {
//                $query->whereHas('videos', function ($query) {
//                    $query->whereNotNull('video');
//                });
//            },
//            'chapters.videos',
//
//        ])->find($id);


        $course = Course::with(['chapters', 'chapters.videos', 'lessons', 'user', 'mocktests', 'mocktests.questions', 'zoomes'])
            ->withCount(['chapters', 'chapterVideos'])->findOrFail(intval($id));

        $course->courseLessons = $course->lessons()->orderBy('id', 'desc')->doesntHave('chapter')->get();

        return response()->json($course, 200);

    }



    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return CourseCollection
     */
    public function courseVideos($id)
    {
        $lesson = Lesson::query()->whereNotNull('file')->where('course_id', $id)->get();
        return new CourseCollection($lesson);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return string
     */
    public function courseModules($id)
    {
        $contentType =request()->only(['module_type']);
        $lesson = Lesson::query()
            ->whereNotNull($contentType['module_type'])
            ->where('course_id', $id)
            ->get();
        return new CourseModuleCollection($lesson);
    }

    public function singleFile($id)
    {
        $lesson = Lesson::query()->where('id', $id)->first();

        return $lesson;
//        return new LessonResource($lesson);
    }


    public function courseContent(Request $request, $id){


//        $course = Course::with(['chapters', 'chapters.videos', 'lessons', 'user', 'mocktests', 'zoomes'])
//            ->withCount(['chapters', 'chapterVideos'])->findOrFail(intval($id));
//
//        $course->courseLessons = $course->lessons()->orderBy('id', 'desc')->doesntHave('chapter')->get();


        $lessonId = $request->input('lessonId');
        if($id && $lessonId != "null"){
            $lesson = Chapter::query()->with('videos')->where('id', $lessonId)->first();
        }else{
            $course = Course::findOrFail($id);
            $lesson = new Chapter();
            $lesson->videos = $course->lessons()->orderBy('id', 'desc')->doesntHave('chapter')->get();
            $lesson->chapter_title = "Without Chapter";
            $lesson->chapter_details = "Without Course Video Descriptions";
        }

        return $lesson;
    }

}
