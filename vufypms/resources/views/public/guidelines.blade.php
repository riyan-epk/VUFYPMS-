@extends('layouts.guest')
@section('title', 'FYP Guidelines')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <h3 class="fw-bold mb-1"><i class="bi bi-book-fill text-primary me-2"></i>FYP Guidelines</h3>
            <p class="text-muted mb-4">Official rules and procedures for the Virtual University Final Year Project program.</p>

            <div class="accordion" id="guidelinesAccordion">
                <div class="accordion-item border-0 mb-2 shadow-sm">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#g1">
                            <i class="bi bi-people me-2 text-primary"></i>1. Team Formation Rules
                        </button>
                    </h2>
                    <div id="g1" class="accordion-collapse collapse show" data-bs-parent="#guidelinesAccordion">
                        <div class="accordion-body text-muted">
                            <ul>
                                <li>Each FYP team must consist of <strong>2 to 4 students</strong>.</li>
                                <li>All team members must be registered students of the current active semester.</li>
                                <li>One student is designated as the <strong>Team Leader</strong> responsible for proposal submission.</li>
                                <li>A student can only be a member of <strong>one team</strong> per semester.</li>
                                <li>Teams must be formed within the team formation deadline announced by the department.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item border-0 mb-2 shadow-sm">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#g2">
                            <i class="bi bi-file-earmark-text me-2 text-success"></i>2. Proposal Submission
                        </button>
                    </h2>
                    <div id="g2" class="accordion-collapse collapse" data-bs-parent="#guidelinesAccordion">
                        <div class="accordion-body text-muted">
                            <ul>
                                <li>Proposals must be submitted within the <strong>proposal submission window</strong> set by the administrator.</li>
                                <li>Each proposal must include: Project Title, Abstract (min 100 words), Domain, Tools/Technologies, and Objectives.</li>
                                <li>Proposals are reviewed by the <strong>assigned supervisor</strong>. A supervisor may approve, request revision, or reject.</li>
                                <li>A maximum of <strong>3 revision cycles</strong> is allowed before rejection.</li>
                                <li>Approved proposals cannot be modified without supervisor permission.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item border-0 mb-2 shadow-sm">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#g3">
                            <i class="bi bi-folder2 me-2 text-warning"></i>3. Document Submission Policy
                        </button>
                    </h2>
                    <div id="g3" class="accordion-collapse collapse" data-bs-parent="#guidelinesAccordion">
                        <div class="accordion-body text-muted">
                            <ul>
                                <li>Required documents: Proposal Doc, SRS, Design Document, Progress Report(s), Final Report, and Presentation.</li>
                                <li>Accepted file formats: <strong>PDF, DOC, DOCX, PPT, PPTX, ZIP, TXT</strong>.</li>
                                <li>Maximum file size: <strong>10 MB</strong> per document.</li>
                                <li>All documents are versioned. Uploading a new version supersedes the previous one.</li>
                                <li>Final Report must be submitted <strong>at least 1 week before</strong> the final defense date.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item border-0 mb-2 shadow-sm">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#g4">
                            <i class="bi bi-award me-2 text-danger"></i>4. Evaluation Criteria
                        </button>
                    </h2>
                    <div id="g4" class="accordion-collapse collapse" data-bs-parent="#guidelinesAccordion">
                        <div class="accordion-body text-muted">
                            <table class="table table-sm">
                                <thead><tr><th>Evaluation Type</th><th>Marks</th><th>Timing</th></tr></thead>
                                <tbody>
                                    <tr><td>Proposal Defense</td><td>20</td><td>After proposal approval</td></tr>
                                    <tr><td>Progress Review (Mid)</td><td>30</td><td>Mid-semester</td></tr>
                                    <tr><td>Final Defense</td><td>50</td><td>End of semester</td></tr>
                                    <tr><td><strong>Total</strong></td><td><strong>100</strong></td><td></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="accordion-item border-0 mb-2 shadow-sm">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#g5">
                            <i class="bi bi-calendar-check me-2 text-info"></i>5. Important Policies
                        </button>
                    </h2>
                    <div id="g5" class="accordion-collapse collapse" data-bs-parent="#guidelinesAccordion">
                        <div class="accordion-body text-muted">
                            <ul>
                                <li><strong>Plagiarism:</strong> Any form of plagiarism will result in immediate disqualification.</li>
                                <li><strong>Attendance:</strong> Teams must attend all scheduled meetings with their supervisor.</li>
                                <li><strong>Communication:</strong> All official communication must be through the VUFYPMS portal.</li>
                                <li><strong>Integrity:</strong> All submitted work must be original and developed by the team members.</li>
                                <li><strong>Deadline:</strong> Late submissions will be penalized as per the department policy.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info mt-4">
                <i class="bi bi-info-circle-fill me-2"></i>
                For any queries, please contact your supervisor through the VUFYPMS messaging module after logging in.
                <a href="{{ route('login') }}" class="alert-link ms-1">Login here</a>
            </div>
        </div>
    </div>
</div>
@endsection
