<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Inertia\Inertia;
use App\Mail\OTPMail;
use App\Models\Invoice;
use App\Models\Product;
use App\Helper\JWTToken;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;



class UserController extends Controller
{
    public function loginPage(Request $request){
        return Inertia::render('LoginPage');
    }

    public function registrationPage(Request $request){
        return Inertia::render('RegistrationPage');
    }

    public function sendOTPPage(Request $request){
        return Inertia::render('SendOTPPage');
    }

    public function verifyOTPPage(Request $request){
        return Inertia::render('VerifyOTPPage');
    }

    public function resetPasswordPage(Request $request){
        return Inertia::render('ResetPasswordPage');
    }

    public function userRegistration(Request $request){
        try{
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'mobile' => 'required',
            ]);

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'mobile' => $request->input('mobile'),
            ]);


            $data = ['message'=>'User created successfully','status'=>true,'error'=>''];
            return redirect('/login')->with($data);
        }catch(Exception $e){

            $data = ['message'=>'Something went wrong','status'=>false,'error'=>''];
            return redirect('/registration')->with($data);
        }
    }

    public function userLogin(Request $request){
        //dd($request->all());
        $count = User::where('email', $request->input('email'))->where('password', $request->input('password'))->select('id')->first();

        if($count !== null){

            $email = $request->input('email');
            $user_id = $count->id;

            $request->session()->put('email', $email);
            $request->session()->put('user_id', $user_id);

            $data = ['message'=>'User login successfully','status'=>true,'error'=>''];
            return redirect('/dashboard')->with($data);
            //return redirect()->route('dashboard')->with('success','Login Success');
        }else{

            $data = ['message'=>'Login faild','status'=>false,'error'=>''];
            return redirect()->back()->with($data);
            //return redirect()->back()->with('error','Login Failed');
        }

    }


    public function dashboardPage(Request $request){
        $user_id = request()->header('id');

        $product = Product::where('user_id', $user_id)->count();
        $category = Category::where('user_id', $user_id)->count();
        $customer = Customer::where('user_id', $user_id)->count();
        $invoice = Invoice::where('user_id', $user_id)->count();
        $total = Invoice::where('user_id', $user_id)->sum('total');
        $vat = Invoice::where('user_id', $user_id)->sum('vat');
        $payable = Invoice::where('user_id', $user_id)->sum('payable');
        $discount = Invoice::where('user_id', $user_id)->sum('discount');

        $data = [
            'product' => $product,
            'category' => $category,
            'customer' => $customer,
            'invoice' => $invoice,
            'total' => round($total),
            'vat' => round($vat),
            'payable' => round($payable),
            'discount' => $discount
        ];

        return Inertia::render('DashboardPage',['list'=>$data]);
    }

    public function userLogout(Request $request){

        $request->session()->forget('email');
        $request->session()->forget('user_id');

        //$data = ['message'=>'User logout successfully','status'=>true,'error'=>''];
        return redirect()->route('login')->with('success','User logout successfully');
        //return redirect('/login')->with($data);
    }

    public function sendOTPCode(Request $request){
        $email = $request->input('email');
        $otp = rand(1000,9999);

        $count = User::where('email',$email)->count();

        if($count == 1){
            // Mail::to($email)->send(new OTPMail($otp));
            User::where('email', $email)->update(['otp' => $otp]);
            $request->session()->put('email', $email);

            $data = ["message"=>"4 Digit {$otp} OTP send successfully","status"=>true,"error"=>''];
            return redirect('/verify-otp')->with($data);
        }else{

            $data = ['message'=>'unauthorized','status'=>false,'error'=>''];
            return redirect('/registration')->with($data);
        }
    }//end method

    public function verifyOTP(Request $request){
        $email = $request->session()->get('email');
        $otp = $request->input('otp');

        $count = User::where('email', $email)->where('otp', $otp)->count();

        if($count == 1){
            User::where('email', $email)->update(['otp' => 0]);



            $request->session()->put('otp_verify','yes');


            $data = ["message"=>"OTP verification successfully","status"=>true,"error"=>''];
            return redirect('/reset-password')->with($data);
        }else{

            $data = ['message'=> 'unauthorized','status'=>false, 'error'=>''];
            return redirect('/login')->with($data);
        }
    }

    public function resetPassword(Request $request){
        try{

            $email = $request->session()->get('email','default');
            $password = $request->input('password');

            $otp_verify = $request->session()->get('otp_verify','default');
            if($otp_verify === 'yes'){
                User::where('email', $email)->update(['password' => $password]);
                $request->session()->flush();

                $data = ['message'=> 'Password reset successfully','status'=>true, 'error'=>'' ];
                return redirect('/login')->with($data);
            }else{
                $data = ['message'=> 'Request fail','status'=>false, 'error'=>'' ];
                return redirect('/reset-password')->with($data);
            }


        }catch(Exception $e){
            $data = ['message'=> $e->getMessage(),'status'=>false, 'error'=>'' ];
            return redirect('/reset-password')->with($data);
        }
    }

    public function profilePage(Request $request){
        $email = request()->header('email');
        $user = User::where('email', $email)->first();
        return Inertia::render('ProfilePage',['user'=>$user]);
    }

    public function userUpdate(Request $request){
        $email = request()->header('email');
        User::where('email', $email)->update([
            'name' => $request->input('name'),
            'email'=> $request->input('email'),
            'mobile'=> $request->input('mobile'),
        ]);
        $data = ['message'=> 'Profile update successfully','status'=>true, 'error'=>'' ];
        return redirect()->back()->with($data);
    }
}
