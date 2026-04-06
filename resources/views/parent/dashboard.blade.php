 @extends('layouts.app')

 {{-- @section('title', 'Login') --}}

 <!-- END: Custom CSS-->
 @section('content')
 <!-- BEGIN: Content-->
 <div class="app-content content">
     <div class="content-overlay"></div>
     <div class="header-navbar-shadow"></div>
     <div class="content-wrapper">
         <div class="content-header row">
         </div>
         <div class="content-body">
             <!-- Dashboard Ecommerce Starts -->
             <section id="dashboard-ecommerce">
                 <div class="row">
                     <div class="col-lg-3 col-sm-6 col-12">
                         <div class="card">
                             <div class="card-header d-flex flex-column align-items-start pb-0">
                                 <div class="avatar bg-rgba-primary p-50 m-0">
                                     <div class="avatar-content">
                                         <i class="feather icon-users text-primary font-medium-5"></i>
                                     </div>
                                 </div>
                                 <p class="mb-0 mt-2">Total Backups</p>
                                 <h2 class="text-bold-700 mt-1">{{ $stats['total_backups'] ?? 0 }}</h2>
                                 <small>Active backup instances</small>
                                 <small class="success"><i class="feather icon-arrow-up"></i> +12 this week</small>
                             </div>
                             <div class="card-content">
                                 <div id="line-area-chart-1"></div>
                             </div>
                         </div>
                     </div>

                     <div class="col-lg-3 col-sm-6 col-12">
                         <div class="card">
                             <div class="card-header d-flex flex-column align-items-start pb-0">
                                 <div class="avatar bg-rgba-warning p-50 m-0">
                                     <div class="avatar-content">
                                         <i class="feather icon-package text-warning font-medium-5"></i>
                                     </div>
                                 </div>
                                 <p class="mb-0 mt-2">Storage Used</p>
                                 <h2 class="text-bold-700 mt-1">{{ $stats['storage_used_gb'] ?? 0 }} GB</h2>
                                 <small>Is Used</small>
                                 <small class="success"><i class="feather icon-arrow-up"></i>24% utilization</small>
                             </div>
                             <div class="card-content">
                                 <div id="line-area-chart-4"></div>
                             </div>
                         </div>
                     </div>
                     <div class="col-lg-3 col-sm-6 col-12">
                         <div class="card">
                             <div class="card-header d-flex flex-column align-items-start pb-0">
                                 <div class="avatar bg-rgba-success p-50 m-0">
                                     <div class="avatar-content">
                                         <i class="feather icon-check-circle text-success font-medium-5"></i>
                                     </div>
                                 </div>
                                 <p class="mb-0 mt-2">Successful Backups</p>
                                 <h2 class="text-bold-700 mt-1">98.3%</h2>
                                 <small>Success rate (30 days)</small>
                                 <small class="success"><i class="feather icon-arrow-up"></i> +0.3% from last
                                     month</small>
                             </div>
                             <div class="card-content">
                                 <div id="line-area-chart-2"></div>
                             </div>
                         </div>
                     </div>
                     <div class="col-lg-3 col-sm-6 col-12">
                         <div class="card">
                             <div class="card-header d-flex flex-column align-items-start pb-0">
                                 <div class="avatar bg-rgba-danger p-50 m-0">
                                     <div class="avatar-content">
                                         <i class="feather icon-alert-octagon text-danger font-medium-5"></i>
                                     </div>
                                 </div>
                                 <p class="mb-0 mt-2">Pending Issues</p>
                                 <h2 class="text-bold-700 mt-1">23</h2>
                                 <small>Require attention</small>
                                 <small class="success"><i class="feather icon-arrow-up"></i> 2 resolved
                                     today</small>
                             </div>
                             <div class="card-content">
                                 <div id="line-area-chart-3"></div>
                             </div>
                         </div>
                     </div>
                 </div>
             </section>
             <!-- Dashboard Ecommerce ends -->

         </div>
     </div>
 </div>
 <!-- END: Content-->
 @endsection