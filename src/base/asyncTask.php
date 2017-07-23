<?php

final class AsyncTask implements Async {

    public $gen;
    public $continuation;
    public $parents;

    public function __construct(\Generator $gen ,AsyncTask $parent = null){
        $this->gen = new Gen($gen);
        $this->parent = $parent;
    }

     public function begin(callable $continuation){
         $this->continuation = $continuation;
         $this->next();
     }

    //传递迭代结果
    public function next($result = null,  $e = null){
        try{
            if ($e){
                $value = $this->gen->throw_($e);
            }else{
                $value = $this->gen->send($result);
            }
            if ($this->gen->valid()){
                //Syscall 可能返回\Generator 或者 Async
                if ($value instanceof Syscall){
                    $value = $value($this);
                }
                if ($value instanceof \Generator){
                    $value = new self($value, $this);
                }
                if ($value instanceof Async){
                    $cc = [$this, "next"];
                    $value->begin($cc);
                }else {
                    $this->next($value, null);
                }
            }else {
                $cc = $this->continuation;
                $cc($result, null);
            }

        }catch (\Exception $e){
            if ($this->gen->valid()) {
                $this->next(null, $e);
            } else {
                $cc = $this->continuation;
                $cc($result, $e);
            }
        }
    }
}
