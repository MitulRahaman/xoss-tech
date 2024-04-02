<?php

namespace App\Repositories;

use Validator;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;



class PostRepository
{
    private $title, $content, $file, $publishedAt, $createdAt;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;
        return $this;
    }
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    // public function getLeaveTypes($id)
    // {
    //     if($id == null) {
    //         return LeaveType::where('status', Config::get('variable_constants.activation.active'))->get();
    //     } else {
    //         return LeaveType::where('id', '=', $id)->first()->name;
    //     }

    // }

    public function getTableData()
    {
        return DB::table('posts as p')
        ->where('p.user_id', $this->id)
        ->select('p.*')
        ->get();
    }



    // public function getLeaveAppliedEmailRecipient()
    // {
    //     $appliedUser = DB::table('basic_info')->where('user_id', '=', $this->id)->first();
    //     if($appliedUser == null ) {
    //         return false;
    //     }

    //     $getLineManagers  = DB::table('users as u')
    //     ->leftJoin('line_managers as lm', function ($join) {
    //         $join->on('u.id', '=', 'lm.user_id')
    //         ->whereNULL('lm.deleted_at');
    //     })
    //     ->where('lm.user_id', '=', $appliedUser->user_id)
    //     ->select('lm.line_manager_user_id')
    //     ->get()
    //     ->toArray();

    //     $lineManagerEmail = array();
    //     foreach ($getLineManagers as $glm) {
    //         array_push($lineManagerEmail, DB::table('users')->where('id', '=', $glm->line_manager_user_id)->first()->email);
    //     }

    //     $hasManageLeavePermission = DB::table('permissions as p')
    //         ->leftJoin('role_permissions as rp', 'p.id', '=', 'rp.permission_id')
    //         ->leftJoin('basic_info as bi', 'bi.role_id', '=', 'rp.role_id')
    //         ->where('p.slug', '=', 'notifyLeaveApply')
    //         ->where('bi.branch_id', '=', $appliedUser->branch_id)
    //         ->select('rp.role_id')
    //         ->get()
    //         ->toArray();
    //     if($hasManageLeavePermission == null ) {
    //         return false;
    //     }

    //     $recipientEmail = array();
    //     foreach ($hasManageLeavePermission as $hmlp) {
    //         array_push($recipientEmail, DB::table('basic_info')->where('role_id', '=', $hmlp->role_id)->first()->preferred_email);
    //     }
    //     if($recipientEmail == null ) {
    //         return false;
    //     }
    //     return [$lineManagerEmail, $recipientEmail];
    // }

    public function storePost()
    {
        return DB::table('posts')->insert([
            'user_id' => auth()->user()->id,
            'title' => $this->title,
            'content' => $this->content,
            'photo' => $this->file[0],
            'published_at' => $this->publishedAt,
            'created_at' => $this->createdAt,
            'updated_at' => null,
        ]);
    }

    // public function getLeaveInfo()
    // {
    //     return LeaveApply::find($this->id);
    // }

    // public function updateLeave($data)
    // {
    //     $data->startDate = date("Y-m-d", strtotime(str_replace("/","-",$data->startDate)));
    //     $data->endDate = date("Y-m-d", strtotime(str_replace("/","-",$data->endDate)));
    //     if($data->totalLeave == null) {
    //         DB::table('leaves')
    //         ->where('id', '=', $this->id)
    //         ->update([
    //             'leave_type_id' => $data->leaveTypeId,
    //             'start_date' => $$data->startDatee,
    //             'end_date' => $data->endDate,
    //             'reason' => $data->reason,
    //         ]);
    //     } else {

    //         DB::table('leaves')
    //         ->where('id', '=', $this->id)
    //         ->update([
    //             'leave_type_id' => $data->leaveTypeId,
    //             'start_date' => $data->startDate,
    //             'end_date' => $data->endDate,
    //             'total' => $data->totalLeave,
    //             'reason' => $data->reason,
    //         ]);
    //     }
    //     return true;
    // }
    // public function recommendLeave($data, $id)
    // {
    //     return DB::table('leaves')->where('id',$id)->update(['status'=> Config::get('variable_constants.leave_status.line_manager_approval'),
    //         'remarks'=>$data['recommend-modal-remarks']]);
    // }
    // public function approveLeave($data, $id)
    // {
    //     return DB::table('leaves')->where('id',$id)->update(['status'=> Config::get('variable_constants.leave_status.approved'),
    //         'remarks'=>$data['approve-modal-remarks']]);
    // }
    // public function rejectLeave($data, $id)
    // {
    //     return DB::table('leaves')->where('id',$id)->update(['status'=> Config::get('variable_constants.leave_status.rejected'),
    //         'remarks'=>$data['reject-modal-remarks']]);
    // }
    // public function cancelLeave($id)
    // {
    //     return DB::table('leaves')->where('id',$id)->update(['status'=> Config::get('variable_constants.leave_status.canceled')]);
    // }
    // public function delete($id)
    // {
    //     return DB::table('leaves')->where('id', $id)->delete();
    // }
    // public function getReciever($employeeId)
    // {
    //     $appliedUser = DB::table('users')->where('employee_id',$employeeId)->first();
    //     $getLineManagers  = DB::table('users as u')
    //     ->leftJoin('line_managers as lm', function ($join) {
    //         $join->on('u.id', '=', 'lm.user_id')
    //         ->whereNULL('lm.deleted_at');
    //     })
    //     ->where('lm.user_id', '=', $appliedUser->id)
    //     ->select('lm.line_manager_user_id')
    //     ->get()
    //     ->toArray();

    //     $lineManagerEmail = array();
    //     foreach ($getLineManagers as $glm) {
    //         array_push($lineManagerEmail, DB::table('users')->where('id', '=', $glm->line_manager_user_id)->first()->email);
    //     }
    //     return [$lineManagerEmail, $appliedUser->email];
    // }
}
