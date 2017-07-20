<?php

final class AsyncTask{

    public $gen;

    public function __construct($gen){
        $this->gen = new Gen($gen);
    }

    public function begin(){
        return $this->next();
    }

    //传递迭代结果
    public function next($result){

        $value = $this->gen->send($result);

        if ($this->gen->valid()) {
            $this->next($value);
        }
    }
}
