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
        $this->tasks = $tasks;
        $this->parent = $parent;
        $this->n = count($tasks);
        assert($this->n > 0);
        $this->results = [];
    }

    public function begin(callable $continuation = null){
        $this->continuation = $continuation;
        foreach ($this->tasks as $id => $task) {
            (new AsyncTask($task, $this->parent))->begin($this->continuation($id));
        };
    }

    private function continuation($id)
    {
        return function($r, $e = null) use($id) {
            if ($this->done) {
                return;
            }

            // 任一回调发生异常，终止任务
            if ($e) {
                $this->done = true;
                $k = $this->continuation;
                $k(null, $e);
                return;
            }

            $this->results[$id] = $r;
            if (--$this->n === 0) {
                // 所有回调完成，终止任务
                $this->done = true;
                if ($this->continuation) {
                    $k = $this->continuation;
                    $k($this->results);
                }
            }
        };
    }
}