# VUFYPMS — Complete Implementation Plan
# Virtual University Final Year Project Management System

---

## 1. EXECUTIVE SUMMARY

| Item | Detail |
|------|--------|
| **Project Name** | Virtual University Final Year Project Management System (VUFYPMS) |
| **Framework** | Laravel 10.x (PHP 8.1+) |
| **Database** | MySQL 8.0 (via XAMPP) |
| **Frontend** | Blade + Bootstrap 5.3 + jQuery 3.7 |
| **Architecture** | MVC (Model-View-Controller) |
| **Auth Strategy** | Session-based with Role Middleware |
| **User Roles** | Admin, Supervisor, Student, Guest |
| **Total Modules** | 15 Functional Modules |
| **Total DB Tables** | 16 Tables |
| **Total Routes** | ~70 Named Routes |
| **Total Controllers** | 25 Controllers |
| **Total Views** | 55+ Blade Templates |

---

## 2. SYSTEM ARCHITECTURE

```
┌─────────────────────────────────────────────────────────────────┐
│                     VUFYPMS Architecture                        │
├─────────────────────────────────────────────────────────────────┤
│  Browser (Client Layer)                                         │
│  Bootstrap 5 + jQuery + AJAX + DataTables + Chart.js           │
├─────────────────────────────────────────────────────────────────┤
│  Web Server Layer                                               │
│  Apache (XAMPP) + PHP 8.1+                                     │
├─────────────────────────────────────────────────────────────────┤
│  Application Layer (Laravel 10)                                 │
│  ┌──────────┐ ┌──────────────┐ ┌────────────┐ ┌────────────┐  │
│  │  Routes  │ │  Middleware  │ │Controllers │ │  Services  │  │
│  └──────────┘ └──────────────┘ └────────────┘ └────────────┘  │
├─────────────────────────────────────────────────────────────────┤
│  Model Layer (Eloquent ORM)                                     │
│  16 Models with Relationships                                   │
├─────────────────────────────────────────────────────────────────┤
│  Data Layer                                                     │
│  MySQL 8.0 — 16 Tables with Foreign Keys + Indexes            │
└─────────────────────────────────────────────────────────────────┘
```

---

## 3. USER ROLES & PERMISSIONS MATRIX

| Feature | Guest | Student | Supervisor | Admin |
|---------|-------|---------|------------|-------|
| View Guidelines | ✓ | ✓ | ✓ | ✓ |
| View Announcements (public) | ✓ | ✓ | ✓ | ✓ |
| Search Published Projects | ✓ | ✓ | ✓ | ✓ |
| Register / Login | ✓ | ✓ | ✓ | ✓ |
| Student Dashboard | ✗ | ✓ | ✗ | ✗ |
| Create/Manage Team | ✗ | ✓ | ✗ | ✗ |
| Submit Proposal | ✗ | ✓ | ✗ | ✗ |
| Upload Documents | ✗ | ✓ | ✗ | ✗ |
| View Milestones | ✗ | ✓ | ✓ | ✓ |
| Send Messages (to supervisor) | ✗ | ✓ | ✓ | ✗ |
| Supervisor Dashboard | ✗ | ✗ | ✓ | ✗ |
| Review Proposals | ✗ | ✗ | ✓ | ✓ |
| Enter Evaluations | ✗ | ✗ | ✓ | ✓ |
| Schedule Meetings | ✗ | ✗ | ✓ | ✓ |
| Admin Dashboard | ✗ | ✗ | ✗ | ✓ |
| User Management | ✗ | ✗ | ✗ | ✓ |
| Domain Management | ✗ | ✗ | ✗ | ✓ |
| Semester/Milestone Mgmt | ✗ | ✗ | ✗ | ✓ |
| Assign Supervisors | ✗ | ✗ | ✗ | ✓ |
| Reports & Analytics | ✗ | ✗ | ✗ | ✓ |
| Archive Management | ✗ | ✗ | ✗ | ✓ |

---

## 4. DATABASE SCHEMA (16 Tables)

### 4.1 Table: `users`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT |
| name | VARCHAR(255) | NOT NULL |
| email | VARCHAR(255) | UNIQUE, NOT NULL |
| password | VARCHAR(255) | NOT NULL |
| role | ENUM('admin','supervisor','student') | DEFAULT 'student' |
| vu_id | VARCHAR(50) | NULLABLE, UNIQUE |
| phone | VARCHAR(20) | NULLABLE |
| profile_photo | VARCHAR(255) | NULLABLE |
| department | VARCHAR(100) | NULLABLE |
| designation | VARCHAR(100) | NULLABLE (supervisors) |
| is_active | TINYINT(1) | DEFAULT 1 |
| email_verified_at | TIMESTAMP | NULLABLE |
| remember_token | VARCHAR(100) | NULLABLE |
| created_at / updated_at | TIMESTAMPS | |

