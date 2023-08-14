<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Audittrails;

class Balls extends Model
{
    use HasFactory;
    protected $table= "balls";

    public function getdatatable()
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'balls.id',
            1 => 'balls.balls',
            2 => 'balls.volume',
            3 => DB::raw('(CASE WHEN balls.status = "A" THEN "Actived" ELSE "Deactived" END)'),

        );
        $query = Balls::from('balls')
            ->where("balls.is_deleted", "=", "N");

        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $searchVal = $requestData['search']['value'];
            $query->where(function ($query) use ($columns, $searchVal, $requestData) {
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
            ->select( 'balls.id', 'balls.balls', 'balls.volume', 'balls.status')
            ->get();

        $data = array();
        $i = 0;

        foreach ($resultArr as $row) {

            $actionhtml  = '';
            $actionhtml .= '<a href="' . route('ball.edit', $row['id']) . '" class="btn btn-icon"><i class="fa fa-edit text-warning"> </i></a>';
            if ($row['status'] == 'A') {
                $status = '<span class="label label-lg label-light-success label-inline">Active</span>';
                $actionhtml .= '<a href="#" data-toggle="modal" data-target="#deactiveModel" class="btn btn-icon  deactive-records" data-id="' . $row["id"] . '" ><i class="fa fa-times text-primary" ></i></a>';
            } else {
                $status = '<span class="label label-lg label-light-danger  label-inline">Deactive</span>';
                $actionhtml .= '<a href="#" data-toggle="modal" data-target="#activeModel" class="btn btn-icon  active-records" data-id="' . $row["id"] . '" ><i class="fa fa-check text-primary" ></i></a>';
            }
            $actionhtml .= '<a href="#" data-toggle="modal" data-target="#deleteModel" class="btn btn-icon  delete-records" data-id="' . $row["id"] . '" ><i class="fa fa-trash text-danger" ></i></a>';

            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $row['balls'];
            $nestedData[] = numberformat($row['volume']);
            $nestedData[] = $status;
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

    public function common_activity($requestData){

        $objBalls = Balls::find($requestData['id']);
        if($requestData['activity'] == 'delete-records'){
            $objBalls->is_deleted = "Y";
            $event = 'D';
        }

        if($requestData['activity'] == 'active-records'){
            $objBalls->status = "A";
            $event = 'A';
        }

        if($requestData['activity'] == 'deactive-records'){
            $objBalls->status = "I";
            $event = 'DA';
        }

        $objBalls->updated_at = date("Y-m-d H:i:s");
        if($objBalls->save()){
            $objAudittrails = new Audittrails();
            $res = $objAudittrails->add_audit($event, $requestData, 'Balls');
            return true;
        }else{
            return false ;
        }
    }

    public function saveAdd($requestData){
        $checkBallName = Balls::from('balls')
                    ->where('balls.balls', $requestData['ball'])
                    ->where('balls.is_deleted', 'N')
                    ->count();

        if($checkBallName == 0){
            $objBalls = new Balls();
            $objBalls->balls = $requestData['ball'];
            $objBalls->volume = $requestData['volume'];
            $objBalls->status = $requestData['status'];
            $objBalls->is_deleted = 'N';
            $objBalls->created_at = date('Y-m-d H:i:s');
            $objBalls->updated_at = date('Y-m-d H:i:s');
            if($objBalls->save()){
                $objAudittrails = new Audittrails();
                $objAudittrails->add_audit("I", $requestData->all(), 'Balls');
                return 'added';
            }else{
                return 'wrong';
            }
        }
        return 'ball_name_exists';
    }

    public function get_balls_details($ballId){
        return Balls::from('balls')
                ->where("balls.id", $ballId)
                ->select('balls.id', 'balls.balls', 'balls.volume', 'balls.status')
                ->first();
    }

    public function saveEdit($requestData){
        $checkBallName = Balls::from('balls')
                    ->where('balls.balls', $requestData['ball'])
                    ->where('balls.is_deleted', 'N')
                    ->where('balls.id', '!=', $requestData['ballId'])
                    ->count();

        if($checkBallName == 0){
            $objBalls = Balls::find($requestData['ballId']);
            $objBalls->balls = $requestData['ball'];
            $objBalls->volume = $requestData['volume'];
            $objBalls->status = $requestData['status'];
            $objBalls->updated_at = date('Y-m-d H:i:s');
            if($objBalls->save()){
                $objAudittrails = new Audittrails();
                $objAudittrails->add_audit("U", $requestData->all(), 'Balls');
                return 'updated';
            }else{
                return 'wrong';
            }
        }
        return 'ball_name_exists';
    }

    public function get_ball_list(){
        return Buckets::from('balls')
                ->select('balls.id', 'balls.balls', 'balls.volume')
                ->where('balls.status', 'A')
                ->where('balls.is_deleted', 'N')
                ->orderBy('balls.volume', 'desc')
                ->get()
                ->toArray();
    }
}
