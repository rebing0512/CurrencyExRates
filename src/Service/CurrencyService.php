<?php
// +----------------------------------------------------------------------
// | Jenson Base
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2099 http://jenson.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://opensource.org/licenses/mit-license.php )
// +----------------------------------------------------------------------
// | Author: Jenson
// +----------------------------------------------------------------------
namespace Jenson\Currency\Service;

use Jenson\Currency\Helpers\DB;
use Jenson\Currency\Helpers\Helper;

require_once('../Extends/simplehtmldom/simple_html_dom.php');

/**
 * 服务层
 * @author   Jenson
 * @blog     http://jenson.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class CurrencyService
{
    private static $url = 'http://www.webmasterhome.cn';
    private static $api = '/huilv/huilvchaxun.asp';
    /**
     * 构造函数
     */
    public function __construct()
    {
        //
    }
  
    /**
     * 获取汇率
     * @params   amount,from,to
     * @author   Jenson
     * @blog    http://jenson.gg/
     * @version 1.0.0
     * @date    2024-12-24
     * @desc    description
     */
    public function getCurrencyRates($params = [])
    {
        $url = self::$url;
        $api = self::$api;
        $amount = $params['amount']??1;
        $from = $params['from']??'CNY';
        $to = $params['to']??'USD';
        $apiUrl = $url.$api.'?amount='.$amount.'&from='.$from.'&to='.$to;
        $data = file_get_html($apiUrl)->plaintext;
        $arr = explode(',',$data);
        $rate_name = trim($arr[0]);
        $rate_str = $arr[1];
        $pattern = '/[\d]+(\.[\d]+)?/';
        preg_match_all($pattern, $rate_str, $matches);
        $numbers = $matches[0];
        $res = [
            'desc'=>$rate_name,
            'rate'=>$numbers[0]
        ];
        return Helper::DataReturn('ok',200,$res);
    }

    /**
     * 获取货币列表
     * @author   Jenson
     * @blog    http://jenson.gg/
     * @version 1.0.0
     * @date    2024-12-24
     * @desc    description
     */
    public function getCurrencyList($params = [])
    {
        $db = new DB();
        $database = $db->database;
        $table = 'currency_code';
        $where = [
            ['status','=',1]
        ];
        $columns = [
            'code','en_name','cn_name'
        ];
        $data = $database->select($table,$columns,$where);
        return Helper::DataReturn('ok',200,$data);
    }

    
}
?>