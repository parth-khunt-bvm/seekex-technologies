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
}
