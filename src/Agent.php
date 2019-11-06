<?php

namespace Trt;

class Agent {
  private $Logs;
  private $API;
  private $fund_id;

  public function __construct(aLogs $logs, $api) {
    $this->Logs       = $logs;
    $this->API        = $api;
    $this->Logs->_log('start');
    $this->fund_id    = 'BTCEUR';
  }

  public function getBalance() {
    $this->Logs->_log('Balance');
    return $this->API->QueryPrivate('balances');
  }

  public function getOpenOrders() {
    $this->Logs->_log('OpenOrders');
    return $this->API->QueryPrivate('funds/'.$this->fund_id.'/orders');
  }

  public function getOrderBook() {
    $data = $this->API->QueryPublic('funds/'.$this->fund_id.'/orderbook');
    return $data;
  }

  public function buy($price, $volume) {
    $side     = 'buy';
    return $this->API->QueryPrivate('funds/'.$this->fund_id.'/orders', [
      "fund_id"   => $this->fund_id,
      "side"      => $side,
      "amount"    => "".$volume,
      "price"     => "".$price,
    ], 'POST');
  }

  public function sell($price, $volume) {
    $side     = 'sell';
    return $this->API->QueryPrivate('funds/'.$this->fund_id.'/orders', [
      "fund_id"   => $this->fund_id,
      "side"      => $side,
      "amount"    => "".$volume,
      "price"     => "".$price,
    ], 'POST');
  }

  public function getLogs() {
    return $this->Logs->getLogs();
  }
}
