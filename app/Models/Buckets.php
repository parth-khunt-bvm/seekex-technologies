<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Audittrails;

class Buckets extends Model
{
    use HasFactory;
    protected $table= "buckets";

    public function getdatatable()
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'buckets.id',
            1 => 'buckets.buckets',
            2 => 'buckets.volume',
            3 => DB::raw('(CASE WHEN buckets.status = "A" THEN "Actived" ELSE "Deactived" END)'),

        );
        $query = Buckets::from('buckets')
            ->where("buckets.is_deleted", "=", "N");

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
            ->select( 'buckets.id', 'buckets.buckets', 'buckets.volume', 'buckets.status')
            ->get();

        $data = array();
        $i = 0;

        foreach ($resultArr as $row) {

            $actionhtml  = '';
            $actionhtml .= '<a href="' . route('bucket.edit', $row['id']) . '" class="btn btn-icon"><i class="fa fa-edit text-warning"> </i></a>';
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
            $nestedData[] = $row['buckets'];
            $nestedData[] = $row['volume'];
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

    public function saveAdd($requestData){
        $checkBucketName = Buckets::from('buckets')
                    ->where('buckets.buckets', $requestData['bucket'])
                    ->where('buckets.is_deleted', 'N')
                    ->count();

        if($checkBucketName == 0){
            $objBuckets = new Buckets();
            $objBuckets->buckets = $requestData['bucket'];
            $objBuckets->volume = $requestData['volume'];
            $objBuckets->status = $requestData['status'];
            $objBuckets->is_deleted = 'N';
            $objBuckets->created_at = date('Y-m-d H:i:s');
            $objBuckets->updated_at = date('Y-m-d H:i:s');
            if($objBuckets->save()){
                $objAudittrails = new Audittrails();
                $objAudittrails->add_audit("I", $requestData->all(), 'Buckets');
                return 'added';
            }else{
                return 'wrong';
            }
        }
        return 'bucket_name_exists';
    }

    public function get_buckets_details($bucketsId){
        return Buckets::from('buckets')
                ->where("buckets.id", $bucketsId)
                ->select('buckets.id', 'buckets.buckets', 'buckets.volume', 'buckets.status')
                ->first();
    }

    public function saveEdit($requestData){
        $checkBucketName = Buckets::from('buckets')
                    ->where('buckets.buckets', $requestData['bucket'])
                    ->where('buckets.is_deleted', 'N')
                    ->where('buckets.id', '!=', $requestData['buckets_id'])
                    ->count();

        if($checkBucketName == 0){
            $objBuckets = Buckets::find($requestData['buckets_id']);
            $objBuckets->buckets = $requestData['bucket'];
            $objBuckets->volume = $requestData['volume'];
            $objBuckets->status = $requestData['status'];
            $objBuckets->updated_at = date('Y-m-d H:i:s');
            if($objBuckets->save()){
                $objAudittrails = new Audittrails();
                $objAudittrails->add_audit("U", $requestData->all(), 'Buckets');
                return 'updated';
            }else{
                return 'wrong';
            }
        }
        return 'bucket_name_exists';
    }

    public function common_activity($requestData){

        $objBuckets = Buckets::find($requestData['id']);
        if($requestData['activity'] == 'delete-records'){
            $objBuckets->is_deleted = "Y";
            $event = 'D';
        }

        if($requestData['activity'] == 'active-records'){
            $objBuckets->status = "A";
            $event = 'A';
        }

        if($requestData['activity'] == 'deactive-records'){
            $objBuckets->status = "I";
            $event = 'DA';
        }

        $objBuckets->updated_at = date("Y-m-d H:i:s");
        if($objBuckets->save()){
            $objAudittrails = new Audittrails();
            $res = $objAudittrails->add_audit($event, $requestData, 'Buckets');
            return true;
        }else{
            return false ;
        }
    }
}
