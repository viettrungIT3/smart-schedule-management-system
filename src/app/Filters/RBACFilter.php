<?php

namespace App\Filters;

use App\Services\RBACService;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RBACFilter implements FilterInterface
{
    protected $rbacService;

    public function __construct()
    {
        $this->rbacService = new RBACService();
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
        // Get user ID from JWT token or session
        $userId = $this->getUserId($request);
        
        if (!$userId) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'success' => false,
                    'error' => 'Authentication required'
                ]);
        }

        // Check if user has required permissions
        if (!empty($arguments)) {
            $permissions = is_array($arguments) ? $arguments : [$arguments];
            
            if (!$this->rbacService->hasAnyPermission($userId, $permissions)) {
                return service('response')
                    ->setStatusCode(403)
                    ->setJSON([
                        'success' => false,
                        'error' => 'Insufficient permissions',
                        'required_permissions' => $permissions
                    ]);
            }
        }

        return $request;
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
        // No processing needed after request
        return $response;
    }

    /**
     * Get user ID from request
     *
     * @param RequestInterface $request
     * @return int|null
     */
    protected function getUserId(RequestInterface $request): ?int
    {
        // Try to get from JWT token first
        $authHeader = $request->getHeaderLine('Authorization');
        
        if ($authHeader && strpos($authHeader, 'Bearer ') === 0) {
            $token = substr($authHeader, 7);
            
            try {
                $jwtHelper = new \App\Libraries\JWTHelper();
                $userData = $jwtHelper->getUserFromToken($token);
                
                if ($userData && isset($userData['id'])) {
                    return (int) $userData['id'];
                }
            } catch (\Exception $e) {
                // JWT token invalid, continue to session check
            }
        }

        // Try to get from session
        $session = session();
        if ($session->has('user_id')) {
            return (int) $session->get('user_id');
        }

        return null;
    }
}
