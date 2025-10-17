<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\JWTHelper;

/**
 * JWTAuthFilter
 * 
 * Middleware to validate JWT tokens for protected routes
 */
class JWTAuthFilter implements FilterInterface
{
    /**
     * JWT Helper instance
     * 
     * @var JWTHelper
     */
    private $jwtHelper;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->jwtHelper = new JWTHelper();
    }

    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Skip authentication for certain routes
        $uri = $request->getUri();
        $path = $uri->getPath();
        
        // Skip auth for login, health check, and docs
        $skipPaths = ['/api/auth/login', '/health', '/docs'];
        foreach ($skipPaths as $skipPath) {
            if (strpos($path, $skipPath) === 0) {
                return;
            }
        }

        // Get Authorization header
        $authHeader = $request->getHeaderLine('Authorization');
        
        if (empty($authHeader)) {
            return $this->unauthorizedResponse('Authorization header is required');
        }

        // Extract token from header
        $token = $this->jwtHelper->extractTokenFromHeader($authHeader);
        
        if (!$token) {
            return $this->unauthorizedResponse('Invalid authorization header format');
        }

        // Validate token
        $payload = $this->jwtHelper->validateToken($token);
        
        if (!$payload) {
            return $this->unauthorizedResponse('Invalid or expired token');
        }

        // Check if it's an access token
        if (!$this->jwtHelper->isAccessToken($token)) {
            return $this->unauthorizedResponse('Invalid token type');
        }

        // Add user data to request for use in controllers
        $request->user = $payload['data'];

        return;
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after request
    }

    /**
     * Return unauthorized response
     * 
     * @param string $message
     * @return ResponseInterface
     */
    private function unauthorizedResponse(string $message): ResponseInterface
    {
        $response = service('response');
        return $response->setStatusCode(401)
                       ->setJSON([
                           'error' => $message,
                           'code' => 401
                       ]);
    }
}