### 4.2 Table: `project_domains`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| name | VARCHAR(100) | NOT NULL |
| description | TEXT | NULLABLE |
| is_active | TINYINT(1) | DEFAULT 1 |
| created_at / updated_at | TIMESTAMPS | |

### 4.3 Table: `semesters`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| name | VARCHAR(100) | NOT NULL |
| start_date | DATE | NOT NULL |
| end_date | DATE | NOT NULL |
| proposal_start | DATE | NULLABLE |
| proposal_end | DATE | NULLABLE |
| is_active | TINYINT(1) | DEFAULT 0 |
| created_at / updated_at | TIMESTAMPS | |

### 4.4 Table: `milestones`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| semester_id | FK → semesters | NOT NULL |
| name | VARCHAR(200) | NOT NULL |
| description | TEXT | NULLABLE |
| due_date | DATE | NOT NULL |
| order_index | INT | DEFAULT 0 |
| created_at / updated_at | TIMESTAMPS | |

### 4.5 Table: `teams`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| name | VARCHAR(200) | NOT NULL |
| project_title | VARCHAR(500) | NULLABLE |
| semester_id | FK → semesters | NOT NULL |
| supervisor_id | FK → users | NULLABLE |
| status | ENUM('forming','active','under_review','approved','completed','archived') | DEFAULT 'forming' |
| created_by | FK → users | NOT NULL |
| created_at / updated_at | TIMESTAMPS | |

### 4.6 Table: `team_members`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| team_id | FK → teams | NOT NULL |
| student_id | FK → users | NOT NULL |
| role | ENUM('leader','member') | DEFAULT 'member' |
| status | ENUM('active','left') | DEFAULT 'active' |
| joined_at | TIMESTAMP | NULLABLE |
| created_at / updated_at | TIMESTAMPS | |

### 4.7 Table: `team_invitations`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| team_id | FK → teams | NOT NULL |
| invited_student_id | FK → users | NOT NULL |
| invited_by | FK → users | NOT NULL |
| status | ENUM('pending','accepted','rejected','cancelled') | DEFAULT 'pending' |
| created_at / updated_at | TIMESTAMPS | |

### 4.8 Table: `proposals`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| team_id | FK → teams | NOT NULL, UNIQUE |
| title | VARCHAR(500) | NOT NULL |
| abstract | TEXT | NOT NULL |
| domain_id | FK → project_domains | NOT NULL |
| tools_technologies | TEXT | NOT NULL |
| objectives | TEXT | NULLABLE |
| status | ENUM('draft','submitted','under_review','revision_required','approved','rejected') | DEFAULT 'draft' |
| revision_notes | TEXT | NULLABLE |
| submitted_at | TIMESTAMP | NULLABLE |
| reviewed_at | TIMESTAMP | NULLABLE |
| reviewed_by | FK → users | NULLABLE |
| created_at / updated_at | TIMESTAMPS | |

### 4.9 Table: `documents`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| team_id | FK → teams | NOT NULL |
| uploaded_by | FK → users | NOT NULL |
| type | ENUM('proposal','srs','design','progress_report','final_report','presentation','other') | NOT NULL |
| original_name | VARCHAR(255) | NOT NULL |
| file_path | VARCHAR(500) | NOT NULL |
| file_size | BIGINT | NULLABLE |
| version | INT | DEFAULT 1 |
| status | ENUM('active','superseded') | DEFAULT 'active' |
| notes | TEXT | NULLABLE |
| created_at / updated_at | TIMESTAMPS | |

### 4.10 Table: `team_milestones`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| team_id | FK → teams | NOT NULL |
| milestone_id | FK → milestones | NOT NULL |
| status | ENUM('pending','in_progress','completed','overdue') | DEFAULT 'pending' |
| completion_notes | TEXT | NULLABLE |
| completed_at | TIMESTAMP | NULLABLE |
| created_at / updated_at | TIMESTAMPS | |

