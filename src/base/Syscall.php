<?php
/**
 * Created by PhpStorm.
 * User: silen
 * Date: 2017/7/24
 * Time: 0:33
 */
class Syscall
{
    private $fun;

    public function __construct(callable $fun)
    {
        $this->fun = $fun;
    }

    public function __invoke(AsyncTask $task)
    {
        $cb = $this->fun;
        return $cb($task);
    }
}