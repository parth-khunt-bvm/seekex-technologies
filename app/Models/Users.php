<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Audittrails;
use DB;
use Hash;
use Route;
use Session;

class Users extends Model
{
    use HasFactory;
    protected $table= "users";

    public function saveProfile($request){
        $countUser = Users::where("email",$request->input('email'))
                        ->where("id",'!=',$request->input('edit_id'))
                        ->count();

        if($countUser == 0){

            $objUsers = Users::find($request->input('edit_id'));
            $objUsers->first_name = $request->input('first_name');
            $objUsers->last_name = $request->input('last_name');
            $objUsers->email = $request->input('email');
            if($request->file('userimage')){
                $image = $request->file('userimage');
                $imagename = 'userimage'.time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/upload/userprofile/');
                $image->move($destinationPath, $imagename);
                $objUsers->userimage  = $imagename ;
            }
            if($objUsers->save()){
                $currentRoute = Route::current()->getName();
                $inputData = $request->input();
                unset($inputData['_token']);
                unset($inputData['profile_avatar_remove']);
                unset($inputData['userimage']);
                if($request->file('userimage')){
                    $inputData['userimage'] = $imagename;
                }
                $objAudittrails = new Audittrails();
                $res = $objAudittrails->add_audit('Update','admin/'. $currentRoute , json_encode($inputData) ,'Update Profile' );
                return true;
            }else{
                return "false";
            }

        }else{
            return "email_exist";
        }
    }

    public function changepassword($request)
    {

        if (Hash::check($request->input('old_password'), $request->input('user_old_password'))) {
            $countUser = Users::where("id",'=',$request->input('editid'))->count();
            if($countUser == 1){
                $objUsers = Users::find($request->input('editid'));
                $objUsers->password =  Hash::make($request->input('new_password'));
                $objUsers->updated_at = date('Y-m-d H:i:s');
                if($objUsers->save()){
                    $currentRoute = Route::current()->getName();
                    $inputData = $request->input();
                    unset($inputData['_token']);
                    unset($inputData['user_old_password']);
                    unset($inputData['old_password']);
                    unset($inputData['new_password']);
                    unset($inputData['new_confirm_password']);
                    $objAudittrails = new Audittrails();
                    $res = $objAudittrails->add_audit('Update','admin/'. $currentRoute , json_encode($inputData) ,'Change Password' );
                    return true;
                }else{
                    return 'false';
                }
            }else{
                return "false";
            }
        }else{
            return "password_not_match";
        }
    }
}
