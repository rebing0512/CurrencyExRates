# CurrencyExRates

php货币汇率扩展包

### 安装

```bash
composer require jenson0512/currencyexrates
```

## 使用
* 1、获取货币列表
    ```
    use Jenson\Currency\Helpers\Helper as CHelper;
    $currency = new CHelper();
        $pamars = [
            'page'        => 1,   #分页,默认1，非必填
            'page_saze'   => 20,  #每页数量默认20，非必填
        ];
    $data = $currency->getCurrencyList($pamars);
    
    ```
* 2、获取汇率
  ```
  use Jenson\Currency\Helpers\Helper as CHelper;
  $currency = new CHelper();
      $pamars = [
          'amount' => 1,      #兑换金额,默认100，非必填
          'from'   => 'USD',  #from默认美元USD，非必填
          'to'     => 'CNY',  #to默认人民币CNY，非必填
      ];
  $data = $currency->getCurrencyRates($pamars);
      
  ```
  
## 数据库
* 1、执行Database目录中create_table.txt中的sql创建数据表
* 2、然后再执行Database目录中的currency_code.sql文件添加数据

