@extends('layouts.app')

@section('title', 'Topic Focus Areas')

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">

        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Focus Areas</h2>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Focus Areas</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <style>
                /* ── Focus Areas page styles ── */

                /* Info card */
                .fa-info-card {
                    background: #fff;
                    border: 1px solid #dce3ee;
                    border-left: 4px solid #1a73e8;
                    border-radius: 8px;
                    padding: 20px 24px 16px;
                    margin-bottom: 20px;
                    box-shadow: 0 2px 8px rgba(0,0,0,.05);
                }
                .fa-info-card .fa-info-title {
                    font-size: 14.5px;
                    font-weight: 700;
                    color: #1a3a5c;
                    margin-bottom: 6px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }
                .fa-info-card .fa-info-title i {
                    color: #1a73e8;
                    font-size: 16px;
                }
                .fa-info-card .fa-info-body {
                    font-size: 13.5px;
                    color: #555;
                    line-height: 1.7;
                    margin-bottom: 14px;
                }
                .fa-info-card .fa-info-body strong { color: #1a3a5c; }
                .fa-info-points {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 10px;
                    margin-top: 4px;
                }
                .fa-info-point {
                    display: flex;
                    align-items: flex-start;
                    gap: 9px;
                    background: #f4f7fc;
                    border-radius: 7px;
                    padding: 10px 14px;
                    flex: 1 1 200px;
                    min-width: 180px;
                }
                .fa-info-point .fp-icon {
                    width: 30px;
                    height: 30px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                    font-size: 14px;
                    margin-top: 1px;
                }
                .fa-info-point .fp-icon.blue  { background: #e8f0fe; color: #1a73e8; }
                .fa-info-point .fp-icon.green { background: #e6f4ea; color: #1e7e34; }
                .fa-info-point .fp-icon.amber { background: #fff3cd; color: #c97a0b; }
                .fa-info-point .fp-icon.red   { background: #fdecea; color: #c0392b; }
                .fa-info-point .fp-text {
                    font-size: 12.8px;
                    color: #444;
                    line-height: 1.55;
                }
                .fa-info-point .fp-text strong {
                    display: block;
                    font-size: 13px;
                    font-weight: 700;
                    color: #1a3a5c;
                    margin-bottom: 2px;
                }

                /* Controls bar */
                .fa-controls {
                    display: flex;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 8px;
                    background: #fff;
                    border: 1px solid #dee2e6;
                    border-radius: 6px;
                    padding: 12px 18px;
                    margin-bottom: 22px;
                    font-size: 13.5px;
                }
                .fa-controls label { margin-bottom: 0; cursor: pointer; }
                .fa-controls .fa-label { font-weight: 500; margin-right: 4px; white-space: nowrap; }

                /* Radio group */
                .fa-radio-group {
                    display: flex;
                    align-items: center;
                    gap: 14px;
                    flex-wrap: wrap;
                }
                .fa-radio-group label {
                    display: flex;
                    align-items: center;
                    gap: 5px;
                    cursor: pointer;
                    margin: 0;
                    white-space: nowrap;
                }
                .fa-radio-group input[type="radio"] { accent-color: #0d6efd; }

                /* Threshold input */
                .fa-threshold-input {
                    width: 60px;
                    text-align: center;
                    border: 1px solid #ced4da;
                    border-radius: 4px;
                    padding: 3px 6px;
                    font-size: 13px;
                }
                .fa-threshold-input:focus { outline: none; border-color: #0d6efd; box-shadow: 0 0 0 2px rgba(13,110,253,.15); }

                /* Recalculate button */
                .btn-recalculate {
                    background: linear-gradient(135deg, #0d6efd, #0a58ca);
                    color: #fff;
                    border: none;
                    border-radius: 20px;
                    padding: 6px 18px;
                    font-size: 13px;
                    font-weight: 600;
                    letter-spacing: .3px;
                    cursor: pointer;
                    transition: opacity .2s;
                }
                .btn-recalculate:hover { opacity: .85; }

                /* Subject accordion */
                .fa-subject-card {
                    border-radius: 8px;
                    overflow: hidden;
                    margin-bottom: 18px;
                    box-shadow: 0 2px 8px rgba(0,0,0,.08);
                }

                .fa-subject-header {
                    background: linear-gradient(135deg, #0d3b6e, #1a5276);
                    color: #fff;
                    padding: 13px 20px;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    cursor: pointer;
                    user-select: none;
                    font-size: 15px;
                    font-weight: 600;
                    letter-spacing: .3px;
                }
                .fa-subject-header .fa-chevron {
                    transition: transform .25s ease;
                    font-size: 16px;
                }
                .fa-subject-header.collapsed .fa-chevron { transform: rotate(180deg); }

                /* Table */
                .fa-table-wrap { overflow-x: auto; }
                .fa-table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 13.5px;
                }
                .fa-table thead tr {
                    background: #0d2c50;
                    color: #fff;
                }
                .fa-table thead th {
                    padding: 11px 14px;
                    font-weight: 600;
                    text-align: center;
                    white-space: nowrap;
                    font-size: 13px;
                    border: none;
                }
                .fa-table thead th:first-child { text-align: left; }
                .fa-table tbody tr {
                    border-bottom: 1px solid #e9ecef;
                    transition: background .15s;
                }
                .fa-table tbody tr:last-child { border-bottom: none; }
                .fa-table tbody tr:hover { background: #f0f4ff; }
                .fa-table tbody td {
                    padding: 10px 14px;
                    text-align: center;
                    color: #333;
                    vertical-align: middle;
                }
                .fa-table tbody td:first-child {
                    text-align: left;
                }
                .fa-topic-link {
                    color: #1a73e8;
                    font-weight: 500;
                    text-decoration: none;
                }
                .fa-topic-link:hover { text-decoration: underline; }

                /* Score badges */
                .fa-score {
                    display: inline-block;
                    padding: 2px 8px;
                    border-radius: 20px;
                    font-weight: 600;
                    font-size: 13px;
                    min-width: 46px;
                }
                .fa-score.low    { color: #c0392b; background: #fdecea; }
                .fa-score.mid    { color: #b7770d; background: #fef3cd; }
                .fa-score.good   { color: #1e7e34; background: #d4edda; }

                /* Empty state */
                .fa-empty {
                    text-align: center;
                    padding: 40px 20px;
                    color: #888;
                }
                .fa-empty i { font-size: 40px; margin-bottom: 10px; display: block; color: #ccc; }

                /* No subjects */
                .fa-no-data {
                    background: #fff;
                    border: 1px solid #dee2e6;
                    border-radius: 8px;
                    padding: 50px 20px;
                    text-align: center;
                    color: #777;
                }
            </style>

            <!-- Info Card -->
            <div class="fa-info-card">
                <div class="fa-info-title">
                    <i class="feather icon-info"></i>
                    About This Report
                </div>
                <div class="fa-info-body">
                    This report highlights topics where your score falls <strong>below a set threshold</strong>,
                    helping you identify the areas that need the most attention.
                    The <strong>recommended threshold</strong> is an average score below <strong>80%</strong>.
                    You can customise both the <strong>percentage threshold</strong> and the
                    <strong>type of average</strong> used, then click <strong>RECALCULATE</strong> to refresh the results.
                </div>
                <div class="fa-info-points">
                    <div class="fa-info-point">
                        <div class="fp-icon blue"><i class="feather icon-bar-chart-2"></i></div>
                        <div class="fp-text">
                            <strong>Threshold</strong>
                            Topics scoring below your chosen % will appear in the list.
                        </div>
                    </div>
                    <div class="fa-info-point">
                        <div class="fp-icon amber"><i class="feather icon-refresh-cw"></i></div>
                        <div class="fp-text">
                            <strong>Average Type</strong>
                            Choose between overall average, first attempt, or most recent attempt.
                        </div>
                    </div>
                    <div class="fa-info-point">
                        <div class="fp-icon green"><i class="feather icon-check-square"></i></div>
                        <div class="fp-text">
                            <strong>Full Attempts Only</strong>
                            Only completed full-test scores are counted &mdash; sweeps are excluded.
                        </div>
                    </div>
                    <div class="fa-info-point">
                        <div class="fp-icon red"><i class="feather icon-target"></i></div>
                        <div class="fp-text">
                            <strong>Focus &amp; Improve</strong>
                            Use this list to guide your revision and boost your weakest areas first.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <form method="GET" action="{{ route('student.focusareas') }}" class="fa-controls">
                <span class="fa-label">Show topics where my</span>

                <div class="fa-radio-group">
                    <label>
                        <input type="radio" name="average_type" value="average"
                               {{ ($averageType ?? 'average') === 'average' ? 'checked' : '' }}>
                        Overall Average
                    </label>
                    <label>
                        <input type="radio" name="average_type" value="first"
                               {{ ($averageType ?? '') === 'first' ? 'checked' : '' }}>
                        First Attempt Average
                    </label>
                    <label>
                        <input type="radio" name="average_type" value="last"
                               {{ ($averageType ?? '') === 'last' ? 'checked' : '' }}>
                        Last Attempt Average
                    </label>
                </div>

                <span class="fa-label" style="margin-left:6px;">is below</span>
                <input type="number" name="threshold" class="fa-threshold-input"
                       min="0" max="100" value="{{ $threshold ?? 80 }}"> %

                <button type="submit" class="btn-recalculate">&#x21BB;&nbsp; RECALCULATE</button>
            </form>

            <!-- Subject Sections -->
            @if(empty($subjectData))
                <div class="fa-no-data">
                    <i class="feather icon-check-circle" style="font-size:48px; color:#27ae60; margin-bottom:12px; display:block;"></i>
                    <h5 style="color:#333; font-weight:600;">Great work! No focus areas found.</h5>
                    <p style="margin:0; color:#777;">All your topic averages are above the {{ $threshold }}% threshold. Keep it up!</p>
                </div>
            @else
                @foreach($subjectData as $index => $data)
                    @php $collapseId = 'subject-collapse-' . $loop->index; @endphp
                    <div class="fa-subject-card">
                        <!-- Header -->
                        <div class="fa-subject-header"
                             data-toggle="collapse"
                             data-target="#{{ $collapseId }}"
                             aria-expanded="true"
                             aria-controls="{{ $collapseId }}"
                             id="header-{{ $collapseId }}">
                            <span>{{ $data['subject']->title }}</span>
                            <span class="fa-chevron">&#x2303;</span>
                        </div>

                        <!-- Collapsible body -->
                        <div id="{{ $collapseId }}" class="collapse show">
                            <div class="fa-table-wrap">
                                <table class="fa-table">
                                    <thead>
                                        <tr>
                                            <th style="width:35%;">Topic / Subtopic</th>
                                            <th>Tests<br>Available</th>
                                            <th>Tests<br>Attempted</th>
                                            <th>Total<br>Attempts</th>
                                            <th>Average</th>
                                            <th>First Attempt<br>Average</th>
                                            <th>Last Attempt<br>Average</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['topics'] as $row)
                                            <tr>
                                                <td>
                                                    <a href="#" class="fa-topic-link">{{ $row['name'] }}</a>
                                                </td>
                                                <td><span style="color:#1a73e8; font-weight:600;">{{ $row['available'] }}</span></td>
                                                <td><span style="color:#1a73e8; font-weight:600;">{{ $row['attempted'] }}</span></td>
                                                <td><span style="color:#1a73e8; font-weight:600;">{{ $row['total'] }}</span></td>
                                                <td>
                                                    @if($row['average'] !== null)
                                                        <span class="fa-score {{ $row['average'] < 50 ? 'low' : ($row['average'] < 75 ? 'mid' : 'good') }}">
                                                            {{ $row['average'] }}%
                                                        </span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($row['first_average'] !== null)
                                                        <span class="fa-score {{ $row['first_average'] < 50 ? 'low' : ($row['first_average'] < 75 ? 'mid' : 'good') }}">
                                                            {{ $row['first_average'] }}%
                                                        </span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($row['last_average'] !== null)
                                                        <span class="fa-score {{ $row['last_average'] < 50 ? 'low' : ($row['last_average'] < 75 ? 'mid' : 'good') }}">
                                                            {{ $row['last_average'] }}%
                                                        </span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

        </div><!-- /content-body -->
    </div>
</div>
<!-- END: Content-->

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle chevron direction on collapse/expand
    document.querySelectorAll('.fa-subject-header').forEach(function (header) {
        var targetId = header.getAttribute('data-target');
        var panel    = document.querySelector(targetId);
        var chevron  = header.querySelector('.fa-chevron');

        if (!panel) return;

        // Bootstrap 4 collapse events
        $(panel).on('show.bs.collapse', function () {
            header.classList.remove('collapsed');
            chevron.style.transform = 'rotate(0deg)';
        });
        $(panel).on('hide.bs.collapse', function () {
            header.classList.add('collapsed');
            chevron.style.transform = 'rotate(180deg)';
        });
    });
});
</script>
@endsection
