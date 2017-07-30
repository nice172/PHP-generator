<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/7/27
 * Time: 23:13
 */


/**
 * timeout 超时处理，任一回调则任务完成
 */
class Helper implements Async
{
    public $parent;
    public $tasks;
    public $continuation;
    public $done;

    public function __construct(array $tasks, AsyncTask $parent = null)
    {
        assert(!empty($tasks));
        $this->tasks = $tasks;
        $this->parent = $parent;
        $this->done = false;
    }

    public function begin(callable $continuation)
    {
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
            $this->done = true;

            if ($this->continuation) {
                $k = $this->continuation;
                $k($r, $e);
            }
        };
    }
}