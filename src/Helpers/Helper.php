<?php

namespace Jenson\Currency\Helpers;

use Jenson\Currency\Service\CurrencyService;

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

    /**
     * 获取货币列表
     * @author: Jenson
     * @date: 2024-12-24
     * @desc: currency list
     */
    public static function getCurrencyList($params = [])
    {
        $service = new CurrencyService();
        $data = $service->getCurrencyList($params);
        return $data;
    }

    /**
     * 获取货币汇率信息
     * @author: Jenson
     * @date: 2024-12-24
     * @desc: currency info
     */
    public static function getCurrencyRates($params = [])
    {
        $service = new CurrencyService();
        $data = $service->getCurrencyRates($params);
        return $data;
    }
}

