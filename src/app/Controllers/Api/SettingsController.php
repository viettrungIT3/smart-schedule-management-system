<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class SettingsController extends BaseController
{
    public function index()
    {
        $settings = $this->getCurrentSettings();

        return $this->respond([
            'success' => true,
            'data' => $settings
        ]);
    }

    public function save($category)
    {
        $allowedCategories = ['general', 'appearance', 'notifications', 'security', 'backup'];

        if (!in_array($category, $allowedCategories)) {
            return $this->respond([
                'success' => false,
                'message' => 'Invalid settings category'
            ], 400);
        }

        // Try to get JSON data first, fallback to POST data
        $data = $this->request->getJSON(true);

        if (!$data) {
            $data = $this->request->getPost();
        }

        // If still no data, try to parse JSON from raw input
        if (!$data) {
            $rawInput = $this->request->getBody();
            if ($rawInput) {
                $data = json_decode($rawInput, true);
            }
        }

        // Validate data based on category
        $validationResult = $this->validateSettings($category, $data);

        if (!$validationResult['valid']) {
            return $this->respond([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validationResult['errors']
            ], 400);
        }

        // Save settings (in a real application, you'd save to database)
        $saved = $this->saveSettings($category, $data);

        if ($saved) {
            return $this->respond([
                'success' => true,
                'message' => ucfirst($category) . ' settings saved successfully',
                'data' => $data
            ]);
        }

        return $this->respond([
            'success' => false,
            'message' => 'Failed to save settings'
        ], 500);
    }

    private function getCurrentSettings()
    {
        // In a real application, you'd load from database
        return [
            'general' => [
                'app_name' => 'ScheduleFlow',
                'app_url' => base_url(),
                'timezone' => 'Asia/Ho_Chi_Minh'
            ],
            'appearance' => [
                'theme' => 'light',
                'color_preset' => 'preset-1',
                'sidebar_caption' => true,
                'sidebar_position' => 'left',
                'header_style' => 'default',
                'compact_mode' => false
            ],
            'notifications' => [
                'email_schedule_reminder' => true,
                'email_attendance' => true,
                'email_system' => true,
                'push_schedule' => true,
                'push_attendance' => false,
                'push_system' => true
            ],
            'security' => [
                'session_timeout' => 120,
                'max_login_attempts' => 5,
                'two_factor' => false,
                'min_password_length' => 6,
                'require_uppercase' => false,
                'require_numbers' => false,
                'require_symbols' => false
            ],
            'backup' => [
                'auto_backup' => true,
                'backup_frequency' => 'daily',
                'backup_retention' => 30
            ]
        ];
    }

    private function validateSettings($category, $data)
    {
        $errors = [];

        switch ($category) {
            case 'general':
                if (empty($data['app_name'])) {
                    $errors[] = 'Application name is required';
                }
                if (empty($data['app_url']) || !filter_var($data['app_url'], FILTER_VALIDATE_URL)) {
                    $errors[] = 'Valid application URL is required';
                }
                if (empty($data['timezone'])) {
                    $errors[] = 'Timezone is required';
                }
                break;

            case 'appearance':
                if (!empty($data['theme']) && !in_array($data['theme'], ['light', 'dark'])) {
                    $errors[] = 'Invalid theme selection';
                }
                if (!empty($data['color_preset']) && !preg_match('/^preset-[1-6]$/', $data['color_preset'])) {
                    $errors[] = 'Invalid color preset';
                }
                break;

            case 'notifications':
                // All notification settings are boolean, no validation needed
                break;

            case 'security':
                if (isset($data['session_timeout']) && ($data['session_timeout'] < 5 || $data['session_timeout'] > 1440)) {
                    $errors[] = 'Session timeout must be between 5 and 1440 minutes';
                }
                if (isset($data['max_login_attempts']) && ($data['max_login_attempts'] < 3 || $data['max_login_attempts'] > 10)) {
                    $errors[] = 'Max login attempts must be between 3 and 10';
                }
                if (isset($data['min_password_length']) && ($data['min_password_length'] < 4 || $data['min_password_length'] > 20)) {
                    $errors[] = 'Minimum password length must be between 4 and 20 characters';
                }
                break;

            case 'backup':
                if (!empty($data['backup_frequency']) && !in_array($data['backup_frequency'], ['daily', 'weekly', 'monthly'])) {
                    $errors[] = 'Invalid backup frequency';
                }
                if (isset($data['backup_retention']) && ($data['backup_retention'] < 7 || $data['backup_retention'] > 365)) {
                    $errors[] = 'Backup retention must be between 7 and 365 days';
                }
                break;
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    private function saveSettings($category, $data)
    {
        // In a real application, you'd save to database
        // For now, we'll just simulate success

        // Log the settings change
        log_message('info', "Settings updated for category: {$category}", $data);

        return true;
    }
}