### 4.11 Table: `messages`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| team_id | FK → teams | NOT NULL |
| sender_id | FK → users | NOT NULL |
| receiver_id | FK → users | NOT NULL |
| content | TEXT | NOT NULL |
| is_read | TINYINT(1) | DEFAULT 0 |
| read_at | TIMESTAMP | NULLABLE |
| created_at / updated_at | TIMESTAMPS | |

### 4.12 Table: `meetings`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| team_id | FK → teams | NOT NULL |
| supervisor_id | FK → users | NOT NULL |
| title | VARCHAR(255) | NOT NULL |
| scheduled_at | DATETIME | NOT NULL |
| venue | VARCHAR(255) | NULLABLE |
| meeting_link | VARCHAR(500) | NULLABLE |
| notes | TEXT | NULLABLE |
| status | ENUM('scheduled','completed','cancelled') | DEFAULT 'scheduled' |
| created_at / updated_at | TIMESTAMPS | |

### 4.13 Table: `evaluations`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| team_id | FK → teams | NOT NULL |
| evaluator_id | FK → users | NOT NULL |
| type | ENUM('proposal_defense','progress_review','final_defense') | NOT NULL |
| marks | DECIMAL(5,2) | NULLABLE |
| max_marks | DECIMAL(5,2) | DEFAULT 100 |
| remarks | TEXT | NULLABLE |
| recommendations | TEXT | NULLABLE |
| evaluation_date | DATE | NOT NULL |
| created_at / updated_at | TIMESTAMPS | |

### 4.14 Table: `announcements`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| created_by | FK → users | NOT NULL |
| title | VARCHAR(255) | NOT NULL |
| content | TEXT | NOT NULL |
| type | ENUM('general','deadline','evaluation','schedule') | DEFAULT 'general' |
| is_public | TINYINT(1) | DEFAULT 0 |
| target_role | ENUM('all','student','supervisor') | DEFAULT 'all' |
| published_at | TIMESTAMP | NULLABLE |
| expires_at | TIMESTAMP | NULLABLE |
| created_at / updated_at | TIMESTAMPS | |

### 4.15 Table: `notifications`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| user_id | FK → users | NOT NULL |
| title | VARCHAR(255) | NOT NULL |
| message | TEXT | NOT NULL |
| type | VARCHAR(50) | DEFAULT 'info' |
| link | VARCHAR(500) | NULLABLE |
| is_read | TINYINT(1) | DEFAULT 0 |
| read_at | TIMESTAMP | NULLABLE |
| created_at / updated_at | TIMESTAMPS | |

### 4.16 Table: `presentations`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| team_id | FK → teams | NOT NULL |
| type | ENUM('proposal_defense','progress_review','final_defense') | NOT NULL |
| scheduled_at | DATETIME | NOT NULL |
| venue | VARCHAR(255) | NULLABLE |
| online_link | VARCHAR(500) | NULLABLE |
| panel_info | TEXT | NULLABLE |
| duration_minutes | INT | DEFAULT 30 |
| status | ENUM('scheduled','completed','postponed','cancelled') | DEFAULT 'scheduled' |
| created_at / updated_at | TIMESTAMPS | |

---

## 5. MODULE BREAKDOWN

### Module 1: Authentication
- Custom login/register (no Breeze)
- Session-based auth
- Role-based redirect after login
- Remember me, logout

### Module 2: Public Portal
- Home page with system info
- Public announcements listing
- Search completed/published projects
- FYP guidelines page

### Module 3: Student — Team Formation
- Create new team (auto-assign as leader)
- Search students by VU-ID or email
- Send team invitations
- Accept/reject invitations
- View team members

### Module 4: Student — Proposal Management
- Create/edit proposal (title, abstract, domain, tools, objectives)
- Submit proposal for review
- View review feedback / revision notes
- Resubmit revised proposal

### Module 5: Student — Document Management
- Upload documents (with type classification)
- View all uploaded documents
- Version management (supersede old versions)

### Module 6: Student — Milestone Tracking
- View semester milestones
- Mark milestones as in-progress/completed
- View due dates and overdue status

### Module 7: Student — Communication
- Send messages to supervisor
- View message history (chat-style)
- Unread message notifications

### Module 8: Student — Evaluations
- View evaluation history (marks, remarks)
- View presentation schedule

### Module 9: Supervisor — Dashboard
- Summary: assigned teams, pending proposals, upcoming meetings
- Quick actions panel

### Module 10: Supervisor — Proposal Review
- List all assigned team proposals
- Approve / Request Revision / Reject
- Enter revision notes

