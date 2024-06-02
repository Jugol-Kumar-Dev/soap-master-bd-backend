<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\GivenMocktestAnswer;
use App\Models\Mocktest;
use App\Models\MocktestAnswer;
use App\Models\MocktestUser;
use App\Models\MocktestUserQuestion;
use App\Models\MoktestLink;
use App\Models\Order;
use App\Models\Question;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Mockery\Mock;
use mysql_xdevapi\Exception;

class MockTestController extends Controller
{
    public  function index($id)
    {
        $user_id = Auth::user();
        $tests = Mocktest::query()
            ->where('show_student', 1)
            ->where('status', 1)
            ->select(['id', 'name', 'user_id'])
            ->withCount('givenAnswers')
            ->latest()->get()
            ->setHidden(['password']);


        $allTests = array();
        foreach ($tests as $test) {
            $allTests[] = [
                'mock' => $test,
                'user' => $test->givenAnswers > 0,
            ];
//            $temp = GivenMocktestAnswer::query()
//                ->where('mocktest_id', $test->id)
//                ->where('user_id', $user_id->id)
//                ->first();
//            $temp = $test->users()->firstWhere('user_id', $user_id->id);

        }

        $courses = Order::query()->with('course')->where('user_id', Auth::id())->where('is_show', 1)->pluck('course_id');
        $courses = Course::query()->with('mocktests:id,name')->whereIn('id', $courses)->get();

        $mocks = [];

        $allTests=[];
        foreach ($courses as $course){
            foreach ($course->mocktests as $mocktest){
                $mock = $mocktest->loadCount(['givenAnswers' => function ($query) {
                    $query->where('user_id', Auth::id());
                }]);

                $allTests[] = [
                    'mock' => $mock,
                    'user' => $mock->given_answers_count > 0
                ];
            }
        }



        $results = MocktestUser::query()
            ->with(['mocktest:id,name'])
            ->where('user_id', Auth::id())->get();

        return ['test' => $allTests, 'result' => $results];
    }


    public function singleMocktest($id)
    {
        $mock = Mocktest::query()->withCount([
            'questions',
            'givenAnswers' => function ($query) {
                $query->where('user_id', Auth::id());
            }
        ])->findOrFail($id)->setHidden(['password']);
        if($mock->users_count > 0){
            return false;
        }else{
            return $mock;
        }
    }

    public function checkMockPassword(Request $request, $id)
    {
        $mock = Mocktest::query()->findOrFail($id);
        if($mock->isPassword){
            $request->validate([
                'password' => 'required'
            ]);
        }

        if (!$mock || $mock->password !==  $request->input('password')){
            return response()->json([
                'message' => 'Password Not Valid...'
            ], 404);
        }

        if($mock->question_type != 'questions' && $mock->questions()->count()){
            DB::table('mocktest_questions')->where('mocktest_id', $mock->id)->where('user_id', Auth::id())->delete();
//            $mock->questions()->wherePivot('user_id')->detach();
        }

        $selectedCategories = json_decode($mock->categories ?? '') ?? [];
        $questions = Question::query()
            ->when($selectedCategories, function ($query)use($selectedCategories){
                $query->whereHas('category', function($q)use($selectedCategories){
                    $q->whereIn('id', $selectedCategories);
                });
            })
            ->inRandomOrder()
            ->pluck('id')
            ->take($mock->total_q);

        if ($mock->question_type != 'questions'){
            $mockQuestions = [];

            foreach ($questions as $question){
                $mockQuestions[] = [
                    'mocktest_id' => $mock->id,
                    'qustion_id' => $question,
                    'user_id' => Auth::id()
                ];
            }
            $mock->questions()->attach($mockQuestions);
        }

        if ($mock->question_type == 'questions'){
            DB::table('mocktest_questions')->where('mocktest_id', $mock->id)->where('user_id', Auth::id())->delete();

            foreach ($mock->questions as $question){
                $mockQuestions[] = [
                    'mocktest_id' => $mock->id,
                    'qustion_id' => $question->id,
                    'user_id' => Auth::id()
                ];
            }
            $mock->questions()->attach($mockQuestions);
        }
//        return response($mock->users, 500);
        $arr =[];
        if($mock){
            // exam given done here.
            $examToken = Str::random(10);
            $arr[] = [
                'mocktest_id' => $mock->id,
                'user_id' => Auth::id(),
                'exam_token' => $examToken,
            ];
            $mock->users()->attach($arr);
            Session::push('mock_token', $examToken);
            return [
                'examToken' => $examToken
            ];
        }else{
            return response()->json([
                'message' => 'Password Not Valid...'
            ], 404);
        }
    }

