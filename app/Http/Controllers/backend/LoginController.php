<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Config;
use Auth;
use Session;
use Hash;

class LoginController extends Controller
{
    public function login(){
        $data['title'] = Config::get( 'constants.PROJECT_NAME' ) . " || Admin Login";
        $data['description'] = Config::get( 'constants.PROJECT_NAME' ) . " || Admin Login";
        $data['keywords'] = Config::get( 'constants.PROJECT_NAME' ) . " || Admin Login";
        $data['css'] = array(
            'toastr/toastr.min.css'
        );
        $data['plugincss'] = array(
        );
        $data['pluginjs'] = array(
            'toastr/toastr.min.js',
            'validate/jquery.validate.min.js',
        );
        $data['js'] = array(
            'comman_function.js',
            'ajaxfileupload.js',
            'jquery.form.min.js',
            'login.js',
        );
        $data['funinit'] = array(
            'Login.init()'
        );

        return view('backend.pages.login', $data);
    }

    public function auth_admin_login(Request $request){
        if (Auth::guard('admin')->attempt(['email' => $request->input('email'), 'password' => $request->input('password'), 'is_deleted'=>'N', 'status'=>'A']) ) {
            $loginData = array(
                'first_name' => Auth::guard('admin')->user()->first_name,
                'last_name' => Auth::guard('admin')->user()->last_name,
                'email' => Auth::guard('admin')->user()->email,
                'userimage' => Auth::guard('admin')->user()->userimage,
                'usertype' => Auth::guard('admin')->user()->user_type,
                'id' => Auth::guard('admin')->user()->id
            );

            Session::push('logindata', $loginData);

            $return['status'] = 'success';
            $return['message'] = 'You have successfully logged in.';
            $return['redirect'] = route('my-dashboard');
        } else {
            $return['status'] = 'error';
            $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Invalid Login Id/Password';
        }
        return json_encode($return);
        exit();
    }

    public function adminLogout(Request $request) {
        $this->resetGuard();
        $request->session()->forget('logindata');
        return redirect()->route('admin-login');
    }

    public function resetGuard() {
        Auth::logout();
        Auth::guard('admin')->logout();
    }
}
