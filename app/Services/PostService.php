<?php

namespace App\Services;

use App\Repositories\PostRepository;

class PostService
{
    private $postRepository, $fileUploadService;

    public function __construct(PostRepository $postRepository, FileUploadService $fileUploadService)
    {
        $this->postRepository = $postRepository;
        $this->fileUploadService = $fileUploadService;
    }



    public function storePost($request)
    {
        $fileName = null;
        if($request['photo']) {
            $fileName = $this->fileUploadService->setPath($request['photo']);
            $this->fileUploadService->setPathName('post')->uploadFile($fileName, $request['photo']);
        }

        return $this->postRepository->setTitle($request['title'])
            ->setContent($request['content'])
            ->setFile($fileName)
            ->setPublishedAt(date('Y-m-d H:i:s'))
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->storePost();
    }

    // public function editLeave($id)
    // {
    //     return $this->leaveApplyRepository->setId($id)->getLeaveInfo();
    // }

    // public function updateLeave($data, $id)
    // {
    //     return $this->leaveApplyRepository->setId($id)->updateLeave($data);
    // }

    // public function LeaveApplicationEmail($value)
    // {
    //     if($value['leaveTypeId']) {
    //         $receivers = $this->leaveApplyRepository->setId(auth()->user()->id)->getLeaveAppliedEmailRecipient();
    //         if(!$receivers) {
    //             return false;
    //         }

    //         $leaveTypeName = $this->leaveApplyRepository->getLeaveTypes($value['leaveTypeId']);
    //         $data =[
    //             'data' => $value,
    //             'leaveTypeName' =>  $leaveTypeName,
    //             'to' => $receivers[1],
    //             'cc'=> $receivers[0],
    //             'user_email' => auth()->user()->email,
    //             'user_name' => auth()->user()->full_name,
    //         ];
    //         LeaveApplyJob::dispatch($data);
    //         return true;
    //     } else {
    //         $receivers = $this->leaveApplyRepository->getReciever($value->employeeId);
    //         $temp =[
    //             'leaveType' => $value->leaveType,
    //             'startDate' => $value->startDate,
    //             'endDate'=> $value->endDate,
    //         ];
    //         $data =[
    //             'data' => $temp,
    //             'to' => $receivers[1],
    //             'cc'=> $receivers[0],
    //             'user_email' => auth()->user()->email,
    //             'user_name' => auth()->user()->full_name,
    //         ];
    //         LeaveApproveJob::dispatch($data);
    //         return true;
    //     }
    // }

    // public function recommendLeave($data, $id)
    // {
    //     return $this->leaveApplyRepository->recommendLeave($data, $id);
    // }

    // public function approveLeave($data, $id)
    // {
    //     $lineManagers = $this->leaveApplyRepository->setId($data->employeeId)->getlineManagers();
    //     if($lineManagers) {
    //         $flag = 1;
    //         foreach($lineManagers as $lm) {
    //             if($lm->line_manager_user_id == auth()->user()->id) {
    //                 $flag = 0;
    //                 break;
    //             }
    //         }
    //         if($flag) {
    //             $isRecommended = $this->leaveApplyRepository->setId($id)->isRecommended();
    //             if(!$isRecommended) {
    //                 return false;
    //             }
    //         }
    //         if($this->leaveApplyRepository->approveLeave($data, $id)) {
    //             event(new LeaveApplied($data));
    //             return true;
    //         } else {
    //             return false;
    //         }
    //     } else {
    //         if($this->leaveApplyRepository->approveLeave($data, $id)) {
    //             event(new LeaveApplied($data));
    //             return true;
    //         } else {
    //             return false;
    //         }
    //     }
    // }


    // public function delete($id)
    // {
    //     return $this->leaveApplyRepository->delete($id);
    // }

    public function getTableData()
    {
        $userId= auth()->user()->id;
        $result = $this->postRepository->setId($userId)->getTableData();

        if ($result->count() > 0) {
            $data = array();

        foreach ($result as $key=>$row) {
            if($row->photo) {
                $url = asset('storage/postFiles/'. $row->photo);
                $photo = "<img src=\"$url\" class=\"rounded\" width='50px' alt=\"user_img\">";
            } else {
                $url = asset('images/asset.jpeg');
                $photo = "<img src=\"$url\" width='50px' class=\"rounded\" alt=\"user_img\">";
            }
            $id = $row->id;
            $title = $row->title;
            $content = $row->content;
            $published_at = $row->published_at;

            $delete_url = url('leaveApply/'.$id.'/delete');
            $toggle_delete_btn = "<li><a class=\"dropdown-item\" href=\"$delete_url\">Delete</a></li>";
            $action_btn = "<div class=\"col-sm-6 col-xl-4\">
                                <div class=\"dropdown\">
                                    <button type=\"button\" class=\"btn btn-secondary dropdown-toggle\" id=\"dropdown-default-secondary\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                        Action
                                    </button>
                                    <div class=\"dropdown-menu font-size-sm\" aria-labelledby=\"dropdown-default-secondary\">
                                    <ul style=\"max-height: 100px; overflow-x:hidden\">";
            $edit_url = url('leaveApply/'.$id.'/edit');
            $edit_btn = "<li><a class=\"dropdown-item\" href=\"$edit_url\">Edit</a></li>";
            $action_btn .= "$edit_btn $toggle_delete_btn";
            $action_btn .= "</ul>
                            </div>
                        </div>
                    </div>";

            $temp = array();
            array_push($temp, $key+1);
            array_push($temp, $photo);
            array_push($temp, $title);
            array_push($temp, $content);
            array_push($temp, $published_at);
            array_push($temp, $action_btn);
            array_push($data, $temp);
        }

            return json_encode(array('data'=>$data));
        } else {
            return '{
                "sEcho": 1,
                "iTotalRecords": "0",
                "iTotalDisplayRecords": "0",
                "aaData": []
            }';
        }
    }
}
