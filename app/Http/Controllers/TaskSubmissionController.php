<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TaskSubmissionController extends Controller
{
    /**
     * Student submits an assigned task.
     */
    public function store(Request $request, Task $task)
    {
        $user = $request->user();

        if (!$user->isStudent()) {
            abort(403);
        }

        // Verify that the task belongs to this student.
        $assigned = $task->students()
            ->where('users.id', $user->id)
            ->exists();

        if (!$assigned) {
            abort(403, 'This task is not assigned to you.');
        }

        $existing = TaskSubmission::where('task_id', $task->id)
            ->where('student_id', $user->id)
            ->first();

        // Reviewed submissions cannot be modified.
        if ($existing && $existing->status === 'Reviewed') {
            return back()->withErrors([
                'submission' => 'This submission has already been reviewed.',
            ]);
        }

        $validated = $request->validate([
            'answer' => ['nullable', 'string', 'max:20000'],
            'file' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,txt,png,jpg,jpeg',
                'max:10240',
            ],
        ]);

        $hasAnswer = filled(trim($validated['answer'] ?? ''));
        $hasNewFile = $request->hasFile('file');
        $hasExistingFile = $existing && filled($existing->file_path);

        if (!$hasAnswer && !$hasNewFile && !$hasExistingFile) {
            throw ValidationException::withMessages([
                'answer' => 'Please provide a written answer or upload a file.',
            ]);
        }

        $newFilePath = null;

        if ($hasNewFile) {
            // Store privately rather than on the public disk.
            $newFilePath = $request->file('file')->store(
                'task-submissions',
                'local'
            );
        }

        try {
            DB::transaction(function () use (
                $task,
                $user,
                $validated,
                $existing,
                $newFilePath
            ) {
                $submission = TaskSubmission::updateOrCreate(
                    [
                        'task_id' => $task->id,
                        'student_id' => $user->id,
                    ],
                    [
                        'answer' => $validated['answer'] ?? null,
                        'file_path' => $newFilePath
                            ?? $existing?->file_path,
                        'status' => 'Submitted',
                        'grade' => null,
                        'feedback' => null,
                        'submitted_at' => now(),
                        'reviewed_at' => null,
                    ]
                );

            });
        } catch (\Throwable $exception) {
            // Clean up the new upload if database saving fails.
            if ($newFilePath) {
                Storage::disk('local')->delete($newFilePath);
            }

            throw $exception;
        }

        // Delete the previous file only after successful replacement.
        if (
            $newFilePath &&
            $existing?->file_path &&
            $existing->file_path !== $newFilePath
        ) {
            Storage::disk('local')->delete($existing->file_path);
        }

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Your task has been submitted successfully.');
    }

    /**
     * Display submissions for a task.
     */
    public function index(Request $request, Task $task)
    {
        $user = $request->user();

        if (!$user->isAdmin() && !(
            $user->isTeacher() &&
            $task->created_by === $user->id
        )) {
            abort(403);
        }

        $task->load('schoolClass');

        $submissions = $task->submissions()
            ->with('student')
            ->latest('submitted_at')
            ->paginate(15);

        return view(
            'tasks.submissions.index',
            compact('task', 'submissions')
        );
    }

    /**
     * Review and grade a student submission.
     */
    public function review(
        Request $request,
        TaskSubmission $submission
    ) {
        $user = $request->user();

        if (!$user->isAdmin() && !(
            $user->isTeacher() &&
            $submission->task->created_by === $user->id
        )) {
            abort(403);
        }

        $validated = $request->validate([
            'grade' => [
                'required',
                'numeric',
                'between:0,100',
            ],
            'feedback' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        DB::transaction(function () use ($submission, $validated) {
            $submission->update([
                'grade' => $validated['grade'],
                'feedback' => $validated['feedback'] ?? null,
                'status' => 'Reviewed',
                'reviewed_at' => now(),
            ]);

            // Mark the student's task as completed after review.
            $submission->task->students()->updateExistingPivot(
                $submission->student_id,
                [
                    'status' => 'Completed',
                    'completed_at' => now(),
                ]
            );
        });

        return back()->with(
            'success',
            'Submission reviewed successfully.'
        );
    }

    /**
     * Download a submission attachment securely.
     */
    public function download(
        Request $request,
        TaskSubmission $submission
    ) {
        $user = $request->user();

        $isOwner = $user->isStudent() &&
            $submission->student_id === $user->id;

        $canReview = $user->isAdmin() || (
            $user->isTeacher() &&
            $submission->task->created_by === $user->id
        );

        if (!$isOwner && !$canReview) {
            abort(403);
        }

        if (
            !$submission->file_path ||
            !Storage::disk('local')->exists(
                $submission->file_path
            )
        ) {
            abort(404, 'Submission file not found.');
        }

        return Storage::disk('local')->download(
            $submission->file_path
        );
    }
}