### Module 11: Supervisor — Progress Monitoring
- View team milestone status
- View uploaded documents
- View team activity

### Module 12: Supervisor — Meetings & Evaluations
- Create meeting slots for teams
- Enter evaluation marks and remarks
- View evaluation history

### Module 13: Admin — User Management
- CRUD for all user types
- Activate/deactivate accounts
- Filter by role, department

### Module 14: Admin — System Configuration
- Manage project domains
- Manage semesters and deadlines
- Manage milestone definitions
- Assign supervisors to teams
- Manage announcements

### Module 15: Admin — Reports & Analytics
- Proposal approval statistics (Chart.js)
- Project domain distribution (Pie chart)
- Supervisor workload distribution
- Submission status summary
- Archive management

---

## 6. ROUTE STRUCTURE

### Public Routes (no auth)
```
GET  /                        → home
GET  /announcements           → announcements.index
GET  /projects                → projects.search
GET  /guidelines              → guidelines
GET  /login                   → auth.login
POST /login                   → auth.authenticate
GET  /register                → auth.register
POST /register                → auth.store
POST /logout                  → auth.logout
```

### Student Routes (auth + role:student)
```
GET  /student/dashboard
GET  /student/team
POST /student/team
GET  /student/team/invitations
POST /student/team/invitations/{id}/accept
POST /student/team/invitations/{id}/reject
GET  /student/proposal
POST /student/proposal
PUT  /student/proposal/{id}
POST /student/proposal/{id}/submit
GET  /student/documents
POST /student/documents
GET  /student/milestones
POST /student/milestones/{id}/update
GET  /student/messages
POST /student/messages
GET  /student/evaluations
GET  /student/presentations
```

### Supervisor Routes (auth + role:supervisor)
```
GET  /supervisor/dashboard
GET  /supervisor/proposals
POST /supervisor/proposals/{id}/review
GET  /supervisor/teams
GET  /supervisor/teams/{id}
GET  /supervisor/meetings
POST /supervisor/meetings
GET  /supervisor/evaluations
POST /supervisor/evaluations
```

### Admin Routes (auth + role:admin)
```
GET/POST/PUT/DELETE  /admin/users
GET/POST/PUT/DELETE  /admin/domains
GET/POST/PUT/DELETE  /admin/semesters
GET/POST/PUT/DELETE  /admin/milestones
GET/POST/PUT/DELETE  /admin/teams
POST                  /admin/teams/{id}/assign-supervisor
GET/POST/PUT/DELETE  /admin/announcements
GET                  /admin/reports
GET                  /admin/archive
```

---

## 7. SECURITY ARCHITECTURE

- **Authentication**: Laravel session guards with bcrypt password hashing
- **Authorization**: Custom `RoleMiddleware` checking `users.role` column
- **CSRF Protection**: Laravel built-in `VerifyCsrfToken` middleware on all POST/PUT/DELETE
- **Input Validation**: Form Request classes with rules per module
- **File Upload Security**: MIME type validation, size limits (10MB), stored in `storage/app/private`
- **SQL Injection**: Eloquent ORM parameterized queries exclusively
- **XSS Protection**: Blade `{{ }}` auto-escapes all output

---

## 8. FOLDER STRUCTURE

