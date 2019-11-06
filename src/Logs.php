<?php

namespace Trt;

abstract class aLogs {
}

class Logs extends aLogs{
  private $logs;
  private $filename = 'operations.log';

  public function __construct() {
    $this->filename = date('Ymd').'_operations.log';
  }

  public function _log($msg, $write = false) {

    $LOGMSG = date('Ymd-Hi')." {$msg}"."\n";
    $this->logs[] = $LOGMSG;

    if ($write) {
      file_put_contents($this->filename, $LOGMSG, FILE_APPEND | LOCK_EX);
    }
  }
  public function getLogs() {
    return $this->logs;
  }
}

