<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User};
use OProjects\Scheduler2\Models\{Schedule};
use OProjects\Scheduler2\Scheduler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;
use Exception;
use OProjects\Twilio\Twilio;
use OProjects\Paymob\Paymob;
use Illuminate\Support\Facades\Session;


class UserController extends Controller
{
    use Twilio;
    use Scheduler;
    use Paymob;
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            Session::put('user_id', $user->id);
            if($user->type === 'Provider'){
                Session::put('type' , 'provider');
                return redirect()->intended('/provider');
            }
            else{
                Session::put('type' , 'seeker');
                return redirect()->intended('/seeker');
            }

        }
        else {
           return redirect('/')->with('error', 'Invalid login credentials');
       }
    }

    public function register(Request $request)
    {
        $phone = $request->mobile;
        if($phone[0] != '+'){
            return redirect('register')->with('error', 'Invalid phone number');
        }
        try{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'mobile' => $request->mobile,
                'type' => 'Seeker',
            ]);
            return redirect('/')->with('success', 'Registration successful');;
        }
        catch(Exception $e){
            return redirect('register')->with('error', 'Email already exists');
        }

    }

    public function forgotPassword(Request $request)
    {
        try{
            $receiverPhone = $request->mobile;
            $message = 'New Password is 1234';
            $user = User::where('mobile', $receiverPhone)->first();
            if ($user) {
                $this->sendSMS($receiverPhone, $message);
                $user->password = Hash::make('1234');
                $user->save();
                return redirect('/');
            } 
            else {
                return back()->with('error', 'Invalid phone number');
            }
        }
        catch(Exception $e){
            return back()->with('error', 'Error with text messaging service');
        }

    }
    public function redirectToFacebook()
    {
       return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
       try {
           $user = Socialite::driver('facebook')->stateless()->user();
           $finduser = User::where('email', $user->email)->first();
           if($finduser){
               //Auth::login($finduser);
               Session::put('type' , 'seeker');
               return redirect('/seeker');
           }else{
               $newUser = User::updateOrCreate(['email' => $user->email],[
                       'password' => Hash::make('password'), 
                   ]);
       
               //Auth::login($newUser);
               Session::put('type' , 'seeker');
               return redirect('/seeker');
           }
      
       } catch (Exception $e) {
           return redirect('/');
       }
    }

    public function redirectToGmail()
    {
       return Socialite::driver('google')->redirect();
    }

    public function handleGmailCallback()
    {
       try {
           $user = Socialite::driver('google')->stateless()->user();
           $finduser = User::where('email', $user->email)->first();
           if($finduser){
               //Auth::login($finduser);
               Session::put('type' , 'seeker');
               return redirect('/seeker');
           }else{
               $newUser = User::updateOrCreate(['email' => $user->email],[
                       'password' => Hash::make('password'), 
                   ]);
       
               //Auth::login($newUser);
               Session::put('type' , 'seeker');
               return redirect('/seeker');
           }
      
       } catch (Exception $e) {
           return redirect('/');
       }
    }

    public function redirectToApple()
    {
       return Socialite::driver('apple')->redirect();
    }

    public function handleAppleCallback()
    {
       try {
           $user = Socialite::driver('apple')->stateless()->user();
           $finduser = User::where('email', $user->email)->first();
           if($finduser){
               //Auth::login($finduser);
               Session::put('type' , 'seeker');
               return redirect('/seeker');
           }else{
               $newUser = User::updateOrCreate(['email' => $user->email],[
                       'password' => Hash::make('password'), 
                   ]);
       
               //Auth::login($newUser);
               Session::put('type' , 'seeker');
               return redirect('/seeker');
           }
      
       } catch (Exception $e) {
           return redirect('/');
       }
    }


    public function viewSchedule(){
        if(Session::get('type')!= 'provider'){
            return redirect('/');
        }
        $sessions = $this->index();
        return view('provider' , ['sessions'=>  $sessions ]);
    }
    public function viewUnreserved(){
        if(Session::get('type')!= 'seeker'){
            return redirect('/');
        }
        $sessions = $this->showAllUnreserved();
        return view('seeker' , ['sessions'=>  $sessions ]);
    }

    public function viewReserved(){
        if(Session::get('type')!= 'seeker'){
            return redirect('/');
        }
        $sessions = $this->indexUser(Session::get('user_id'));
        return view('reserved-sessions' , ['sessions'=>  $sessions ]);
    }

    public function createSession(Request $request){
        if(Session::get('type')!= 'provider'){
            return redirect('/');
        }
        $this->store($request->date, $request->from, $request->to, $request->price);
        if (session()->has('error')) {
            return redirect()->back();
        }
        if (session()->has('success')) {
            return redirect('/provider')->with('success', session('success'));
        }
    }

    public function removeSession($id){
        if(Session::get('type')!= 'provider'){
            return redirect('/');
        }
        $this->deleteSession($id);
        if (session()->has('success')) {
            return redirect('/provider')->with('success', session('success'));
        } 
        else{
            return redirect('/provider')->with('error', session('error'));
        }
            
    }


    public function book($id){
        if(Session::get('type')!= 'seeker'){
            return redirect('/');
        }
        $delivery_needed = 'false';
        $input = Schedule::where('id' , $id)->value('price');
        $number = floatval($input); 
        $newNumber = $number * 100;
        $amount_cents = number_format($newNumber, 2, '.', ''); 
        $billing_data = [
            "apartment" => "NA", 
            "email"=> User::where('id' , Session::get('user_id'))->value('email'), 
            "floor"=>"NA", 
            "first_name"=> User::where('id' , Session::get('user_id'))->value('name'), 
            "street"=> "NA", 
            "building"=> "NA", 
            "phone_number"=> User::where('id' , Session::get('user_id'))->value('mobile'), 
            "shipping_method"=> "NA", 
            "postal_code"=> "NA", 
            "city" =>"NA",
            "country"=> "NA", 
            "last_name"=> User::where('id' , Session::get('user_id'))->value('name'), 
            "state"=> "NA"
        ];
        $items = [];
        $paymentToken = $this->getPaymentToken($delivery_needed, $amount_cents,$billing_data , $items);
        Session::put('booking_id' , $id);
        return redirect("https://accept.paymob.com/api/acceptance/iframes/784309?payment_token={$paymentToken}");
    }

    public function handlePaymobCallback(Request $request){
        $paymentStatus = $request->input('success');
        if ($paymentStatus=='true'){
            $this->bookSession(Session::get('booking_id') , Session::get('user_id'));
            return redirect('/seeker')->with('success', 'Booking was successful');
        }
        else{
            return redirect('/seeker')->with('error', 'Booking was not successful');
        }

    }

    public function logout(){
        session(['type' => '']);
        return redirect('http://127.0.0.1:8000/');
    }
}