```
vufypms/
├── app/
│   ├── Console/Kernel.php
│   ├── Exceptions/Handler.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/LoginController.php
│   │   │   ├── Auth/RegisterController.php
│   │   │   ├── Public/HomeController.php
│   │   │   ├── Student/DashboardController.php
│   │   │   ├── Student/TeamController.php
│   │   │   ├── Student/ProposalController.php
│   │   │   ├── Student/DocumentController.php
│   │   │   ├── Student/MilestoneController.php
│   │   │   ├── Student/MessageController.php
│   │   │   ├── Student/EvaluationController.php
│   │   │   ├── Supervisor/DashboardController.php
│   │   │   ├── Supervisor/ProposalController.php
│   │   │   ├── Supervisor/TeamController.php
│   │   │   ├── Supervisor/MeetingController.php
│   │   │   ├── Supervisor/EvaluationController.php
│   │   │   ├── Admin/DashboardController.php
│   │   │   ├── Admin/UserController.php
│   │   │   ├── Admin/DomainController.php
│   │   │   ├── Admin/SemesterController.php
│   │   │   ├── Admin/MilestoneController.php
│   │   │   ├── Admin/TeamController.php
│   │   │   ├── Admin/AnnouncementController.php
│   │   │   └── Admin/ReportController.php
│   │   ├── Kernel.php
│   │   └── Middleware/
│   │       ├── RoleMiddleware.php
│   │       └── CheckActive.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Team.php
│   │   ├── TeamMember.php
│   │   ├── TeamInvitation.php
│   │   ├── Proposal.php
│   │   ├── Document.php
│   │   ├── Milestone.php
│   │   ├── TeamMilestone.php
│   │   ├── Message.php
│   │   ├── Meeting.php
│   │   ├── Evaluation.php
│   │   ├── Announcement.php
│   │   ├── Notification.php
│   │   ├── Presentation.php
│   │   ├── ProjectDomain.php
│   │   └── Semester.php
│   └── Providers/
├── bootstrap/app.php
├── config/ (app, auth, database, filesystems, session, view, cache, logging)
├── database/
│   ├── migrations/ (16 migration files)
│   └── seeders/ (6 seeder files)
├── public/index.php
├── resources/views/
│   ├── layouts/ (app, student, supervisor, admin, guest)
│   ├── auth/ (login, register)
│   ├── public/ (home, announcements, projects, guidelines)
│   ├── student/ (dashboard, team, proposal, documents, milestones, messages, evaluations)
│   ├── supervisor/ (dashboard, proposals, teams, meetings, evaluations)
│   └── admin/ (dashboard, users, domains, semesters, teams, announcements, reports, archive)
├── routes/web.php
├── storage/ (framework, logs, app/public)
├── .env.example
├── composer.json
└── artisan
```

---

## 9. TECHNOLOGY STACK REQUIREMENTS

### Server-Side
| Requirement | Version | Purpose |
|-------------|---------|---------|
| PHP | 8.1+ | Laravel runtime |
| Laravel | 10.x | Application framework |
| MySQL | 5.7+ / 8.0 | Database |
| Apache | 2.4+ | Web server |
| Composer | 2.x | PHP dependency manager |

### Client-Side (CDN)
| Library | Version | Purpose |
|---------|---------|---------|
| Bootstrap | 5.3.x | UI framework |
| Bootstrap Icons | 1.11.x | Icons |
| jQuery | 3.7.x | DOM manipulation / AJAX |
| DataTables | 1.13.x | Advanced table features |
| Chart.js | 4.x | Analytics charts |
| SweetAlert2 | 11.x | Alert dialogs |
| Select2 | 4.1.x | Enhanced select dropdowns |

### Development Tools
| Tool | Purpose |
|------|---------|
| XAMPP | Local server environment |
| phpMyAdmin | Database GUI |
| Git / GitHub | Version control |
| VS Code / PHPStorm | Code editor |
| Composer | PHP package manager |
| Node.js + NPM | (optional, for asset compilation) |

---

## 10. DEPLOYMENT GUIDE

### Local Development (XAMPP)
1. Install XAMPP (PHP 8.1+, MySQL 8.0)
2. Install Composer globally
3. Place project in `C:\xampp\htdocs\vufypms\`
4. Configure Apache virtual host OR use `php artisan serve`
5. Create MySQL database `vufypms`
6. Copy `.env.example` → `.env`, configure DB credentials
7. Run `composer install`
8. Run `php artisan key:generate`
9. Run `php artisan migrate --seed`
10. Run `php artisan storage:link`
11. Access at `http://localhost:8000` or `http://localhost/vufypms/public`

### Default Credentials (after seeding)
| Role | Email | Password |
|------|-------|---------|
| Admin | admin@vu.edu.pk | password |
| Supervisor | supervisor@vu.edu.pk | password |
| Student | student@vu.edu.pk | password |

---

## 11. DEVELOPMENT TIMELINE (Estimated)

| Phase | Tasks | Duration |
|-------|-------|----------|
| Phase 1 | DB Design, Migrations, Models, Seeders | Week 1-2 |
| Phase 2 | Auth System, Layouts, Public Pages | Week 3 |
| Phase 3 | Student Module (Team, Proposal, Docs) | Week 4-5 |
| Phase 4 | Supervisor Module | Week 6 |
| Phase 5 | Admin Module (Users, Config, Assign) | Week 7 |
| Phase 6 | Reports, Analytics, Archive | Week 8 |
| Phase 7 | Testing, Bug Fixes, Polish | Week 9-10 |

---

*Generated by: VUFYPMS Architect — May 2026*
