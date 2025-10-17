<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\RBACService;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    protected $rbacService;

    public function __construct()
    {
        $this->rbacService = new RBACService();
    }

    /**
     * Main dashboard - redirects based on user role
     */
    public function index()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $userRole = session()->get('user_role');
        
        // Redirect to appropriate dashboard based on role
        switch ($userRole) {
            case 'admin':
                return $this->admin();
            case 'teacher':
                return $this->teacher();
            case 'student':
                return $this->student();
            default:
                return redirect()->to('/login');
        }
    }

    /**
     * Admin Dashboard
     */
    public function admin()
    {
        // Check admin permissions
        if (!session()->get('logged_in') || session()->get('user_role') !== 'admin') {
            return redirect()->to('/login');
        }

        // Get admin statistics
        $stats = $this->getAdminStats();
        
        // Get recent activities
        $recent_activities = $this->getRecentActivities();
        
        // Get recent users
        $recent_users = $this->getRecentUsers();

        $data = [
            'title' => 'Admin Dashboard',
            'stats' => $stats,
            'recent_activities' => $recent_activities,
            'recent_users' => $recent_users
        ];

        return view('dashboard/admin', $data);
    }

    /**
     * Teacher Dashboard
     */
    public function teacher()
    {
        // Check teacher permissions
        if (!session()->get('logged_in') || session()->get('user_role') !== 'teacher') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        
        // Get teacher statistics
        $stats = $this->getTeacherStats($userId);
        
        // Get today's schedules
        $today_schedules = $this->getTodaySchedules($userId);
        
        // Get upcoming classes
        $upcoming_classes = $this->getUpcomingClasses($userId);
        
        // Get recent attendance
        $recent_attendance = $this->getRecentAttendance($userId);
        
        // Get notifications
        $notifications = $this->getNotifications($userId);

        $data = [
            'title' => 'Teacher Dashboard',
            'stats' => $stats,
            'today_schedules' => $today_schedules,
            'upcoming_classes' => $upcoming_classes,
            'recent_attendance' => $recent_attendance,
            'notifications' => $notifications
        ];

        return view('dashboard/teacher', $data);
    }

    /**
     * Student Dashboard
     */
    public function student()
    {
        // Check student permissions
        if (!session()->get('logged_in') || session()->get('user_role') !== 'student') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        
        // Get student statistics
        $stats = $this->getStudentStats($userId);
        
        // Get today's schedules
        $today_schedules = $this->getTodaySchedules($userId);
        
        // Get weekly schedule
        $weekly_schedule = $this->getWeeklySchedule($userId);
        
        // Get notifications
        $notifications = $this->getNotifications($userId);

        $data = [
            'title' => 'Student Dashboard',
            'stats' => $stats,
            'today_schedules' => $today_schedules,
            'weekly_schedule' => $weekly_schedule,
            'notifications' => $notifications
        ];

        return view('dashboard/student', $data);
    }

    /**
     * Get admin statistics
     */
    private function getAdminStats()
    {
        // Mock data for now - replace with actual database queries
        return [
            'total_users' => 150,
            'total_schedules' => 45,
            'total_classes' => 12,
            'attendance_rate' => 87
        ];
    }

    /**
     * Get teacher statistics
     */
    private function getTeacherStats($userId)
    {
        // Mock data for now - replace with actual database queries
        return [
            'my_classes' => 4,
            'today_schedules' => 3,
            'total_students' => 120,
            'attendance_rate' => 92
        ];
    }

    /**
     * Get student statistics
     */
    private function getStudentStats($userId)
    {
        // Mock data for now - replace with actual database queries
        return [
            'attendance_rate' => 95,
            'classes_attended' => 45,
            'total_subjects' => 6,
            'upcoming_classes' => 3,
            'present_days' => 42,
            'absent_days' => 3
        ];
    }

    /**
     * Get today's schedules
     */
    private function getTodaySchedules($userId)
    {
        // Mock data for now - replace with actual database queries
        return [
            [
                'id' => 1,
                'subject_name' => 'Mathematics',
                'class_name' => '10A1',
                'teacher_name' => 'John Doe',
                'start_time' => '08:00:00',
                'room' => 'Room 101',
                'attendance_status' => null
            ],
            [
                'id' => 2,
                'subject_name' => 'Physics',
                'class_name' => '10A1',
                'teacher_name' => 'Jane Smith',
                'start_time' => '10:00:00',
                'room' => 'Lab 201',
                'attendance_status' => 'present'
            ]
        ];
    }

    /**
     * Get upcoming classes
     */
    private function getUpcomingClasses($userId)
    {
        // Mock data for now
        return [
            [
                'subject_name' => 'Chemistry',
                'class_name' => '10A1',
                'start_time' => '14:00:00',
                'date' => date('Y-m-d')
            ]
        ];
    }

    /**
     * Get recent attendance
     */
    private function getRecentAttendance($userId)
    {
        // Mock data for now
        return [
            [
                'class_name' => '10A1',
                'date' => date('Y-m-d', strtotime('-1 day')),
                'present_count' => 38,
                'absent_count' => 7,
                'attendance_rate' => 84
            ]
        ];
    }

    /**
     * Get weekly schedule
     */
    private function getWeeklySchedule($userId)
    {
        // Mock data for now
        return [
            'Monday' => [
                [
                    'subject_name' => 'Mathematics',
                    'teacher_name' => 'John Doe',
                    'start_time' => '08:00:00',
                    'room' => 'Room 101',
                    'is_today' => false
                ]
            ],
            'Tuesday' => [
                [
                    'subject_name' => 'Physics',
                    'teacher_name' => 'Jane Smith',
                    'start_time' => '10:00:00',
                    'room' => 'Lab 201',
                    'is_today' => true
                ]
            ]
        ];
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities()
    {
        // Mock data for now
        return [
            [
                'title' => 'New user registered',
                'description' => 'Student John Smith joined the system',
                'time' => '2 hours ago'
            ],
            [
                'title' => 'Schedule updated',
                'description' => 'Mathematics class moved to Room 102',
                'time' => '4 hours ago'
            ]
        ];
    }

    /**
     * Get recent users
     */
    private function getRecentUsers()
    {
        // Mock data for now
        return [
            [
                'id' => 1,
                'full_name' => 'John Smith',
                'email' => 'john@example.com',
                'role' => 'student',
                'status' => 'active',
                'last_login' => '2 hours ago'
            ],
            [
                'id' => 2,
                'full_name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'role' => 'teacher',
                'status' => 'active',
                'last_login' => '1 day ago'
            ]
        ];
    }

    /**
     * Get notifications
     */
    private function getNotifications($userId)
    {
        // Mock data for now
        return [
            [
                'title' => 'Class Reminder',
                'message' => 'Mathematics class starts in 30 minutes',
                'icon' => 'bell',
                'type' => 'info',
                'time' => '30 minutes ago'
            ],
            [
                'title' => 'Attendance Marked',
                'message' => 'Your attendance has been recorded for Physics class',
                'icon' => 'check-circle',
                'type' => 'success',
                'time' => '1 hour ago'
            ]
        ];
    }
}
