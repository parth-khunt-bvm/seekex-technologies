<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Balls;
use Config;

class BallController extends Controller
{
    function __construct()
    {
            $this->middleware('admin');
    }

    public function list(Request $request){

        $data['title'] = Config::get('constants.PROJECT_NAME') . ' || Ball List';
        $data['description'] = Config::get('constants.PROJECT_NAME') . ' || Ball List';
        $data['keywords'] = Config::get('constants.PROJECT_NAME') . ' || Ball List';
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
            'ball.js',
        );
        $data['funinit'] = array(
            'Ball.init()'
        );
        $data['header'] = array(
            'title' => 'Ball List',
            'breadcrumb' => array(
                'Dashboard' => route('my-dashboard'),
                'Ball List' => 'Ball List',
            )
        );
        return view('backend.pages.ball.list', $data);
    }

    public function add (){
        $data['title'] = Config::get( 'constants.PROJECT_NAME' ) . " || Add Ball";
        $data['description'] = Config::get( 'constants.PROJECT_NAME' ) . " || Add Ball";
        $data['keywords'] = Config::get( 'constants.PROJECT_NAME' ) . " || Add Ball";
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
            'ball.js',
        );
        $data['funinit'] = array(
            'Ball.add()'
        );
        $data['header'] = array(
            'title' => 'Add Ball',
            'breadcrumb' => array(
                'My Dashboard' => route('my-dashboard'),
                'Ball List' => route('ball.list'),
                'Add Ball' => 'Add Ball',
            )
        );
        return view('backend.pages.ball.add', $data);
    }

    public function saveAdd(Request $request){
        $objBalls = new Balls();
        $result = $objBalls->saveAdd($request);
        if ($result == "added") {
            $return['status'] = 'success';
             $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Ball details successfully added.';
            $return['redirect'] = route('ball.list');
        } elseif ($result == "ball_name_exists") {
            $return['status'] = 'warning';
            $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Ball has already exists.';
        }  else{
            $return['status'] = 'error';
            $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Something goes to wrong';
        }
        echo json_encode($return);
        exit;
    }

    public function edit ($bucketsId){

        $objBalls = new Balls();
        $data['buckets_details'] = $objBalls->get_balls_details($bucketsId);
        $data['title'] = Config::get( 'constants.PROJECT_NAME' ) . " || Edit Ball";
        $data['description'] = Config::get( 'constants.PROJECT_NAME' ) . " || Edit Ball";
        $data['keywords'] = Config::get( 'constants.PROJECT_NAME' ) . " || Edit Ball";
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
            'ball.js',
        );
        $data['funinit'] = array(
            'Ball.edit()'
        );
        $data['header'] = array(
            'title' => 'Edit Ball',
            'breadcrumb' => array(
                'My Dashboard' => route('my-dashboard'),
                'Ball List' => route('ball.list'),
                'Edit Ball' => 'Edit Ball',
            )
        );
        return view('backend.pages.ball.edit', $data);
    }

    public function saveEdit(Request $request){
        $objBalls = new Balls();
        $result = $objBalls->saveEdit($request);
        if ($result == "updated") {
            $return['status'] = 'success';
             $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Ball details successfully updated.';
            $return['redirect'] = route('ball.list');
        } elseif ($result == "ball_name_exists") {
            $return['status'] = 'warning';
            $return['jscode'] = '$(".submitbtn:visible").removeAttr("disabled");$("#loader").hide();';
            $return['message'] = 'Ball has already exists.';
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
                $objBalls = new Balls();
                $list = $objBalls->getdatatable();

                echo json_encode($list);
                break;

            case 'common-activity':
                $data = $request->input('data');
                $objBalls = new Balls();
                $result = $objBalls->common_activity($data);
                if ($result) {
                    $return['status'] = 'success';
                    if($data['activity'] == 'delete-records'){
                        $return['message'] = 'Ball details successfully deleted.';;
                    }elseif($data['activity'] == 'active-records'){
                        $return['message'] = 'Ball details successfully actived.';;
                    }else{
                        $return['message'] = 'Ball details successfully deactived.';;
                    }
                    $return['redirect'] = route('ball.list');
                } else {
                    $return['status'] = 'error';
                    $return['jscode'] = '$("#loader").hide();';
                    $return['message'] = 'It seems like something is wrong';;
                }

                echo json_encode($return);
                exit;
        }
    }
}
