<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\TeamController as StudentTeam;
use App\Http\Controllers\Student\ProposalController as StudentProposal;
use App\Http\Controllers\Student\DocumentController as StudentDocument;
use App\Http\Controllers\Student\MilestoneController as StudentMilestone;
use App\Http\Controllers\Student\MessageController as StudentMessage;
use App\Http\Controllers\Student\EvaluationController as StudentEvaluation;
use App\Http\Controllers\Supervisor\DashboardController as SupervisorDashboard;
use App\Http\Controllers\Supervisor\ProposalController as SupervisorProposal;
use App\Http\Controllers\Supervisor\TeamController as SupervisorTeam;
use App\Http\Controllers\Supervisor\MeetingController as SupervisorMeeting;
use App\Http\Controllers\Supervisor\EvaluationController as SupervisorEvaluation;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\DomainController as AdminDomain;
use App\Http\Controllers\Admin\SemesterController as AdminSemester;
use App\Http\Controllers\Admin\MilestoneController as AdminMilestone;
use App\Http\Controllers\Admin\TeamController as AdminTeam;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncement;
use App\Http\Controllers\Admin\ReportController as AdminReport;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/announcements', [HomeController::class, 'announcements'])->name('announcements');
Route::get('/projects', [HomeController::class, 'projects'])->name('projects.search');
Route::get('/guidelines', [HomeController::class, 'guidelines'])->name('guidelines');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Dashboard Redirect
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = auth()->user();
    return match ($user->role) {
        'admin'      => redirect()->route('admin.dashboard'),
        'supervisor' => redirect()->route('supervisor.dashboard'),
        default      => redirect()->route('student.dashboard'),
    };
})->middleware(['auth', 'active'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->middleware(['auth', 'active', 'role:student'])->group(function () {

    Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');

    // Team
    Route::get('/team', [StudentTeam::class, 'index'])->name('team.index');
    Route::post('/team', [StudentTeam::class, 'store'])->name('team.store');
    Route::post('/team/invite', [StudentTeam::class, 'invite'])->name('team.invite');
    Route::get('/team/invitations', [StudentTeam::class, 'invitations'])->name('team.invitations');
    Route::post('/team/invitations/{id}/accept', [StudentTeam::class, 'acceptInvitation'])->name('team.invitations.accept');
    Route::post('/team/invitations/{id}/reject', [StudentTeam::class, 'rejectInvitation'])->name('team.invitations.reject');
    Route::get('/team/search-students', [StudentTeam::class, 'searchStudents'])->name('team.search-students');

    // Proposal
    Route::get('/proposal', [StudentProposal::class, 'index'])->name('proposal.index');
    Route::post('/proposal', [StudentProposal::class, 'store'])->name('proposal.store');
    Route::put('/proposal/{id}', [StudentProposal::class, 'update'])->name('proposal.update');
    Route::post('/proposal/{id}/submit', [StudentProposal::class, 'submit'])->name('proposal.submit');

    // Documents
    Route::get('/documents', [StudentDocument::class, 'index'])->name('documents.index');
    Route::post('/documents', [StudentDocument::class, 'store'])->name('documents.store');
    Route::get('/documents/{id}/download', [StudentDocument::class, 'download'])->name('documents.download');
    Route::delete('/documents/{id}', [StudentDocument::class, 'destroy'])->name('documents.destroy');

    // Milestones
    Route::get('/milestones', [StudentMilestone::class, 'index'])->name('milestones.index');
    Route::post('/milestones/{id}/update', [StudentMilestone::class, 'update'])->name('milestones.update');

    // Messages
    Route::get('/messages', [StudentMessage::class, 'index'])->name('messages.index');
    Route::post('/messages', [StudentMessage::class, 'store'])->name('messages.store');
    Route::get('/messages/unread-count', [StudentMessage::class, 'unreadCount'])->name('messages.unread-count');

    // Evaluations & Presentations
    Route::get('/evaluations', [StudentEvaluation::class, 'index'])->name('evaluations.index');
    Route::get('/presentations', [StudentEvaluation::class, 'presentations'])->name('presentations.index');

    // Notifications
    Route::get('/notifications', [StudentDashboard::class, 'notifications'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [StudentDashboard::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [StudentDashboard::class, 'markAllNotificationsRead'])->name('notifications.read-all');
});

/*
|--------------------------------------------------------------------------
| Supervisor Routes
|--------------------------------------------------------------------------
*/
Route::prefix('supervisor')->name('supervisor.')->middleware(['auth', 'active', 'role:supervisor'])->group(function () {

    Route::get('/dashboard', [SupervisorDashboard::class, 'index'])->name('dashboard');

    // Proposals
    Route::get('/proposals', [SupervisorProposal::class, 'index'])->name('proposals.index');
    Route::get('/proposals/{id}', [SupervisorProposal::class, 'show'])->name('proposals.show');
    Route::post('/proposals/{id}/review', [SupervisorProposal::class, 'review'])->name('proposals.review');

    // Teams
    Route::get('/teams', [SupervisorTeam::class, 'index'])->name('teams.index');
    Route::get('/teams/{id}', [SupervisorTeam::class, 'show'])->name('teams.show');
    Route::get('/teams/{id}/documents', [SupervisorTeam::class, 'documents'])->name('teams.documents');
    Route::get('/teams/{id}/documents/{docId}/download', [SupervisorTeam::class, 'downloadDocument'])->name('teams.documents.download');

    // Meetings
    Route::get('/meetings', [SupervisorMeeting::class, 'index'])->name('meetings.index');
    Route::post('/meetings', [SupervisorMeeting::class, 'store'])->name('meetings.store');
    Route::put('/meetings/{id}', [SupervisorMeeting::class, 'update'])->name('meetings.update');
    Route::delete('/meetings/{id}', [SupervisorMeeting::class, 'destroy'])->name('meetings.destroy');

    // Evaluations
    Route::get('/evaluations', [SupervisorEvaluation::class, 'index'])->name('evaluations.index');
    Route::post('/evaluations', [SupervisorEvaluation::class, 'store'])->name('evaluations.store');
    Route::get('/evaluations/{id}/edit', [SupervisorEvaluation::class, 'edit'])->name('evaluations.edit');
    Route::put('/evaluations/{id}', [SupervisorEvaluation::class, 'update'])->name('evaluations.update');

    // Messages
    Route::get('/messages/{teamId}', [SupervisorDashboard::class, 'messages'])->name('messages.index');
    Route::post('/messages', [SupervisorDashboard::class, 'sendMessage'])->name('messages.store');

    // Notifications
    Route::get('/notifications', [SupervisorDashboard::class, 'notifications'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [SupervisorDashboard::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [SupervisorDashboard::class, 'markAllNotificationsRead'])->name('notifications.read-all');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'active', 'role:admin'])->group(function () {

    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [AdminUser::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUser::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUser::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [AdminUser::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [AdminUser::class, 'update'])->name('users.update');
    Route::post('/users/{id}/toggle-active', [AdminUser::class, 'toggleActive'])->name('users.toggle-active');
    Route::delete('/users/{id}', [AdminUser::class, 'destroy'])->name('users.destroy');

    // Project Domains
    Route::get('/domains', [AdminDomain::class, 'index'])->name('domains.index');
    Route::post('/domains', [AdminDomain::class, 'store'])->name('domains.store');
    Route::put('/domains/{id}', [AdminDomain::class, 'update'])->name('domains.update');
    Route::delete('/domains/{id}', [AdminDomain::class, 'destroy'])->name('domains.destroy');

    // Semesters
    Route::get('/semesters', [AdminSemester::class, 'index'])->name('semesters.index');
    Route::post('/semesters', [AdminSemester::class, 'store'])->name('semesters.store');
    Route::put('/semesters/{id}', [AdminSemester::class, 'update'])->name('semesters.update');
    Route::post('/semesters/{id}/activate', [AdminSemester::class, 'activate'])->name('semesters.activate');
    Route::delete('/semesters/{id}', [AdminSemester::class, 'destroy'])->name('semesters.destroy');

    // Milestones
    Route::get('/milestones', [AdminMilestone::class, 'index'])->name('milestones.index');
    Route::post('/milestones', [AdminMilestone::class, 'store'])->name('milestones.store');
    Route::put('/milestones/{id}', [AdminMilestone::class, 'update'])->name('milestones.update');
    Route::delete('/milestones/{id}', [AdminMilestone::class, 'destroy'])->name('milestones.destroy');

    // Teams & Supervisor Assignment
    Route::get('/teams', [AdminTeam::class, 'index'])->name('teams.index');
    Route::get('/teams/{id}', [AdminTeam::class, 'show'])->name('teams.show');
    Route::post('/teams/{id}/assign-supervisor', [AdminTeam::class, 'assignSupervisor'])->name('teams.assign-supervisor');
    Route::post('/teams/{id}/archive', [AdminTeam::class, 'archive'])->name('teams.archive');

    // Announcements
    Route::get('/announcements', [AdminAnnouncement::class, 'index'])->name('announcements.index');
    Route::get('/announcements/create', [AdminAnnouncement::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [AdminAnnouncement::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{id}/edit', [AdminAnnouncement::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/{id}', [AdminAnnouncement::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{id}', [AdminAnnouncement::class, 'destroy'])->name('announcements.destroy');

    // Reports & Archive
    Route::get('/reports', [AdminReport::class, 'index'])->name('reports.index');
    Route::get('/archive', [AdminReport::class, 'archive'])->name('archive.index');

    // Presentations
    Route::post('/presentations', [AdminTeam::class, 'schedulePresentation'])->name('presentations.store');
    Route::put('/presentations/{id}', [AdminTeam::class, 'updatePresentation'])->name('presentations.update');
});
