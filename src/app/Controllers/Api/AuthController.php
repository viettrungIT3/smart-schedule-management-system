<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Libraries\JWTHelper;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AuthController
 * 
 * Handles authentication endpoints for JWT and Session-based auth
 */
class AuthController extends BaseController
{
    /**
     * JWT Helper instance
     * 
     * @var JWTHelper
     */
    private $jwtHelper;

    /**
     * User Model instance
     * 
     * @var UserModel
     */
    private $userModel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->jwtHelper = new JWTHelper();
        $this->userModel = new UserModel();
    }

    /**
     * Login endpoint
     * 
     * @return ResponseInterface
     */
    public function login(): ResponseInterface
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'error' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ], 400);
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Find user by email
        $user = $this->userModel->where('email', $email)
                               ->where('status', 'active')
                               ->first();

        if (!$user) {
            return $this->respond([
                'error' => 'Invalid credentials'
            ], 401);
        }

        // Verify password
        if (!password_verify($password, $user['password_hash'])) {
            return $this->respond([
                'error' => 'Invalid credentials'
            ], 401);
        }

        // Prepare user data for token
        $userData = [
            'id' => (int) $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
            'full_name' => $user['full_name'],
            'status' => $user['status']
        ];

        // Generate JWT tokens
        $tokens = $this->jwtHelper->generateTokenPair($userData);

        // Create session if requested
        $authType = $this->request->getPost('auth_type') ?? 'jwt';
        if ($authType === 'session' || $authType === 'both') {
            $session = \Config\Services::session();
            $session->set([
                'user_id' => $user['id'],
                'user_email' => $user['email'],
                'user_role' => $user['role'],
                'user_name' => $user['full_name'],
                'logged_in' => true
            ]);
        }

        return $this->respond([
            'message' => 'Login successful',
            'user' => $userData,
            'tokens' => $tokens,
            'auth_type' => $authType
        ], 200);
    }

    /**
     * Logout endpoint
     * 
     * @return ResponseInterface
     */
    public function logout(): ResponseInterface
    {
        // Destroy session if exists
        $session = \Config\Services::session();
        if ($session->get('logged_in')) {
            $session->destroy();
        }

        return $this->respond([
            'message' => 'Logout successful'
        ], 200);
    }

    /**
     * Refresh token endpoint
     * 
     * @return ResponseInterface
     */
    public function refresh(): ResponseInterface
    {
        $rules = [
            'refresh_token' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'error' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ], 400);
        }

        $refreshToken = $this->request->getPost('refresh_token');

        // Validate refresh token
        if (!$this->jwtHelper->isRefreshToken($refreshToken)) {
            return $this->respond([
                'error' => 'Invalid refresh token'
            ], 401);
        }

        $payload = $this->jwtHelper->validateToken($refreshToken);
        if (!$payload) {
            return $this->respond([
                'error' => 'Invalid or expired refresh token'
            ], 401);
        }

        // Get user data
        $userId = $payload['user_id'];
        $user = $this->userModel->find($userId);

        if (!$user || $user['status'] !== 'active') {
            return $this->respond([
                'error' => 'User not found or inactive'
            ], 401);
        }

        // Prepare user data for new token
        $userData = [
            'id' => (int) $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
            'full_name' => $user['full_name'],
            'status' => $user['status']
        ];

        // Generate new token pair
        $tokens = $this->jwtHelper->generateTokenPair($userData);

        return $this->respond([
            'message' => 'Token refreshed successfully',
            'tokens' => $tokens
        ], 200);
    }

    /**
     * Get current user profile
     * 
     * @return ResponseInterface
     */
    public function profile(): ResponseInterface
    {
        // This endpoint should be protected by JWT middleware
        $user = $this->getCurrentUser();
        
        if (!$user) {
            return $this->respond([
                'error' => 'Unauthorized'
            ], 401);
        }

        return $this->respond([
            'user' => $user
        ], 200);
    }

    /**
     * Change password endpoint
     * 
     * @return ResponseInterface
     */
    public function changePassword(): ResponseInterface
    {
        $user = $this->getCurrentUser();
        
        if (!$user) {
            return $this->respond([
                'error' => 'Unauthorized'
            ], 401);
        }

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'error' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ], 400);
        }

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        // Get user from database
        $userData = $this->userModel->find($user['id']);

        // Verify current password
        if (!password_verify($currentPassword, $userData['password_hash'])) {
            return $this->respond([
                'error' => 'Current password is incorrect'
            ], 400);
        }

        // Update password
        $this->userModel->update($user['id'], [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->respond([
            'message' => 'Password changed successfully'
        ], 200);
    }

    /**
     * Get current user from JWT token or session
     * 
     * @return array|null
     */
    private function getCurrentUser(): ?array
    {
        // Try JWT first
        $authHeader = $this->request->getHeaderLine('Authorization');
        if ($authHeader) {
            $token = $this->jwtHelper->extractTokenFromHeader($authHeader);
            if ($token) {
                return $this->jwtHelper->getUserFromToken($token);
            }
        }

        // Try session
        $session = \Config\Services::session();
        if ($session->get('logged_in')) {
            return [
                'id' => $session->get('user_id'),
                'email' => $session->get('user_email'),
                'role' => $session->get('user_role'),
                'full_name' => $session->get('user_name'),
                'status' => 'active'
            ];
        }

        return null;
    }
}
