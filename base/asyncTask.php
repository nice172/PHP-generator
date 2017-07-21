<?php

final class AsyncTask{

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
    public function next($result){

        $value = $this->gen->send($result);

        if ($this->gen->valid()) {
            if ($value instanceof \Generator){
                $continuation = [$this, "next"];

                (new self($value))->begin($continuation);
            }
            $this->next($value);
        }else{
            $cc = $this->continuation;
            $cc($result);
        }
    }
}
