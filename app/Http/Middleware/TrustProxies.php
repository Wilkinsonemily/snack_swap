<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Middleware\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * Trust all proxies (Railway, cloud load balancer)
     */
    protected $proxies = '*';

    /**
     * Use all forwarded headers
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
