<?php

   /**
     * 统一生成器接口 current,key,next,rewind,valid
     */
   class Gen
   {

       public $isfirst = true;
       public $generator;


       function __construct($generator)
       {
           $this->generator = $generator;
       }

    /**
     * 检查当前标量是否有效
     */
       public function valid(){
            return $this->generator->valid();
       }

       /*
        *generator implements Iterator
        */
        public function send($value = null){
            if($this->isfirst){
                $this->isfirst = true;
                return $this->generator->current();
            }else{
                return $this->generator->send($value);
            }
        }

         /*
        *throw 异常处理
        */
         public function throw_(\Exception $e){
            return $this->generator->throw($e);
         }
   }