    public function mockQuestions($id)
    {
        $mock = Mocktest::query()->with('users')->select(['id', 'duration', 'total_q'])->findOrFail($id); //->wherePassword($request->input('password'))
        $mock->isShowQustions = true;

        if(\request()->has('mockId')){
            $data[] = [
                'mocktest_id' => request()->input('mockId'),
                'user_id' => Auth::id(),
                'question_id' => request()->input('qId'),
                'user_answer' => \request()->input('qAns'),
            ];
            $mock->givenAnswers()->attach($data);
        }


//        'data' => DB::table('mocktest_questions')
//        ->where('mocktest_id', $mock->id)
//        ->where('user_id', Auth::id())
//        ->paginate(1),
//        return $mock->users->count();
//        if(empty($mock) || $mock->users?->count() > 0) return false;


        return [
            'data' => $mock->questions()->where('user_id', Auth::id())->select('questions.id', 'questions.title')->paginate(1),
            'mock' => $mock,
            'answer_details' => request()->has('mockId') ? \request()->all() : 'no have answer details'
        ];
    }

    public function saveMockExam(Request $request)
    {
        $mock = Mocktest::query()->with('users')->findOrFail($request->input('mockId'));
        if(\request()->has('mockId')){
            $data[] = [
                'mocktest_id' => request()->input('mockId'),
                'user_id' => Auth::id(),
                'question_id' => request()->input('qId'),
                'user_answer' => request()->input('qAns'),
            ];
            $mock->givenAnswers()->attach($data);
        }

        DB::table('mocktest_questions')->where('mocktest_id', $mock->id)->where('user_id', Auth::id())->delete();

        return true;
/*

        $marks = 0;
        $correct = 0;
        $incorrect = 0;


        $mock = Mocktest::query()->withCount('users')->findOrFail($request->input('mockId'));
        if($mock->users_count > 0) return false;

        $givenMockTestData = [];
        foreach ($request->input('answares') as $item){
            $qus = Question::findOrFail($item["id"]);
            if($item["answare"] == Str::lower($qus->answer)){
                $marks += $qus->mark;
                $correct++;
            }else{
                $incorrect++;
            }

            $givenMockTestData[] = [
                'mocktest_id' => $mock->id,
                'qustion_id' => $qus->id,
                'user_id' => Auth::id(),
                'given_ans' => $item["answare"],
                'is_correct' => $item["answare"] == Str::lower($qus->answer),
                'is_anserd' => (bool)$item['answare'],
            ];
        }

        $mock->users()->attach([$mock->id =>[
            'user_id' => Auth::id(),
            'mark' => $marks,
            'total_correct' => $correct,
            'total_incaorrect' => $incorrect
        ]]);

        GivenMocktestAnswer::query()->insert($givenMockTestData);

        if ($mock->question_type != 'questions') {
            $mock->exminers()->where('user_id', Auth::id())->detach();
        }

        return true;*/
    }


    public function showResult($givenExamId)
    {
        $resultDetails = MocktestUser::query()->with('mocktest')->find($givenExamId);
        $answers =  MocktestAnswer::query()
            ->with('question')
            ->where('mocktest_id', $resultDetails->mocktest->id)
            ->where('user_id', Auth::id())
            ->get();

        $marks = 0;
        $correct = 0;
        $incorrect = 0;

        foreach ($answers as $item) {
            if ($item["user_answer"] == Str::lower($item->question->answer)) {
                $marks += $item->question->mark;
                $correct++;
            } else {
                $incorrect++;
            }
        }

        $givenAnsweres = [
          'marks' => $marks,
          'correct' => $correct,
          'incorrect' => $incorrect,
          'totalAnswered' => count($answers)
        ];

        return \response()->json([
            'examDetails' => $resultDetails,
            'ansCountes' => $givenAnsweres,
            'answerDetails' => $answers
        ], 200);

    }

}
