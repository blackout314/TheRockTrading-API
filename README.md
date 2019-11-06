# TheRockTrading-API
the rock trading php api library


### example ###
```php
$logs   = new \Trt\Logs();
$api    = new \Trt\Api($TRTKEY, $TRTSEC);
$agent  = new \Trt\Agent($logs, $api);

$orders = $agent->getOpenOrders();
```
