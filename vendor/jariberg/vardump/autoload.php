<?php

// Create vardump and vardumphtml shorthands

if (!function_exists('vardump')) {

  function vardump()
  {
    $vardump = new Vardump();
    call_user_func(array($vardump, 'dump'), Vardump::getArgs(func_num_args(), func_get_args()));
  }

  function vardump_html()
  {
    $vardump = new Vardump();
    $vardump->setHtmlMode(true);
    call_user_func(array($vardump, 'dump'), Vardump::getArgs(func_num_args(), func_get_args()));
  }

  function vardump_error()
  {
    $vardump = new Vardump();
    call_user_func(array($vardump, 'error'), Vardump::getArgs(func_num_args(), func_get_args()));
  }

  function vardump_info()
  {
    $vardump = new Vardump();
    call_user_func(array($vardump, 'info'), Vardump::getArgs(func_num_args(), func_get_args()));
  }

  function vardump_exception(\Exception $e)
  {
    $vardump = new Vardump();
    $vardump->dumpPhpException($e);
  }
}


