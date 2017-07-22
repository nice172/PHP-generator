<?php

final class AsyncTask implements Async {

    public $gen;
    public $continuation;

    public function __construct($gen){
        $this->gen = new Gen($gen);
    }

    public function begin(callable $continuation){
        $this->continuation = $continuation;
        $this->next();
    }

    //传递迭代结果
    public function next($result = null, \Exception $e = null){
        if ($e) {
             $this->gen->throw_($e);
        }

        $e = null;
        try {
            // send方法内部是一个resume的过程: 
            // 恢复execute_data上下文， 调用zend_execute_ex()继续执行,
            // 后续中op_array内可能会抛出异常
            $value = $this->gen->send($result);
        } catch (\Exception $e) {};

       if ($e) {
            if ($this->gen->valid()) {
                // 传递异常
                return $this->next(null, $e);
            } else {
                throw $e;
            }
        } else {
            if ($this->gen->valid()) {
                // 正常yield值
                return $this->next($value);
            } else {
                return $result;
            }
        }
    }
}
