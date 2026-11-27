<!-- Start Test Instructions Modal -->
<div class="modal fade" id="instructionsModal" tabindex="-1" role="dialog" aria-labelledby="modal-paper-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered start-test-modal-dialog" role="document">
        <div class="modal-content border-0 shadow-lg start-test-modal-content">
            <form id="modal-start-form" method="POST" action="">
                @csrf
                <div class="start-test-modal-wrapper p-3 p-md-4">

                    <!-- Modal Header -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center min-width-0 pr-3">
                            <div class="start-header-icon-badge mr-3 flex-shrink-0" id="modal-header-subject-icon">
                                <!-- Default Maths (+ - × ÷) SVG Icon -->
                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.5 4.5V10.5M4.5 7.5H10.5" stroke="white" stroke-width="2.3" stroke-linecap="round"/>
                                    <path d="M17.5 7.5H23.5" stroke="white" stroke-width="2.3" stroke-linecap="round"/>
                                    <path d="M5 17.5L10 22.5M10 17.5L5 22.5" stroke="white" stroke-width="2.3" stroke-linecap="round"/>
                                    <path d="M17.5 20H23.5" stroke="white" stroke-width="2.3" stroke-linecap="round"/>
                                    <circle cx="20.5" cy="17.2" r="1.15" fill="white"/>
                                    <circle cx="20.5" cy="22.8" r="1.15" fill="white"/>
                                </svg>
                            </div>
                            <div class="min-width-0">
                                <h2 class="start-modal-title mb-0 text-truncate" id="modal-paper-title">Maths Practice Test</h2>
                                <p class="start-modal-subtitle mb-0">Please read the instructions carefully before you begin.</p>
                            </div>
                        </div>
                        <button type="button" class="start-modal-close-btn flex-shrink-0" data-dismiss="modal" aria-label="Close">
                            <i class="feather icon-x"></i>
                        </button>
                    </div>

                    <!-- 5-Stat Horizontal Metadata Summary Bar -->
                    <div class="start-meta-bar mb-3">
                        <div class="start-meta-grid">
                            <!-- 1. Questions -->
                            <div class="start-meta-item">
                                <div class="start-meta-icon-circle meta-icon-blue">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="4" y="2" width="16" height="20" rx="2"/>
                                        <line x1="8" y1="6" x2="16" y2="6"/>
                                        <line x1="16" y1="14" x2="16" y2="18"/>
                                        <path d="M16 10h.01"/><path d="M12 10h.01"/><path d="M8 10h.01"/>
                                        <path d="M12 14h.01"/><path d="M8 14h.01"/><path d="M12 18h.01"/><path d="M8 18h.01"/>
                                    </svg>
                                </div>
                                <div class="start-meta-text">
                                    <span class="start-meta-label">Questions</span>
                                    <span class="start-meta-value text-meta-blue" id="modal-paper-questions">0</span>
                                </div>
                            </div>

                            <!-- 2. Duration -->
                            <div class="start-meta-item">
                                <div class="start-meta-icon-circle meta-icon-amber">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="9"/>
                                        <polyline points="12 7 12 12 15.5 14"/>
                                    </svg>
                                </div>
                                <div class="start-meta-text">
                                    <span class="start-meta-label">Duration</span>
                                    <span class="start-meta-value text-meta-amber" id="modal-paper-time">0 mins</span>
                                </div>
                            </div>

                            <!-- 3. Type -->
                            <div class="start-meta-item">
                                <div class="start-meta-icon-circle meta-icon-purple">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                        <polyline points="10 9 9 9 8 9"/>
                                    </svg>
                                </div>
                                <div class="start-meta-text">
                                    <span class="start-meta-label">Type</span>
                                    <span class="start-meta-value text-meta-purple" id="modal-paper-type">Direct Answer</span>
                                </div>
                            </div>

                            <!-- 4. Marks -->
                            <div class="start-meta-item">
                                <div class="start-meta-icon-circle meta-icon-green">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="white">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                    </svg>
                                </div>
                                <div class="start-meta-text">
                                    <span class="start-meta-label">Marks</span>
                                    <span class="start-meta-value text-meta-green" id="modal-paper-marks">0</span>
                                </div>
                            </div>

                            <!-- 5. Subject -->
                            <div class="start-meta-item start-meta-item-last">
                                <div class="start-meta-icon-circle meta-icon-rose">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                                    </svg>
                                </div>
                                <div class="start-meta-text">
                                    <span class="start-meta-label">Subject</span>
                                    <span class="start-meta-value text-meta-rose" id="modal-paper-subject">Maths</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main 2-Column Content -->
                    <div class="row">
                        <!-- Left Card: Numbered Instructions -->
                        <div class="col-lg-7 col-12 mb-3 mb-lg-0">
                            <div class="start-panel-card h-100">
                                <h4 class="start-panel-heading mb-3">Instructions</h4>

                                <!-- Optional Teacher / Paper Specific Instruction Note -->
                                <div id="modal-paper-instruction-box" class="paper-custom-instruction-box mb-3 d-none">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="feather icon-file-text text-primary mr-1" style="font-size: 0.9rem;"></i>
                                        <span class="font-weight-bold text-primary" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.4px;">Paper Specific Note</span>
                                    </div>
                                    <div id="modal-paper-instruction" class="paper-custom-instruction-content"></div>
                                </div>

                                <!-- Standard 7-Step Instructions List -->
                                <div class="instructions-numbered-list">
                                    <div class="instruction-step-row">
                                        <div class="instruction-step-num">1</div>
                                        <div class="instruction-step-body">
                                            <div class="instruction-step-title">Read each question carefully before answering.</div>
                                            <div class="instruction-step-desc">Take your time to understand what is being asked.</div>
                                        </div>
                                    </div>

                                    <div class="instruction-step-row">
                                        <div class="instruction-step-num">2</div>
                                        <div class="instruction-step-body">
                                            <div class="instruction-step-title" id="modal-instr-step2-title">Type your final answer clearly in the answer box.</div>
                                            <div class="instruction-step-desc" id="modal-instr-step2-desc">Make sure your answer is visible and in the correct format.</div>
                                        </div>
                                    </div>

                                    <div class="instruction-step-row">
                                        <div class="instruction-step-num">3</div>
                                        <div class="instruction-step-body">
                                            <div class="instruction-step-title">Do not refresh or close the page during the test.</div>
                                            <div class="instruction-step-desc">This may cause you to lose your progress.</div>
                                        </div>
                                    </div>

                                    <div class="instruction-step-row">
                                        <div class="instruction-step-num">4</div>
                                        <div class="instruction-step-body">
                                            <div class="instruction-step-title">You can move between questions using the question navigator.</div>
                                            <div class="instruction-step-desc">Click on the question numbers to go to a different question.</div>
                                        </div>
                                    </div>

                                    <div class="instruction-step-row">
                                        <div class="instruction-step-num">5</div>
                                        <div class="instruction-step-body">
                                            <div class="instruction-step-title">Use 'Mark for Review' if you want to return to a question later.</div>
                                            <div class="instruction-step-desc">This will help you keep track of questions you want to check again.</div>
                                        </div>
                                    </div>

                                    <div class="instruction-step-row">
                                        <div class="instruction-step-num">6</div>
                                        <div class="instruction-step-body">
                                            <div class="instruction-step-title">Click 'Save &amp; Exit' only if you want to pause the test.</div>
                                            <div class="instruction-step-desc">You can return and continue later if your teacher allows it.</div>
                                        </div>
                                    </div>

                                    <div class="instruction-step-row instruction-step-row-last">
                                        <div class="instruction-step-num">7</div>
                                        <div class="instruction-step-body">
                                            <div class="instruction-step-title">Click 'Submit Test' when you have finished all questions.</div>
                                            <div class="instruction-step-desc">Make sure you have answered all the questions before submitting.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Before You Start + Important Note -->
                        <div class="col-lg-5 col-12 d-flex flex-column">
                            <!-- Before You Start Card -->
                            <div class="start-panel-card flex-grow-1 mb-3">
                                <h4 class="start-panel-heading mb-3">Before You Start</h4>

                                <div class="before-start-checklist">
                                    <label class="checklist-pill-row mb-2">
                                        <input type="checkbox" class="checklist-checkbox start-readiness-check">
                                        <span class="checklist-custom-box">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        </span>
                                        <span class="checklist-item-icon text-checklist-blue">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12.55a11 11 0 0 1 14.08 0"/>
                                                <path d="M1.42 9a16 16 0 0 1 21.16 0"/>
                                                <path d="M8.53 16.11a6 6 0 0 1 6.95 0"/>
                                                <line x1="12" y1="20" x2="12.01" y2="20"/>
                                            </svg>
                                        </span>
                                        <span class="checklist-item-text">Make sure you have a stable internet connection.</span>
                                    </label>

                                    <label class="checklist-pill-row mb-2">
                                        <input type="checkbox" class="checklist-checkbox start-readiness-check">
                                        <span class="checklist-custom-box">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        </span>
                                        <span class="checklist-item-icon text-checklist-amber">
                                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                                <path d="m15 5 4 4"/>
                                            </svg>
                                        </span>
                                        <span class="checklist-item-text">Keep rough paper and a pencil ready.</span>
                                    </label>

                                    <label class="checklist-pill-row mb-2">
                                        <input type="checkbox" class="checklist-checkbox start-readiness-check">
                                        <span class="checklist-custom-box">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        </span>
                                        <span class="checklist-item-icon text-checklist-purple">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
                                                <line x1="22" y1="9" x2="16" y2="15"/>
                                                <line x1="16" y1="9" x2="22" y2="15"/>
                                            </svg>
                                        </span>
                                        <span class="checklist-item-text">Work in a quiet place.</span>
                                    </label>

                                    <label class="checklist-pill-row mb-0">
                                        <input type="checkbox" class="checklist-checkbox start-readiness-check">
                                        <span class="checklist-custom-box">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        </span>
                                        <span class="checklist-item-icon checklist-green-badge">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        </span>
                                        <span class="checklist-item-text">Check that you understand the rules.</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Important Note Card -->
                            <div class="important-note-card">
                                <div class="d-flex align-items-center">
                                    <div class="important-note-icon flex-shrink-0 mr-3">
                                        <span>!</span>
                                    </div>
                                    <div>
                                        <h5 class="important-note-title mb-25">Important Note</h5>
                                        <p class="important-note-text mb-0" id="modal-important-note-text">
                                            The timer will begin as soon as you click Start Test.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="start-modal-footer d-flex flex-column-reverse flex-sm-row justify-content-between align-items-stretch align-items-sm-center pt-3 mt-2">
                        <button type="button" class="btn btn-back-assessments d-inline-flex align-items-center justify-content-center" data-dismiss="modal">
                            <i class="feather icon-arrow-left mr-2"></i> Back to Assessments
                        </button>
                        <button type="submit" class="btn btn-start-test-primary d-inline-flex align-items-center justify-content-center mb-2 mb-sm-0">
                            Start Test <i class="feather icon-arrow-right ml-2"></i>
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* ==========================================================================
       START TEST INSTRUCTIONS MODAL - MODERN UI/UX
       ========================================================================== */
    #instructionsModal {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    #instructionsModal .start-test-modal-dialog {
        width: calc(100% - 2.5rem) !important;
        max-width: calc(100% - 2.5rem) !important;
        min-height: calc(100vh - 2.5rem) !important;
        margin: 1.25rem auto !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    #instructionsModal .start-test-modal-dialog::before {
        display: none !important;
        content: none !important;
    }

    #instructionsModal .start-test-modal-content {
        width: 100% !important;
        margin: 0 auto !important;
        background-color: #f3f7ff !important;
        border-radius: 22px !important;
        overflow: hidden !important;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    /* Header */
    .start-header-icon-badge {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
        color: #ffffff;
    }

    .start-header-icon-badge.subject-english {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.25);
    }

    .start-header-icon-badge.subject-vr {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.25);
    }

    .start-modal-title {
        color: #0b1f51 !important;
        font-size: 1.6rem;
        font-weight: 800;
        line-height: 1.25;
        letter-spacing: -0.4px;
    }

    .start-modal-subtitle {
        color: #64748b;
        font-size: 0.94rem;
        font-weight: 500;
        margin-top: 2px;
    }

    .start-modal-close-btn {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: none;
        background-color: rgba(15, 23, 42, 0.06);
        color: #475569;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .start-modal-close-btn:hover {
        background-color: rgba(15, 23, 42, 0.12);
        color: #0f172a;
    }

    /* 5-Column Metadata Bar */
    .start-meta-bar {
        background-color: #ffffff;
        border-radius: 16px;
        border: 1px solid #e9eff8;
        box-shadow: 0 3px 14px rgba(15, 23, 42, 0.04);
        padding: 14px 20px;
    }

    .start-meta-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        align-items: center;
        gap: 10px;
    }

    .start-meta-item {
        display: flex;
        align-items: center;
        padding: 4px 12px 4px 4px;
        border-right: 1px solid #e2e8f0;
        min-width: 0;
    }

    .start-meta-item-last {
        border-right: none;
        padding-right: 4px;
    }

    .start-meta-icon-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-right: 12px;
        color: #ffffff;
    }

    .meta-icon-blue {
        background-color: #2563eb;
        box-shadow: 0 3px 10px rgba(37, 99, 235, 0.2);
    }

    .meta-icon-amber {
        background-color: #f59e0b;
        box-shadow: 0 3px 10px rgba(245, 158, 11, 0.2);
    }

    .meta-icon-purple {
        background-color: #8b5cf6;
        box-shadow: 0 3px 10px rgba(139, 92, 246, 0.2);
    }

    .meta-icon-green {
        background-color: #16a34a;
        box-shadow: 0 3px 10px rgba(22, 163, 74, 0.2);
    }

    .meta-icon-rose {
        background-color: #fce7f3;
        color: #e11d48;
    }

    .start-meta-text {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .start-meta-label {
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 500;
        line-height: 1.2;
    }

    .start-meta-value {
        font-size: 1.05rem;
        font-weight: 800;
        line-height: 1.25;
        margin-top: 2px;
        word-break: break-word;
    }

    .text-meta-blue { color: #2563eb; }
    .text-meta-amber { color: #f59e0b; }
    .text-meta-purple { color: #6d28d9; }
    .text-meta-green { color: #16a34a; }
    .text-meta-rose { color: #e11d48; }

    /* Content Cards */
    .start-panel-card {
        background-color: #ffffff;
        border-radius: 16px;
        border: 1px solid #e9eff8;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
        padding: 22px 24px;
    }

    .start-panel-heading {
        color: #0b1f51 !important;
        font-size: 1.22rem;
        font-weight: 800;
        letter-spacing: -0.2px;
    }

    /* Optional Custom Instruction Note */
    .paper-custom-instruction-box {
        background-color: #eff6ff;
        border-left: 3px solid #2563eb;
        border-radius: 8px;
        padding: 10px 14px;
    }

    .paper-custom-instruction-content {
        color: #334155;
        font-size: 0.84rem;
        line-height: 1.5;
    }

    .paper-custom-instruction-content p:last-child {
        margin-bottom: 0;
    }

    /* Numbered Instructions List */
    .instruction-step-row {
        display: flex;
        align-items: flex-start;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .instruction-step-row:first-child {
        padding-top: 2px;
    }

    .instruction-step-row-last {
        border-bottom: none;
        padding-bottom: 0;
    }

    .instruction-step-num {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background-color: #eef4ff;
        color: #2563eb;
        font-size: 0.85rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-right: 14px;
        margin-top: 2px;
    }

    .instruction-step-body {
        flex: 1;
        min-width: 0;
    }

    .instruction-step-title {
        color: #0f172a;
        font-size: 0.88rem;
        font-weight: 700;
        line-height: 1.35;
    }

    .instruction-step-desc {
        color: #64748b;
        font-size: 0.8rem;
        line-height: 1.4;
        margin-top: 2px;
    }

    /* Before You Start Interactive Checklist */
    .checklist-pill-row {
        position: relative;
        display: flex;
        align-items: center;
        background-color: #f4f7fe;
        border: 1px solid transparent;
        border-radius: 10px;
        padding: 12px 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }

    .checklist-pill-row:hover {
        background-color: #ebf2ff;
        border-color: #dbeafe;
    }

    .checklist-checkbox {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .checklist-checkbox:focus-visible + .checklist-custom-box {
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
    }

    .checklist-custom-box {
        width: 19px;
        height: 19px;
        border-radius: 5px;
        border: 1.8px solid #93c5fd;
        background-color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-right: 12px;
        transition: all 0.2s ease;
        color: transparent;
        font-size: 11px;
    }

    .checklist-checkbox:checked + .checklist-custom-box {
        background-color: #2563eb;
        border-color: #2563eb;
        color: #ffffff;
    }

    .checklist-pill-row.is-checked {
        background-color: #eff6ff;
        border-color: #bfdbfe;
    }

    .checklist-item-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-right: 12px;
    }

    .text-checklist-blue { color: #2563eb; }
    .text-checklist-amber { color: #f59e0b; }
    .text-checklist-purple { color: #7c3aed; }

    .checklist-green-badge {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background-color: #16a34a;
        color: #ffffff;
    }

    .checklist-item-text {
        color: #1e293b;
        font-size: 0.87rem;
        font-weight: 500;
        line-height: 1.35;
    }

    /* Important Note Card */
    .important-note-card {
        background-color: #fdf2f4;
        border: 1px solid #fecdd3;
        border-radius: 16px;
        padding: 18px 20px;
    }

    .important-note-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e11d48;
        color: #ffffff;
        font-weight: 900;
        font-size: 1.25rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(225, 29, 72, 0.25);
    }

    .important-note-title {
        color: #0b1f51 !important;
        font-weight: 800;
        font-size: 1.05rem;
    }

    .important-note-text {
        color: #334155;
        font-size: 0.87rem;
        font-weight: 500;
        line-height: 1.4;
    }

    /* Footer Buttons */
    .btn-back-assessments {
        background-color: #e6eefc !important;
        color: #1d4ed8 !important;
        border: none !important;
        border-radius: 10px !important;
        padding: 11px 22px !important;
        font-size: 0.9rem !important;
        font-weight: 700 !important;
        transition: all 0.2s ease !important;
    }

    .btn-back-assessments:hover {
        background-color: #dbeafe !important;
        color: #1e40af !important;
        transform: translateY(-1px);
    }

    .btn-start-test-primary {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 10px !important;
        padding: 11px 32px !important;
        font-size: 0.95rem !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35) !important;
        transition: all 0.2s ease !important;
    }

    .btn-start-test-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.45) !important;
        color: #ffffff !important;
    }

    /* Responsive Metadata Bar */
    @media (max-width: 991.98px) {
        .start-meta-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }
        .start-meta-item {
            border-right: none;
            padding: 6px 8px;
            background-color: #f8fafc;
            border-radius: 10px;
        }
        .start-meta-item-last {
            grid-column: span 2;
        }
    }

    @media (max-width: 575.98px) {
        #instructionsModal .start-test-modal-dialog {
            width: calc(100% - 1rem) !important;
            max-width: calc(100% - 1rem) !important;
            min-height: calc(100vh - 1rem) !important;
            margin: 0.5rem auto !important;
        }
        .start-meta-grid {
            grid-template-columns: 1fr;
        }
        .start-meta-item-last {
            grid-column: span 1;
        }
        .start-modal-title {
            font-size: 1.25rem;
        }
    }

    /* Dark Mode Support */
    .dark-layout #instructionsModal .start-test-modal-content {
        background-color: #0f172a !important;
        border: 1px solid #1e293b !important;
    }

    .dark-layout .start-modal-title,
    .dark-layout .start-panel-heading,
    .dark-layout .instruction-step-title,
    .dark-layout .important-note-title {
        color: #f8fafc !important;
    }

    .dark-layout .start-modal-subtitle,
    .dark-layout .start-meta-label,
    .dark-layout .instruction-step-desc,
    .dark-layout .important-note-text {
        color: #94a3b8 !important;
    }

    .dark-layout .start-modal-close-btn {
        background-color: rgba(255, 255, 255, 0.08);
        color: #cbd5e1;
    }

    .dark-layout .start-modal-close-btn:hover {
        background-color: rgba(255, 255, 255, 0.16);
        color: #ffffff;
    }

    .dark-layout .start-meta-bar,
    .dark-layout .start-panel-card {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        box-shadow: none !important;
    }

    .dark-layout .start-meta-item {
        border-right-color: #334155;
    }

    @media (max-width: 991.98px) {
        .dark-layout .start-meta-item {
            background-color: #0f172a;
        }
    }

    .dark-layout .meta-icon-rose {
        background-color: rgba(225, 29, 72, 0.2);
        color: #fb7185;
    }

    .dark-layout .instruction-step-row {
        border-bottom-color: #334155;
    }

    .dark-layout .instruction-step-num {
        background-color: rgba(37, 99, 235, 0.2);
        color: #60a5fa;
    }

    .dark-layout .paper-custom-instruction-box {
        background-color: rgba(37, 99, 235, 0.12);
    }

    .dark-layout .paper-custom-instruction-content {
        color: #cbd5e1;
    }

    .dark-layout .checklist-pill-row {
        background-color: #0f172a;
    }

    .dark-layout .checklist-pill-row:hover {
        background-color: #172554;
        border-color: #1e40af;
    }

    .dark-layout .checklist-pill-row.is-checked {
        background-color: rgba(37, 99, 235, 0.18);
        border-color: #2563eb;
    }

    .dark-layout .checklist-custom-box {
        background-color: #1e293b;
        border-color: #3b82f6;
    }

    .dark-layout .checklist-item-text {
        color: #e2e8f0;
    }

    .dark-layout .important-note-card {
        background-color: rgba(225, 29, 72, 0.12);
        border-color: rgba(225, 29, 72, 0.3);
    }

    .dark-layout .btn-back-assessments {
        background-color: #1e293b !important;
        color: #93c5fd !important;
        border: 1px solid #334155 !important;
    }

    .dark-layout .btn-back-assessments:hover {
        background-color: #334155 !important;
        color: #ffffff !important;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {
        // Toggle interactive checklist row highlight
        $(document).on('change', '.start-readiness-check', function () {
            $(this).closest('.checklist-pill-row').toggleClass('is-checked', $(this).is(':checked'));
        });

        // Subject-specific header icon SVG generator
        function getSubjectHeaderSvg(subjectName) {
            var lower = (subjectName || '').toLowerCase();
            if (lower.indexOf('english') !== -1) {
                return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>';
            }
            if (lower.indexOf('verbal') !== -1 || lower.indexOf('reasoning') !== -1) {
                return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 4.44-1.04"/><path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-4.44-1.04"/></svg>';
            }
            // Default / Maths (+ - x ÷)
            return '<svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.5 4.5V10.5M4.5 7.5H10.5" stroke="white" stroke-width="2.3" stroke-linecap="round"/><path d="M17.5 7.5H23.5" stroke="white" stroke-width="2.3" stroke-linecap="round"/><path d="M5 17.5L10 22.5M10 17.5L5 22.5" stroke="white" stroke-width="2.3" stroke-linecap="round"/><path d="M17.5 20H23.5" stroke="white" stroke-width="2.3" stroke-linecap="round"/><circle cx="20.5" cy="17.2" r="1.15" fill="white"/><circle cx="20.5" cy="22.8" r="1.15" fill="white"/></svg>';
        }

        $(document).on('click', '.btn-start-trigger', function () {
            var button = $(this);
            var paperTitle = button.data('paper-title') || 'Practice Test';
            var questionsCount = button.data('paper-questions');
            var totalTime = button.data('paper-time') || 'Unlimited';
            var isTimed = String(button.data('paper-timed')) === '1';
            var paperType = button.data('paper-type') || 'Direct Answer';
            var totalMarks = button.data('paper-marks');
            var subjectName = button.data('paper-subject') || 'General';
            var instruction = button.attr('data-paper-instruction') || '';
            var actionUrl = button.data('action-url');

            // Update Modal Form Action
            $('#modal-start-form').attr('action', actionUrl);

            // Update Header Title & Subject Icon
            $('#modal-paper-title').text(paperTitle);
            var iconBadge = $('#modal-header-subject-icon');
            iconBadge.removeClass('subject-english subject-vr');
            var subLower = String(subjectName).toLowerCase();
            if (subLower.indexOf('english') !== -1) {
                iconBadge.addClass('subject-english');
            } else if (subLower.indexOf('verbal') !== -1 || subLower.indexOf('reasoning') !== -1) {
                iconBadge.addClass('subject-vr');
            }
            iconBadge.html(getSubjectHeaderSvg(subjectName));

            // Update 5 Metadata Stats
            $('#modal-paper-questions').text(questionsCount !== undefined ? questionsCount : 0);
            $('#modal-paper-time').text(totalTime);
            $('#modal-paper-type').text(paperType);
            $('#modal-paper-marks').text(totalMarks !== undefined ? totalMarks : 0);
            $('#modal-paper-subject').text(subjectName);

            // Adapt Instruction Step 2 wording cleanly to answer format
            if (String(paperType).toLowerCase().indexOf('multiple') !== -1) {
                $('#modal-instr-step2-title').text('Select your final answer clearly for each question.');
                $('#modal-instr-step2-desc').text('Review your selected option before moving on or confirming.');
            } else {
                $('#modal-instr-step2-title').text('Type your final answer clearly in the answer box.');
                $('#modal-instr-step2-desc').text('Make sure your answer is visible and in the correct format.');
            }

            // Show optional paper-specific custom instruction if non-empty
            var tempDiv = document.createElement('div');
            tempDiv.innerHTML = instruction;
            var plainInstruction = (tempDiv.textContent || tempDiv.innerText || '').trim();
            if (plainInstruction.length > 0) {
                $('#modal-paper-instruction').html(instruction);
                $('#modal-paper-instruction-box').removeClass('d-none');
            } else {
                $('#modal-paper-instruction').empty();
                $('#modal-paper-instruction-box').addClass('d-none');
            }

            // Update Important Note dynamically based on timed/untimed test
            if (isTimed) {
                $('#modal-important-note-text').text('The timer will begin as soon as you click Start Test.');
            } else {
                $('#modal-important-note-text').text('This test has no time limit. Click Start Test when you are ready to begin.');
            }

            // Reset readiness checkboxes cleanly for each new modal open
            $('.start-readiness-check').prop('checked', false).closest('.checklist-pill-row').removeClass('is-checked');

            // Show the modal
            $('#instructionsModal').modal('show');
        });
    });
</script>
@endpush
