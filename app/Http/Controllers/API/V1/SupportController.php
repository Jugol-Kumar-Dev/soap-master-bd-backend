<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\BusinessSettingController;
use App\Http\Controllers\Controller;
use App\Mail\ForgatePasswordMail;
use App\Mail\SendVerificationMail;
use App\Models\Course;
use App\Models\Faq;
use App\Models\Lesson;
use App\Models\Mocktest;
use App\Models\Page;
use App\Models\Review;
use App\Models\Slider;
use App\Models\Suport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class SupportController extends Controller
{

    public function store(Request $request){

        $data = $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'subject'=>'required|max:1000',
            'message'=>'required|max:50000',
        ]);

        if (Auth::check()){
            $data['user_id'] = Auth::id();
        }
        $support  = Suport::create($data);

        $user = User::where('email', 'admin@amcpaedia.com')->first();
//        Notification::sendNow($user, new SupportNotification($support));

        return response()->json(['message' => 'Your Message Sended....'], 200);
//        return redirect()->back()->with('success','Your message send success');
    }

    public function getFaqs()
    {
        $faqs = Faq::get()->groupBy('category');
        return response()->json(['data' => $faqs]);
    }


    public function getSliders()
    {
        $sliders = Slider::query()->select('bg_image')->get();
        return response()->json([
            'data' => $sliders,
            'userCount' => User::query()->count(),
            'couseCount' => Course::count(),
            'videoCount' => Lesson::count(),
            'mockCount' => Mocktest::count()
        ]);
    }

    public function refendEmail($email, $forgate = false){
        $user = User::where("email", $email)->first();

        if ($forgate){
            if ($user && $user != null){
                Mail::to($user)->send(new ForgatePasswordMail($user));
                return \response()->json(['message' => 'Verify email resend done...'], 404);
            }else{
                return \response()->json(['message' => 'Email Address is not valid...'], 200);
            }
        }else{
            if ($user && $user != null){
                Mail::to($user)->send(new SendVerificationMail($user));
//            Notification::sendNow($user, new SendVerificationNotification($user));
                return \response()->json(['message' => 'Verify email resend done...'], 200);
            }else{
//                return back()->with('error', 'Email Address is not valid.');
                return \response()->json(['message' => 'Email Address is not valid...'], 404);

            }
        }

    }
    public function verifiedEmail(){
        $email = base64_decode(\Illuminate\Support\Facades\Request::input('email'));
        $user = User::where('email', $email)->first();

        if ($user && $user != null){
            $user->email_verified_at = now();
            $user->save();
            return redirect(env("FRONTEND_URL"))."?success=your-email-is-verified-now";
        }else{
            return \response()->json(['message' => 'Email Address is not valid...'], 404);
        }
    }

    public function checkForgotPassword(){
        $email = base64_decode(\request()->input("_token"));

        $user = User::where('email', $email)->first();

        if ($user && $user != null){
            return redirect(env('FRONTEND_URL')."/new-given-password?email=$email");
        }else{
            return \response()->json(['message' => 'Email Address is not valid...'], 404);
        }
    }

    public function saveNewChangedPassword(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'min:6|required_with:confirm_password|same:confirm_password',
        ]);
        $user = User::where('email', $request->input('email'))->first();
        $user->password = $request->input('password');
        $user->update();

        return response()->json('Password Change Successfully Done...', 200);
    }


    public function getSettings()
    {
        $setting = new BusinessSettingController();
        $settngs = [
            's1Title' => $setting->get_setting('s1Title'),
            's1Slogan' => $setting->get_setting('s1Slogan'),

            's2Title' => $setting->get_setting('s2Title'),
            's2Slogan' => $setting->get_setting('s2Slogan'),

            's3Title' => $setting->get_setting('s3Title'),
            's3Slogan' => $setting->get_setting('s3Slogan'),

            's4Title' => $setting->get_setting('s4Title'),
            's4Slogan' => $setting->get_setting('s4Slogan'),

            'mstitle' => $setting->get_setting('mstitle'),
            'msbody' => $setting->get_setting('msbody'),

            'contactUs' => $setting->get_setting('contactUs'),

            'faqpagetitle' => $setting->get_setting('faqpagetitle'),
            'faqpageslogan' => $setting->get_setting('faqpageslogan'),

            'footerText' => $setting->get_setting('footerText'),
        ];

        return response()->json($settngs);
    }

    public function navPages(){

        $setting = new BusinessSettingController();
        $ids = collect(json_decode($setting->get_setting('headerpages')));
        $pages = [];
        if(count($ids) > 0){
            $pages = Page::query()
                ->select('id', 'title', 'slug')
                ->whereIn('id', $ids)
                ->get();
        }
        return response()->json($pages);
    }
    public function footPages(){
        $setting = new BusinessSettingController();
        $ids = collect(json_decode($setting->get_setting('footerpages')));
        $pages = [];
        if(count($ids) > 0){
            $pages = Page::query()
                ->select('id', 'title', 'slug')
                ->whereIn('id', $ids)
                ->get();
        }

        return response()->json($pages);
    }

    public function singlePage(string $slug)
    {
        return Page::query()->whereSlug($slug)->first();
    }

    public function reviews()
    {
        return Review::query()->select(['id', 'name', 'review'])->get();
    }

}
