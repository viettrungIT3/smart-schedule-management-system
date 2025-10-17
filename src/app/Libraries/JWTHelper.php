<?php

namespace App\Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use CodeIgniter\Config\Services;

/**
 * JWTHelper Library
 * 
 * Handles JWT token generation, validation, and management
 */
class JWTHelper
{
    /**
     * JWT Secret Key
     * 
     * @var string
     */
    private $secretKey;

    /**
     * JWT Algorithm
     * 
     * @var string
     */
    private $algorithm = 'HS256';

    /**
     * Access Token Expiration Time (in seconds)
     * 
     * @var int
     */
    private $accessTokenExpiry = 3600; // 1 hour

    /**
     * Refresh Token Expiration Time (in seconds)
     * 
     * @var int
     */
    private $refreshTokenExpiry = 604800; // 7 days

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->secretKey = env('JWT_SECRET_KEY', 'scheduleflow_jwt_secret_key_2024');
    }

    /**
     * Generate Access Token
     * 
     * @param array $payload User data to include in token
     * @return string JWT token
     */
    public function generateAccessToken(array $payload): string
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + $this->accessTokenExpiry;

        $tokenPayload = [
            'iss' => 'scheduleflow-api', // Issuer
            'aud' => 'scheduleflow-client', // Audience
            'iat' => $issuedAt, // Issued at
            'exp' => $expirationTime, // Expiration time
            'type' => 'access', // Token type
            'data' => $payload // User data
        ];

        return JWT::encode($tokenPayload, $this->secretKey, $this->algorithm);
    }

    /**
     * Generate Refresh Token
     * 
     * @param int $userId User ID
     * @return string JWT refresh token
     */
    public function generateRefreshToken(int $userId): string
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + $this->refreshTokenExpiry;

        $tokenPayload = [
            'iss' => 'scheduleflow-api',
            'aud' => 'scheduleflow-client',
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'type' => 'refresh',
            'user_id' => $userId
        ];

        return JWT::encode($tokenPayload, $this->secretKey, $this->algorithm);
    }

    /**
     * Validate JWT Token
     * 
     * @param string $token JWT token
     * @return array|false Decoded payload or false if invalid
     */
    public function validateToken(string $token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, $this->algorithm));
            return (array) $decoded;
        } catch (ExpiredException $e) {
            log_message('error', 'JWT Token expired: ' . $e->getMessage());
            return false;
        } catch (SignatureInvalidException $e) {
            log_message('error', 'JWT Token signature invalid: ' . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            log_message('error', 'JWT Token validation error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Extract token from Authorization header
     * 
     * @param string $authHeader Authorization header value
     * @return string|null Token or null if not found
     */
    public function extractTokenFromHeader(string $authHeader): ?string
    {
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Get user data from token
     * 
     * @param string $token JWT token
     * @return array|null User data or null if invalid
     */
    public function getUserFromToken(string $token): ?array
    {
        $payload = $this->validateToken($token);
        
        if ($payload && isset($payload['data'])) {
            // Convert stdClass to array if needed
            $data = $payload['data'];
            if (is_object($data)) {
                return (array) $data;
            }
            return $data;
        }
        
        return null;
    }

    /**
     * Check if token is access token
     * 
     * @param string $token JWT token
     * @return bool
     */
    public function isAccessToken(string $token): bool
    {
        $payload = $this->validateToken($token);
        return $payload && isset($payload['type']) && $payload['type'] === 'access';
    }

    /**
     * Check if token is refresh token
     * 
     * @param string $token JWT token
     * @return bool
     */
    public function isRefreshToken(string $token): bool
    {
        $payload = $this->validateToken($token);
        return $payload && isset($payload['type']) && $payload['type'] === 'refresh';
    }

    /**
     * Get token expiration time
     * 
     * @param string $token JWT token
     * @return int|null Expiration timestamp or null if invalid
     */
    public function getTokenExpiration(string $token): ?int
    {
        $payload = $this->validateToken($token);
        return $payload && isset($payload['exp']) ? $payload['exp'] : null;
    }

    /**
     * Check if token is expired
     * 
     * @param string $token JWT token
     * @return bool
     */
    public function isTokenExpired(string $token): bool
    {
        $expiration = $this->getTokenExpiration($token);
        return $expiration ? $expiration < time() : true;
    }

    /**
     * Generate token pair (access + refresh)
     * 
     * @param array $userData User data for access token
     * @return array Token pair
     */
    public function generateTokenPair(array $userData): array
    {
        $accessToken = $this->generateAccessToken($userData);
        $refreshToken = $this->generateRefreshToken($userData['id']);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            'expires_in' => $this->accessTokenExpiry
        ];
    }
}
