<?php
namespace App\Services;

use App\Models\TeachingAssignmentModel;
use App\Models\TimeslotModel;
use App\Models\ScheduleModel;

class SchedulerService
{
    public function generateWeeklySchedules(): array
    {
        $assignments = (new TeachingAssignmentModel())
            ->orderBy('class_id ASC, subject_id ASC')
            ->findAll();
        $timeslots = (new TimeslotModel())->orderBy('id ASC')->findAll();

        $teacherBusy = $roomBusy = $classBusy = [];
        $created = [];
        $scheduleModel = new ScheduleModel();

        foreach ($assignments as $a) {
            $remaining = max(1, (int)($a['periods_per_week'] ?? 1));
            for ($weekday = 1; $weekday <= 5 && $remaining > 0; $weekday++) {
                foreach ($timeslots as $t) {
                    $tsId = (int)$t['id'];
                    if (!empty($teacherBusy[$a['teacher_id']][$weekday][$tsId])) continue;
                    if (!empty($classBusy[$a['class_id']][$weekday][$tsId])) continue;
                    $roomId = 1; // đơn giản hóa: dùng phòng 1
                    if (!empty($roomBusy[$roomId][$weekday][$tsId])) continue;

                    $row = [
                        'class_id' => (int)$a['class_id'],
                        'subject_id' => (int)$a['subject_id'],
                        'teacher_id' => (int)$a['teacher_id'],
                        'room_id' => $roomId,
                        'timeslot_id' => $tsId,
                        'weekday' => $weekday,
                        'is_applied' => 0,
                    ];
                    // Upsert theo (class_id, weekday, timeslot_id)
                    $existing = $scheduleModel
                        ->where('class_id', $row['class_id'])
                        ->where('weekday', $weekday)
                        ->where('timeslot_id', $tsId)
                        ->first();
                    if ($existing) {
                        $scheduleModel->update((int)$existing['id'], $row);
                    } else {
                        $scheduleModel->insert($row);
                    }
                    $created[] = $row;
                    $teacherBusy[$a['teacher_id']][$weekday][$tsId] = true;
                    $classBusy[$a['class_id']][$weekday][$tsId] = true;
                    $roomBusy[$roomId][$weekday][$tsId] = true;
                    if (--$remaining <= 0) break;
                }
            }
        }
        return $created;
    }

    public function applyGenerated(): int
    {
        $model = new ScheduleModel();
        return $model->where('is_applied', 0)->set(['is_applied' => 1])->update();
    }
}


