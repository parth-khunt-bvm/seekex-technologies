<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Agent\Agent;
use Session;
use Route;
use DB;

class Audittrails extends Model
{
    use HasFactory;
    protected $table = 'audit_trails';

    public function add_audit($event , $data, $module){
        $agent = new Agent();
        $browser = $agent->browser();
        $currentRoute = Route::current()->getName();
        if($event == 'U'){
            $activity = 'Update';
        }elseif($event == 'I'){
            $activity = 'Insert';
        }elseif($event == 'A'){
            $activity = 'Active Records';
        }elseif($event == 'DA'){
            $activity = 'Deactive Records';
        }elseif($event == 'D'){
            $activity = 'Delete Records';
        }else{
            $activity = 'Unknow Activity';
        }
        unset($data['_token']);
        $loginUser = Session::all();
        $objAudittrails = new Audittrails();
        $objAudittrails->user_id = $loginUser['logindata'][0]['id'];
        $objAudittrails->event = $activity;
        $objAudittrails->module = $module;
        $objAudittrails->data = json_encode($data);
        $objAudittrails->url = str_replace(".", "/", $currentRoute);
        $objAudittrails->ip = $_SERVER['REMOTE_ADDR'];
        $objAudittrails->agent = $browser;
        $objAudittrails->created_at = date("Y-m-d H:i:s");
        $objAudittrails->updated_at = date("Y-m-d H:i:s");
        return $objAudittrails->save();
    }

    // public function add_audit($evet , $url , $data,$module){

    //     $agent = new Agent();
    //     $browser = $agent->browser();

    //     $loginUser = Session::all();
    //     $objAudittrails = new Audittrails();
    //     $objAudittrails->user_id = $loginUser['logindata'][0]['id'];
    //     $objAudittrails->event = $evet;
    //     $objAudittrails->module = $module;
    //     $objAudittrails->data = $data;
    //     $objAudittrails->url = $url;
    //     $objAudittrails->ip = $_SERVER['REMOTE_ADDR'];
    //     $objAudittrails->agent = $browser;
    //     $objAudittrails->created_at = date("Y-m-d H:i:s");
    //     $objAudittrails->updated_at = date("Y-m-d H:i:s");
    //     return $objAudittrails->save();
    // }

    public function getdatatable()
    {
        // ccd($employee_list);
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'audit_trails.id',
            1 => 'users.first_name',
            2 => 'users.last_name',
            3 => 'audit_trails.event',
            4 => 'audit_trails.module',
            5 => 'audit_trails.url',
            6 => 'audit_trails.ip',
            7 => 'audit_trails.agent',
            8 => 'audit_trails.created_at',

        );
        $query = Audittrails ::from('audit_trails')
                            ->join("users","users.id","=","audit_trails.user_id");

        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $searchVal = $requestData['search']['value'];
            $query->where(function($query) use ($columns, $searchVal, $requestData) {
                $flag = 0;
                foreach ($columns as $key => $value) {
                    $searchVal = $requestData['search']['value'];
                    if ($requestData['columns'][$key]['searchable'] == 'true') {
                        if ($flag == 0) {
                            $query->where($value, 'like', '%' . $searchVal . '%');
                            $flag = $flag + 1;
                        } else {
                            $query->orWhere($value, 'like', '%' . $searchVal . '%');
                        }
                    }
                }
            });
        }

        $temp = $query->orderBy($columns[$requestData['order'][0]['column']], $requestData['order'][0]['dir']);

        $totalData = count($temp->get());
        $totalFiltered = count($temp->get());

        $resultArr = $query->skip($requestData['start'])
                    ->take($requestData['length'])
                    ->select('users.first_name', 'users.last_name', 'audit_trails.id', 'audit_trails.event', 'audit_trails.module', 'audit_trails.data', 'audit_trails.url', 'audit_trails.ip', 'audit_trails.agent', 'audit_trails.created_at')
                    ->get();

        $data = array();
        $i = 0;

        foreach ($resultArr as $row) {

            $actionhtmlagent = '<span class="btn btn-icon"><i class="fa fa-'.strtolower($row['agent']).' text-success" ></i></span>';
            $actionhtml  = '';
            $actionhtml =  $actionhtml. '<a href="javscript:;" data-toggle="modal" data-target="#viewAuditTrails" data-id="'.$row['id'].'" class="btn btn-icon viewdata"><i class="fa fa-eye text-info"> </i></a>';

            // $actionhtml = '<a href="javscript:;" data-toggle="modal" data-target="#viewAuditTrails" data-id="'.$row['id'].'" class="btn btn-icon viewdata"><i class="fa fa-eye text-info"> </i></a>';

            //
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            // $nestedData[] = $row['id'];
            $nestedData[] = $row['first_name']." ". $row['last_name'];
            $nestedData[] = $row['event'];
            $nestedData[] = $row['module'];
            $nestedData[] = $row['url'];
            $nestedData[] = $row['ip'];
            $nestedData[] = $actionhtmlagent;
            $nestedData[] = date("d-M-Y h:i:s",strtotime($row['created_at']));
            $nestedData[] = $actionhtml;
            $data[] = $nestedData;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );
        return $json_data;
    }


    public function get_view_data($data){
        return Audittrails::select('data')->where('id',$data['id'])->get();
    }
}
