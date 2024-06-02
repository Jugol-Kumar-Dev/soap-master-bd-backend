<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Properties;
use App\Models\Course;
use App\Models\Order;
use App\Models\Transaction;
use Http\Discovery\Exception\NotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Stripe\StripeClient;

class StripeController extends Controller
{

    protected $sessionId;

    public function index()
    {
        $client = new StripeClient(config('stripe.stripe_sk'));
        return $client->paymentIntents->all();

        return "redirect index page";
    }

    public function checkout(Request $request)
    {
        $course = Course::query()->select(['name', 'description', 'price'])->findOrFail($request->input('courseId'))->toArray();

        Stripe::setApiKey(config('stripe.stripe_sk'));
        try {
            $session = Session::create([
                'line_items' =>[
                    [
                        'price_data' => [
                            'currency' => 'AUD',
                            'product_data' => [
                                'name' => $course['name'],
                                'description' => $course['description']
                            ],
                            'unit_amount' => intval($course["price"]) * 100,
                        ],
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'payment',
                'success_url' => config('app.frontend_url')."/success?session_url={CHECKOUT_SESSION_ID}",
                'cancel_url' => config('app.frontend_url')
            ]);

            $this->sessionId = $session->id;
            $this->saveOrder($request);
            return $session->url;
        } catch (ApiErrorException $e) {
            return $e->getMessage();
        }
    }

    public function verify(Request $request)
    {
        $sessionId = $request->input('sessionId');

        $stripe = new StripeClient(config('stripe.stripe_sk'));

        if($sessionId){
            try {
                $session = $stripe->checkout->sessions->retrieve($sessionId);

                if($session){
                    $order = Order::where('session_id', $session->id)->where('status', 'pending')->first();
                    $trx = Transaction::where('session_id', $session->id)->where('status', 'pending')->first();
                    if($order && $order != null){
                        $order->status = 'active';
                        $order->save();
                    }
                    if ($trx && $trx != null){
                        $trx->status = 'successfull';
                        $trx->save();
                    }

                    return $session;
                }else{
                    throw new NotFoundException();
                }

            } catch (ApiErrorException $e) {
                throw new NotFoundException();
            }
        }
        return response()->json('Your Session Id Is Not Valid...');
    }


    public function cancel()
    {
        return "this is cancel url";
    }


    private function saveOrder(Request $request)
    {


        $course = Course::query()->findOrFail($request->input('courseId'));


        $rand_id = rand(73862, 5632625);
        $order = new Order;
        $order->course_id = $course->id;
        $order->user_id = $request->input('is_assinged') ? $request->input('student_id') : $request->input('userId') ?? Auth::user()?->id;
        $order->transaction_id = $rand_id;
        $order->payment_method = "stripe";
        $order->total_amount = $course->price;
        $order->coupon_discount = $request->input('discount_amount') ??  0.00 ;
        $order->pay_amount = 0.00;
        $order->currency = config('app.currency');
        $order->status = 'pending';
        $order->is_show = $request->filled('is_show');

        $order->duration = $request->access_time;
        $order->duration_type = $request->access_type;

        $order->enroll_start = date(Properties::$enrollDateFormat, strtotime($request->enroll_start));
        $order->enroll_expire = date(Properties::$enrollDateFormat, strtotime($request->enroll_end));

        $order->session_id = $this->sessionId;

        $order->save();

        $payment = new Transaction;
        $payment->course_id = $course->id;
        $payment->trx = $rand_id;
        $payment->user_id = $request->userId; // Auth::user()->id;
        $payment->amount = $course->price;
        $payment->method = 'stripe';
        $payment->status = 'unpaid';
        $payment->save();

//        if ($request->coupon_id != null){
//            Auth::user()->coupons()->attach($this->createArrayGroups($request));
//        }

    }

    private function createArrayGroups($request): array
    {
        $added = array();
        $user_id = Auth::id();

        $added[$user_id] = [
            "coupon_id" => $request->coupon_id,
            "using" => 1
        ];
        return $added;
    }
}
