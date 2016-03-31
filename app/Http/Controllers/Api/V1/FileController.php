<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Services\Api;

use Cache;
use Config;
use Queue;
use DB;
use Storage;
use App\Model\App;
use App\Model\File;

class FileController extends ApiController
{
    /**
     * 上传文件
     *
     * @param Request $request: file $file 文件
     * @return apiReturn
     */
    public function postFile(Request $request)
    {
        return Api::apiReturn(SUCCESS, '上传成功', $this->_uploadFile($request->file('file')));
    }

    /**
     * 上传文件
     *
     * @param file $file 文件
     * @return array
     */
    public function _uploadFile($file)
    {
        $this->apiValidate(['file' => $file], ['file' => 'required']);

        $data['file_name'] = $file->getClientOriginalName();
        $data['extension'] = $file->getClientOriginalExtension();
        $data['mime_type'] = $file->getMimeType();
        $data['size'] = $file->getClientSize();
        $newName = md5($data['file_name'] . time()) . '.' . $data['extension'];
        $directory = config('file.directory') . date('Ymd') . '/';
        $result = $file->move($directory, $newName);
        $data['file_path'] = url($directory . $newName);

        File::create(['app_id' => $this->getAppId(), 'user_id' => $this->getUserId(), 'file_name' => $data['file_name'],
            'file_path' => $data['file_path'], 'extension' => $data['extension'], 'mime_type' => $data['mime_type'],
            'size' => $data['size'], 'created_at' => date('Y-m-d H:i:s')
        ]);

        return $data;
    }

}
