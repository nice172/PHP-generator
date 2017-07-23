<?php

/**
 * 统一生成器接口 current,key,next,rewind,valid
 */
class AsyncDns implements Async
{
    public function begin(callable $cc)
    {
        swoole_async_dns_lookup("www.baidu.com", function($host, $ip) use($cc) {
            $cc($ip);
            $cc($host);
        });
    }
}