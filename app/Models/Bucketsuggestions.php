<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bucketsuggestionsbucket;
use App\Models\Bucketsuggestionsball;
use DB;
use App\Models\Audittrails;

class Bucketsuggestions extends Model
{
    use HasFactory;
    protected $table = 'bucketsuggestions';

    public function saveAdd($requestData){
        if(isset($requestData['bucket'])){
            $objBucketsuggestions = new Bucketsuggestions();
            $objBucketsuggestions->name = $requestData['bucketSuggestions'];
            $objBucketsuggestions->created_at = date('Y-m-d H:i:s');
            $objBucketsuggestions->updated_at = date('Y-m-d H:i:s');
            if($objBucketsuggestions->save()){
                $objBucketsuggestionsId = $objBucketsuggestions->id;

                foreach($requestData['bucket'] as $key => $value){
                    $objBucketsuggestionsbucket = new Bucketsuggestionsbucket();
                    $objBucketsuggestionsbucket->bucketsuggestionsId = $objBucketsuggestionsId;
                    $objBucketsuggestionsbucket->bucket = $value;
                    $objBucketsuggestionsbucket->volume = $requestData['bucket_volume'][$key];
                    $objBucketsuggestionsbucket->created_at = date('Y-m-d H:i:s');
                    $objBucketsuggestionsbucket->updated_at = date('Y-m-d H:i:s');
                    $objBucketsuggestionsbucket->save();
                }

                foreach($requestData['ball'] as $key => $value){
                    $objBucketsuggestionsball = new Bucketsuggestionsball();
                    $objBucketsuggestionsball->bucketsuggestionsId = $objBucketsuggestionsId;
                    $objBucketsuggestionsball->balls = $value;
                    $objBucketsuggestionsball->volume = $requestData['ball_volume'][$key];
                    $objBucketsuggestionsball->created_at = date('Y-m-d H:i:s');
                    $objBucketsuggestionsball->updated_at = date('Y-m-d H:i:s');
                    $objBucketsuggestionsball->save();
                }
                return 'added';
            }
            return 'wrong';
        }
        return 'bucket_not_available';
    }

    public function getdatatable()
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'bucketsuggestions.id',
            1 => 'bucketsuggestions.name',
            // 2 => DB::raw("group_concat(bucketsuggestionsball.volume) as bucket"),
            // 3 => DB::raw("group_concat(bucketsuggestionsbucket.bucket) as bucket")
        );
        $query = Bucketsuggestions::from('bucketsuggestions')
                        ->join('bucketsuggestionsball', 'bucketsuggestionsball.bucketsuggestionsId', '=', 'bucketsuggestions.id')
                        ->join('bucketsuggestionsbucket', 'bucketsuggestionsbucket.bucketsuggestionsId', '=', 'bucketsuggestions.id')
                        ->groupBy('bucketsuggestions.id');

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
            ->select('bucketsuggestions.id', 'bucketsuggestions.name', DB::raw('group_concat(bucketsuggestionsbucket.bucket) as bucket'))
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
            $nestedData[] = $row['name'];
            $nestedData[] = $row['bucket'];
            $nestedData[] = $row['bucket'];
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
}
