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

    public function _uploadFile($file)
    {
        $this->apiValidate(['file' => $file], ['file' => 'required']);

        $data['file_name'] = $file->getClientOriginalName();
        $data['extension'] = $file->getClientOriginalExtension();
        $data['mime_type'] = $file->getMimeType();
        $data['size'] = $file->getClientSize();
        $newName = md5($data['file_name'] . time()) . '.' . $data['extension'];
        $directory = config('file.directory') . date('Ymd', time()) . '/';
        $result = $file->move($directory, $newName);
        $data['file_path'] = url($directory . $newName);

        return $data;
    }

}
