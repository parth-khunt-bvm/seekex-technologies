<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Audittrails;
use Config;

class AuditTrailsController extends Controller
{
    function __construct()
    {
            $this->middleware('admin');
    }

    public function list(Request $request){

        $data['title'] = Config::get('constants.PROJECT_NAME') . ' || Audit Trails List';
        $data['description'] = Config::get('constants.PROJECT_NAME') . ' || Audit Trails List';
        $data['keywords'] = Config::get('constants.PROJECT_NAME') . ' || Audit Trails List';
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
            'audittrails.js',
        );
        $data['funinit'] = array(
            'Audittrails.init()'
        );
        $data['header'] = array(
            'title' => 'Audit Trails List',
            'breadcrumb' => array(
                'Dashboard' => route('my-dashboard'),
                'Audit Trails List' => 'Audit Trails List',
            )
        );
        return view('backend.pages.audittrails.list', $data);


    }

    public function ajaxcall(Request $request){

        $action = $request->input('action');
        switch ($action) {
            case 'getdatatable':
                $objAudittrails = new Audittrails();
                $list = $objAudittrails->getdatatable();

                echo json_encode($list);
                break;

            case 'viewdata' :
                $objAudittrails = new Audittrails();
                $data = $objAudittrails->get_view_data($request->input('data'));

                echo $data[0]->data;
                break;
        }
    }
}
