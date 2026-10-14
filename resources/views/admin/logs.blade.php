@extends('layouts.app')
 @section('content')
     <!-- BEGIN: Content-->
     <div class="app-content content">
         <div class="content-overlay"></div>
         <div class="header-navbar-shadow"></div>
         <div class="content-wrapper">
            <!-- Page Header / Title & Breadcrumbs -->
            <div class="admin-page-header">
                <div class="admin-page-title-wrap">
                    <div class="admin-title-row">
                        <h1 class="admin-page-heading">System Logs</h1>
                        <nav class="admin-breadcrumbs" aria-label="Breadcrumb">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                            <span class="breadcrumb-sep">&raquo;</span>
                            <span>System</span>
                            <span class="breadcrumb-sep">&raquo;</span>
                            <span>Logs</span>
                        </nav>
                    </div>
                    <p class="admin-page-subtitle">Monitor backup operations, user activities, and system events</p>
                </div>
            </div>
            <div class="content-body">
                 <div class="row">
                     <div class="col-lg-3 col-sm-6 col-12">
                         <div class="card">
                             <div class="card-header d-flex align-items-start pb-0">
                                 <div>
                                     <h2 class="text-bold-700 mb-0 success">4</h2>
                                     <p>Successful</p>
                                 </div>
                                 <div class="avatar bg-rgba-primary p-50 m-0">
                                     <div class="avatar-content">
                                         <i class="feather icon-cpu text-primary font-medium-5"></i>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-lg-3 col-sm-6 col-12">
                         <div class="card">
                             <div class="card-header d-flex align-items-start pb-0">
                                 <div>
                                     <h2 class="text-bold-700 mb-0 warning">1.2gb</h2>
                                     <p>Warnings</p>
                                 </div>
                                 <div class="avatar bg-rgba-success p-50 m-0">
                                     <div class="avatar-content">
                                         <i class="feather icon-server text-success font-medium-5"></i>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-lg-3 col-sm-6 col-12">
                         <div class="card">
                             <div class="card-header d-flex align-items-start pb-0">
                                 <div>
                                     <h2 class="text-bold-700 mb-0 danger">0.1%</h2>
                                     <p>Errors</p>
                                 </div>
                                 <div class="avatar bg-rgba-danger p-50 m-0">
                                     <div class="avatar-content">
                                         <i class="feather icon-activity text-danger font-medium-5"></i>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-lg-3 col-sm-6 col-12">
                         <div class="card">
                             <div class="card-header d-flex align-items-start pb-0">
                                 <div>
                                     <h2 class="text-bold-700 mb-0 primary">13</h2>
                                     <p>Total Operations</p>
                                 </div>
                                 <div class="avatar bg-rgba-warning p-50 m-0">
                                     <div class="avatar-content">
                                         <i class="feather icon-alert-octagon text-warning font-medium-5"></i>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

                 <section id="data-list-view" class="data-list-view-header">
                     <div class="card">
                         <div class="card-content">
                             <div class="card-body card-dashboard">
                                 <!-- DataTable starts -->
                                 <div class="table-responsive">
                                     <table class="table data-list-view">
                                         <thead>
                                             <tr>
                                                 <th>Timestamp</th>
                                                 <th>File</th>
                                                 <th>Type</th>
                                                 <th>Action</th>
                                                 <th>Message</th>
                                                 <th>Steps</th>
                                                <th>Status</th>
                                                  
                                             </tr>
                                         </thead>
                                         <tbody>
                                             @foreach ($logs as $log)
                                                 <tr>
                                                     <td class="text-nowrap">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                                     <td>{{ $log->file_name }}</td>

                                                     <td>{{ ucfirst($log->type) }}</td>
                                                     <td>{{ ucfirst($log->action) }}</td>
                                                     <td>{{ $log->message }}</td>
                                                     <td>
                                                         <ul>
                                                             @foreach ($log->steps as $step)
                                                                 <li>{{ $step }}</li>
                                                             @endforeach
                                                         </ul>
                                                     </td>
                                                     <td>
                                                         <span class="badge badge-{{ $log->status === 'completed' ? 'success' : ($log->status === 'failed' ? 'danger' : 'warning') }}">
                                                             {{ ucfirst($log->status) }}
                                                         </span>
                                                     </td>
                                                 </tr>
                                             @endforeach
                                         </tbody>
                                     </table>
                                 </div>
                                 <!-- DataTable ends -->
                             </div>
                         </div>
                     </div>
                 </section>
             </div>
         </div>
     </div>
     <!-- END: Content-->
 @endsection
