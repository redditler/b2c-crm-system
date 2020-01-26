<?php

namespace App\Http\Controllers;

use App\PasswordSecurity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use PragmaRX\Google2FA\Google2FA;

class PasswordSecurityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \PragmaRX\Google2FA\Exceptions\InsecureCallException
     */
    public function show2faForm(Request $request){
        $user = Auth::user();
        $google2fa_url = "";
        $google2fa_secret = '';
        //dd($user);
        if($user->passwordSecurity()->exists()){
            $google2fa = new Google2FA();
            $google2fa->setAllowInsecureCallToGoogleApis(true);
            $google2fa_secret = $user->passwordSecurity->google2fa_secret;
            $google2fa_url = $google2fa->getQRCodeGoogleUrl(
                'dev.leed.steko.com.ua',
                $user->email,
                $google2fa_secret
            );
        }
        $data = [
            'user' => $user,
            'google2fa_url' => $google2fa_url,
            'google2fa_secret' => $google2fa_secret
        ];
        return view('auth.2fa', ['data' => $data]);
    }

    public function generate2faSecret(Request $request){
        $user = Auth::user();
        // Initialise the 2FA class
        $google2fa = new Google2FA();



        // Add the secret key to the registration data
        PasswordSecurity::create([
            'user_id' => $user->id,
            'google2fa_enable' => 0,
            'google2fa_secret' => $google2fa->generateSecretKey(),
        ]);

        return redirect('/2fa')->with('success',"Secret Key is generated, Please verify Code to Enable 2FA");
    }


    public function enable2fa(Request $request){
        $user = Auth::user();
        $google2fa = new Google2FA();
        $secret = $request->input('verify-code');
        $valid = $google2fa->verifyKey($user->passwordSecurity->google2fa_secret, $secret);
        if($valid){
            $user->passwordSecurity->google2fa_enable = 1;
            $user->passwordSecurity->save();
            return redirect('2fa')->with('success',"2FA is Enabled Successfully.");
        }else{
            return redirect('2fa')->with('error',"Invalid Verification Code, Please try again.");
        }
    }

    public function disable2fa(Request $request){

        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your  password does not matches with your account password. Please try again.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
        ]);
        $user = Auth::user();
        $user->passwordSecurity->google2fa_enable = 0;
        $user->passwordSecurity->save();
        return redirect('/2fa')->with('success',"2FA is now Disabled.");
    }

    public function delete2fa($id)
    {
        if (Auth::user()->role_id != 1){
            return redirect('/');
        }
        $passwordSecurity = PasswordSecurity::where('user_id', $id)->delete();
        return redirect()->back();

    }

}































