<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RateLimitFilter implements FilterInterface
{
    protected $maxAttempts = 5;
    protected $timeWindow = 300; // 5 minutes in seconds
    protected $cache;

    public function __construct()
    {
        $this->cache = \Config\Services::cache();
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        $ip = $request->getIPAddress();
        $key = 'rate_limit_' . md5($ip);
        
        $attempts = $this->cache->get($key);
        
        if ($attempts === null) {
            $attempts = 0;
        }
        
        if ($attempts >= $this->maxAttempts) {
            $response = service('response');
            $response->setStatusCode(429);
            $response->setJSON([
                'error' => 'Too many requests. Please try again later.',
                'retry_after' => $this->timeWindow
            ]);
            return $response;
        }
        
        // Store current attempt
        $this->cache->save($key, $attempts + 1, $this->timeWindow);
        
        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Reset rate limit on successful login
        if ($response->getStatusCode() === 200 || $response->getStatusCode() === 302) {
            $ip = $request->getIPAddress();
            $key = 'rate_limit_' . md5($ip);
            $this->cache->delete($key);
        }
        
        return $response;
    }
}