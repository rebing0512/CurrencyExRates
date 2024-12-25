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

require_once(__DIR__ . '/../Extends/simplehtmldom/simple_html_dom.php');

/**
 * 服务层
 * @author   Jenson
 * @blog     http://jenson.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class CurrencyService
{
    // 接口域名
    private static $url = 'http://www.webmasterhome.cn';
    // 接口地址
    private static $api = '/huilv/huilvchaxun.asp';
    // 接口域名V2
    private static $url_v2 = '';
    // 接口地址V2
    private static $api_v2 = '';
    // 默认页码
    private static $page = 1;
    // 每页数量
    private static $page_size = 20;
    // 数据库表名
    private static $table = 'currency_code';
    // token_v2
    private static $token_v2 = 'MKGEAN3yhsKwyQZT';
    // 版本
    private static $version = '2.0';

    /**
     * 构造函数
     * @params   config
     * @author   Jenson
     * @blog    http://jenson.gg/
     * @version 2.0.0
     * @date    2024-12-25
     * @return  array
     */
    public function __construct($config = [])
    {
        self::$url_v2 = 'https://v2.alapi.cn';
        self::$api_v2 = '/api/exchange';
        $currency_token = getenv('CURRENCY_TOKEN');
        if(!empty($currency_token)){
            self::$token_v2 = $currency_token;
        }
    }
  
    /**
     * 获取汇率
     * @params   amount:默认100,from：默认美元,to：默认人民币
     * @author   Jenson
     * @blog    http://jenson.gg/
     * @version 1.0.0
     * @date    2024-12-24
     * @desc    默认100美元，美元兑人民币汇率
     * @return  array
     */
    public function getCurrencyRates($params = [])
    {
        $version = $params['version']??self::$version;
        if($version == '2.0'){
            $data = $this->getCurrencyRatesV2($params);
            return $data;
        }
        $url = self::$url;
        $api = self::$api;
        $amount = $params['amount']??100;
        #正向汇率
        $from = $params['from']??'USD';
        $to = $params['to']??'CNY';
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
        #逆向汇率
        $from = $params['to']??'CNY';
        $to = $params['from']??'USD';
        $apiUrl = $url.$api.'?amount='.$amount.'&from='.$from.'&to='.$to;
        $data = file_get_html($apiUrl)->plaintext;
        $arr = explode(',',$data);
        $rate_name = trim($arr[0]);
        $rate_str = $arr[1];
        $pattern = '/[\d]+(\.[\d]+)?/';
        preg_match_all($pattern, $rate_str, $matches);
        $numbers = $matches[0];
        $res_reverse = [
            'desc'=>$rate_name,
            'rate'=>$numbers[0]
        ];
        $data = [
            'forward'=>$res,
            'reverse'=>$res_reverse
        ];
        return Helper::DataReturn('ok',200,$data);
    }

    /**
     * 获取汇率V2
     * @params   amount:默认100,from：默认美元,to：默认人民币
     * @author   Jenson
     * @blog    http://jenson.gg/
     * @version 2.0.0
     * @date    2024-12-25
     * @return  array
     */
    public function getCurrencyRatesV2($params = []){
        $url = self::$url_v2;
        $api = self::$api_v2;
        $token = self::$token_v2;
        $amount = $params['amount']??100;
        #正向汇率
        $from = $params['from']??'USD';
        $to = $params['to']??'CNY';
        $apiUrl = $url.$api.'?money='.$amount.'&from='.$from.'&to='.$to.'&token='.$token;
        $result = file_get_html($apiUrl)->plaintext;
        $result = json_decode($result,true);
        $res = [];
        if($result['code'] == 200){
            $data = $result['data'];
            $desc = $amount.$data["currency_form_name"].'可兑换'.$data["exchange_round"].$data["currency_to_name"];
            $rate = $data['exchange']/$amount;
            $res_forward = [
                'desc'=>$desc,
                'rate'=>$rate
            ];
            $res['forward'] = $res_forward;
        }else{
            return Helper::DataReturn($data['msg'],$data['code']);
        }
        #接口不支持并发
        sleep(1);
        #逆向汇率
        $from = $params['to']??'CNY';
        $to = $params['from']??'USD';
        $apiUrl = $url.$api.'?money='.$amount.'&from='.$from.'&to='.$to.'&token='.$token;
        $result = file_get_html($apiUrl)->plaintext;
        $result = json_decode($result,true);
        if($result['code'] == 200){
            $data = $result['data'];
            $desc = $amount.$data["currency_form_name"].'可兑换'.$data["exchange_round"].$data["currency_to_name"];
            $rate = $data['exchange']/$amount;
            $res_reverse = [
                'desc'=>$desc,
                'rate'=>$rate
            ];
            $res['reverse'] = $res_reverse;
        }else{
            return Helper::DataReturn($data['msg'],$data['code']);
        }
        return Helper::DataReturn('ok',200,$res);
    }
    /**
     * 获取货币列表
     * @params   page,page_size
     * @author   Jenson
     * @blog    http://jenson.gg/
     * @version 1.0.0
     * @date    2024-12-24
     * @desc    默认获取全部数据，获取分页数据，入参需要分页参数（默认第1页，20条数据）
     * @return  array
     */
    public function getCurrencyList($params = [])
    {
        $db = new DB();
        $database = $db->database;
        $table = self::$table;
        $where = [
            'status'=>1
        ];
        if(isset($params['page'])){
            $page = $params['page']?:self::$page;
            $page_size = $params['page_size']??self::$page_size;
            $limit_start = ($page - 1)*$page_size ;
            $limit = [$limit_start,$page_size];
            $where = [
                'status'=>1,
                "LIMIT" => $limit
            ];
        }
        $columns = [
            'code','en_name','cn_name'
        ];
        $data = $database->select($table,$columns,$where);
        return Helper::DataReturn('ok',200,$data);
    }

    
}
?>