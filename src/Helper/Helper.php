<?php

namespace Jenson\Currency\Helper;

class Helper
{
    public static function DataReturn($msg = '', $code = 0, $data = '')
    {
        // 默认情况下，手动调用当前方法
        $result = ['msg'=>$msg, 'code'=>$code, 'data'=>$data];

        // 错误情况下，防止提示信息为空
        if($result['code'] != 0 && empty($result['msg']))
        {
            $result['msg'] = '操作失败';
        }
        return $result;
    }

}

