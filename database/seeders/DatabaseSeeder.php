<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
use App\Models\SchoolClass;
use App\Models\Announcement;
use App\Models\TaskSubmission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            /*
            |--------------------------------------------------------------------------
            | DEMONSTRATION ACCOUNTS
            |--------------------------------------------------------------------------
            */

            $admin = User::updateOrCreate(
                ['email' => 'admin@demo.com'],
                [
                    'name' => 'Demo Administrator',
                    'first_name' => 'Demo',
                    'role' => 'admin',
                    'password' => Hash::make('DemoAdmin123!'),
                    'is_approved' => true,
                    'email_verified_at' => now(),
                ]
            );

            $teachers = collect();

            foreach ([
                ['name' => 'Maria Santos', 'email' => 'teacher1@demo.com', 'employee_id' => 'EMP-DEMO-001'],
                ['name' => 'Juan Reyes', 'email' => 'teacher2@demo.com', 'employee_id' => 'EMP-DEMO-002'],
            ] as $data) {
                $teachers->push(
                    User::updateOrCreate(
                        ['email' => $data['email']],
                        [
                            'name' => $data['name'],
                            'first_name' => explode(' ', $data['name'])[0],
                            'role' => 'teacher',
                            'employee_id' => $data['employee_id'],
                            'password' => Hash::make('DemoTeacher123!'),
                            'is_approved' => true,
                            'email_verified_at' => now(),
                        ]
                    )
                );
            }

            $students = collect();

            foreach ([
                ['name' => 'Alex Cruz', 'email' => 'student1@demo.com', 'student_id' => 'STU-DEMO-001'],
                ['name' => 'Jamie Garcia', 'email' => 'student2@demo.com', 'student_id' => 'STU-DEMO-002'],
                ['name' => 'Sam Mendoza', 'email' => 'student3@demo.com', 'student_id' => 'STU-DEMO-003'],
                ['name' => 'Taylor Flores', 'email' => 'student4@demo.com', 'student_id' => 'STU-DEMO-004'],
                ['name' => 'Casey Ramos', 'email' => 'student5@demo.com', 'student_id' => 'STU-DEMO-005'],
            ] as $data) {
                $students->push(
                    User::updateOrCreate(
                        ['email' => $data['email']],
                        [
                            'name' => $data['name'],
                            'first_name' => explode(' ', $data['name'])[0],
                            'role' => 'student',
                            'student_id' => $data['student_id'],
                            'password' => Hash::make('DemoStudent123!'),
                            'is_approved' => true,
                            'email_verified_at' => now(),
                        ]
                    )
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CLASSES
            |--------------------------------------------------------------------------
            */

            $classA = SchoolClass::updateOrCreate(
                [
                    'name' => 'General Calculus',
                    'section' => '12-A',
                ],
                [
                    'academic_year' => '2026-2027',
                    'teacher_id' => $teachers[0]->id,
                ]
            );

            $classB = SchoolClass::updateOrCreate(
                [
                    'name' => 'Computer Programming',
                    'section' => '12-B',
                ],
                [
                    'academic_year' => '2026-2027',
                    'teacher_id' => $teachers[1]->id,
                ]
            );

            foreach ($students as $index => $student) {
                $student->schoolClasses()->syncWithoutDetaching([
                    $index < 3 ? $classA->id : $classB->id,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | TASKS
            |--------------------------------------------------------------------------
            */

            $taskData = [
                [
                    'title' => 'Calculus Assignment',
                    'subject' => 'General Calculus',
                    'type' => 'Assignment',
                    'description' => 'Solve the assigned derivative problems and submit your answers.',
                    'due_date' => now()->addDays(3),
                    'status' => 'Pending',
                    'created_by' => $teachers[0]->id,
                    'school_class_id' => $classA->id,
                ],
                [
                    'title' => 'Derivative Quiz',
                    'subject' => 'General Calculus',
                    'type' => 'Quiz',
                    'description' => 'Review the rules of differentiation before answering.',
                    'due_date' => now()->addDays(5),
                    'status' => 'Pending',
                    'created_by' => $teachers[0]->id,
                    'school_class_id' => $classA->id,
                ],
                [
                    'title' => 'Programming Project',
                    'subject' => 'Computer Programming',
                    'type' => 'Project',
                    'description' => 'Create a simple application and explain how it works.',
                    'due_date' => now()->addDays(7),
                    'status' => 'Pending',
                    'created_by' => $teachers[1]->id,
                    'school_class_id' => $classB->id,
                ],
                [
                    'title' => 'Programming Exercise',
                    'subject' => 'Computer Programming',
                    'type' => 'Assignment',
                    'description' => 'Complete the programming exercises discussed in class.',
                    'due_date' => now()->addDays(2),
                    'status' => 'Pending',
                    'created_by' => $teachers[1]->id,
                    'school_class_id' => $classB->id,
                ],
            ];

            $tasks = collect();

            foreach ($taskData as $data) {
                $task = Task::updateOrCreate(
                    [
                        'title' => $data['title'],
                        'created_by' => $data['created_by'],
                    ],
                    $data
                );

                $tasks->push($task);

                $classStudents = $task->school_class_id === $classA->id
                    ? $students->take(3)
                    : $students->slice(3);

                foreach ($classStudents as $student) {
                    $task->students()->syncWithoutDetaching([
                        $student->id => ['status' => 'Pending'],
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | SUBMISSIONS
            |--------------------------------------------------------------------------
            */

            $submissionExamples = [
                [
                    'task' => $tasks[0],
                    'student' => $students[0],
                    'status' => 'Submitted',
                    'grade' => null,
                    'feedback' => null,
                ],
                [
                    'task' => $tasks[0],
                    'student' => $students[1],
                    'status' => 'Reviewed',
                    'grade' => 92,
                    'feedback' => 'Excellent work. Your solutions are clear.',
                ],
                [
                    'task' => $tasks[2],
                    'student' => $students[3],
                    'status' => 'Submitted',
                    'grade' => null,
                    'feedback' => null,
                ],
            ];

            foreach ($submissionExamples as $example) {
                $reviewed = $example['status'] === 'Reviewed';

                TaskSubmission::updateOrCreate(
                    [
                        'task_id' => $example['task']->id,
                        'student_id' => $example['student']->id,
                    ],
                    [
                        'answer' => 'This is demonstration submission content.',
                        'status' => $example['status'],
                        'grade' => $example['grade'],
                        'feedback' => $example['feedback'],
                        'submitted_at' => now()->subHours(2),
                        'reviewed_at' => $reviewed ? now()->subHour() : null,
                    ]
                );
            }

            /*
            |--------------------------------------------------------------------------
            | ANNOUNCEMENTS
            |--------------------------------------------------------------------------
            */

            foreach ([
                [
                    'title' => 'Welcome to the Student Task System',
                    'content' => 'Welcome! Check your dashboard regularly for assignments, deadlines, and school announcements.',
                    'category' => 'General',
                    'is_pinned' => true,
                ],
                [
                    'title' => 'Upcoming Assignment Deadlines',
                    'content' => 'Please review your pending tasks and submit your work before each deadline.',
                    'category' => 'Academic',
                    'is_pinned' => false,
                ],
                [
                    'title' => 'Check Your Notifications',
                    'content' => 'Visit the notifications page regularly to stay informed about tasks and important updates.',
                    'category' => 'Reminder',
                    'is_pinned' => false,
                ],
            ] as $data) {
                Announcement::updateOrCreate(
                    ['title' => $data['title']],
                    $data
                );
            }

            $this->command?->info('Demo accounts and sample data created successfully.');
            $this->command?->info('Demo password for all accounts: DemoStudent123! (students only).');
            $this->command?->info('Teacher password: DemoTeacher123!');
            $this->command?->info('Admin password: DemoAdmin123!');
        });
    }
}