<?php
/**
 * Created by PhpStorm.
 * User: silen
 * Date: 2017/7/24
 * Time: 21:45
 */

/**
 * spawn one semicoroutine
 *  第一个参数为task
 *  剩余参数(优先检查callable)
 *      如果参数类型 callable 则参数被设置为 Continuation
 *      如果参数类型 AsyncTask 则参数被设置为 ParentTask
 *      如果参数类型 array 则参数被设置为 Context
 */
function spawn(){
    $inter = func_num_args();
    if ($inter == 0){
        return ;
    }

    $task = func_get_arg(0);
    $continuation = function (){};
    $parent = null;
    $ctx = [];

    for ($i=1;$i<$inter;$i++){
        $arg = func_get_arg($i);
        if (is_callable($arg)){
            $continuation = $arg;
        }elseif ($arg instanceof AsyncTask){
            $parent = $arg;
        }elseif (is_array($arg)){
            $ctx = $arg;
        }
    }

    if (is_callable($task)){
        try{
            $task = $task();
        }catch (\Exception $e){
            $continuation(null, $e);
            return;
        }
    }
    if ($task instanceof \Generator){
        foreach ($ctx as $k=>$v){
            $task->$k = $v;
        }
        (new AsyncTask($task, $parent))->begin($continuation);
    }else {
        $continuation($task, null);
    }
}