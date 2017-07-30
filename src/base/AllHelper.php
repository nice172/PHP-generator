<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/7/30
 * Time: 18:29
 */

/**
 * timeout 超时处理，所有回调执行完成则任务完成
 */
class AllHelper implements Async{

    public $parent;
    public $tasks;
    public $continuation;
    public $done;

    public $n;
    public $results;

    public function __construct(array $tasks, AsyncTask $parent = null)
    {

    }

    public function begin(callable $continuation = null){

    }
}