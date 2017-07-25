<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/7/25
 * Time: 22:59
 */

/**
 * 异步回调API是无法直接使用yield，需要使用thunk或者promise进行转换
 */
class callThunk implements Async
{
    public $fun;

    public function __construct(callable $fun)
    {
        $this->fun = $fun;
    }

    public function begin(callable $continuation)
    {
        $fun = $this->fun;
        $fun($continuation);
    }
}

function call(callable $fn)
{
    return new callThunk($fn);
}
