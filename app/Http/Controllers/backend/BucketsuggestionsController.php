<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Config;
use App\Models\Bucketsuggestions;
use App\Models\Balls;
use App\Models\Buckets;

class BucketsuggestionsController extends Controller
{
    function __construct(){
        $this->middleware('admin');
    }

    public function list(Request $request){

        $data['title'] = Config::get('constants.PROJECT_NAME') . ' || Bucket Suggestions';
        $data['description'] = Config::get('constants.PROJECT_NAME') . ' || Bucket Suggestions';
        $data['keywords'] = Config::get('constants.PROJECT_NAME') . ' || Bucket Suggestions';
        $data['css'] = array(
            'toastr/toastr.min.css'
        );
        $data['plugincss'] = array(
            'plugins/custom/datatables/datatables.bundle.css'
        );
        $data['pluginjs'] = array(
            'toastr/toastr.min.js',
            'plugins/custom/datatables/datatables.bundle.js',
            'pages/crud/datatables/data-sources/html.js'
        );
        $data['js'] = array(
            'comman_function.js',
            'bucketsuggestions.js',
        );
        $data['funinit'] = array(
            'Bucketsuggestions.init()'
        );
        $data['header'] = array(
            'title' => 'Bucket Suggestions',
            'breadcrumb' => array(
                'Dashboard' => route('my-dashboard'),
                'Bucket Suggestions' => 'Bucket Suggestions',
            )
        );
        return view('backend.pages.bucketsuggestions.list', $data);
    }

    public function add (){
        $objBuckets = new Buckets();
        $data['bucket_list'] = $objBuckets->get_bucket_list();

        $objBalls = new Balls();
        $data['ball_list'] = $objBalls->get_ball_list();

        $data['title'] = Config::get( 'constants.PROJECT_NAME' ) . " || Add Bucket Suggestions";
        $data['description'] = Config::get( 'constants.PROJECT_NAME' ) . " || Add Bucket Suggestions";
        $data['keywords'] = Config::get( 'constants.PROJECT_NAME' ) . " || Add Bucket Suggestions";
        $data['css'] = array(
            'toastr/toastr.min.css'
        );
        $data['plugincss'] = array(
        );
        $data['pluginjs'] = array(
            'toastr/toastr.min.js',
            'pages/crud/forms/widgets/select2.js',
            'validate/jquery.validate.min.js',
        );
        $data['js'] = array(
            'comman_function.js',
            'ajaxfileupload.js',
            'jquery.form.min.js',
            'bucketsuggestions.js',
        );
        $data['funinit'] = array(
            'Bucketsuggestions.add()'
        );
        $data['header'] = array(
            'title' => 'Add Bucket Suggestions',
            'breadcrumb' => array(
                'My Dashboard' => route('my-dashboard'),
                'Bucket Suggestions List' => route('bucket-suggestions.list'),
                'Add Bucket Suggestions' => 'Add Bucket Suggestions',
            )
        );
        return view('backend.pages.bucketsuggestions.add', $data);
    }

    public function saveAdd(Request $request){
        $objBucketsuggestions = new Bucketsuggestions();
        $result = $objBucketsuggestions->saveAdd($request);
        if ($result == "added") {
            $return['status'] = 'success';
             $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Bucket suggestions details successfully added.';
            $return['redirect'] = route('bucket-suggestions.list');
        } elseif ($result == "bucket_not_available") {
            $return['status'] = 'warning';
            $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Please select bucket and Ball.';
        }  else{
            $return['status'] = 'error';
            $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Something goes to wrong';
        }
        echo json_encode($return);
        exit;
    }

    public function ajaxcall(Request $request){
        $action = $request->input('action');
        switch ($action) {
            case 'getdatatable':
                $objBucketsuggestions = new Bucketsuggestions();
                $list = $objBucketsuggestions->getdatatable();

                echo json_encode($list);
                break;
        }
    }
}
