<?php

class AsyncSleep implements Async{

    public function begin(callable $cc)
    {
        swoole_timer_after(1000, $cc);
    }

}