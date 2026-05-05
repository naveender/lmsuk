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
                         <div class="col-lg-4 col-md-6 col-sm-6">
                             <div class="card text-white bg-gradient-primary text-center">
                                 <div class="card-content">
                                     <div class="card-body">
                                        <div class="d-block mb-1">
                                             <img src="{{ asset('theme/app-assets/images/lmsicon/more/community.png') }}"
                                                 alt="element 01" width="150" class="mb-1">
                                         </div>
                                         <a href="{{ route('student.videolessonscategories') }}"
                                             class="btn btn-primary waves-effect waves-light">Manage Students</a>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-lg-4 col-md-6 col-sm-6">
                             <div class="card text-white bg-gradient-primary text-center">
                                 <div class="card-content">
                                     <div class="card-body">
                                        <div class="d-block mb-1">
                                             <img src="{{ asset('theme/app-assets/images/lmsicon/more/training.png') }}"
                                                 alt="element 01" width="150" class="mb-1">
                                         </div>
                                         <a href="{{ route('student.videolessonscategories') }}"
                                             class="btn btn-primary waves-effect waves-light">Manage Classes</a>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-lg-4 col-md-6 col-sm-6">
                             <div class="card text-white bg-gradient-primary text-center">
                                 <div class="card-content">
                                     <div class="card-body">
                                        <div class="d-block mb-1">
                                             <img src="{{ asset('theme/app-assets/images/lmsicon/more/cloud-upload.png') }}"
                                                 alt="element 01" width="150" class="mb-1">
                                         </div>
                                         <a href="{{ route('student.videolessonscategories') }}"
                                             class="btn btn-primary waves-effect waves-light">Upload Files</a>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-lg-4 col-md-6 col-sm-6">
                             <div class="card text-white bg-gradient-primary text-center">
                                 <div class="card-content">
                                     <div class="card-body">
                                        <div class="d-block mb-1">
                                             <img src="{{ asset('theme/app-assets/images/lmsicon/more/announcement.png') }}"
                                                 alt="element 01" width="150" class="mb-1">
                                         </div>
                                         <a href="{{ route('student.videolessonscategories') }}"
                                             class="btn btn-primary waves-effect waves-light">Manage Announcement</a>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-lg-4 col-md-6 col-sm-6">
                             <div class="card text-white bg-gradient-primary text-center">
                                 <div class="card-content">
                                     <div class="card-body">
                                        <div class="d-block mb-1">
                                             <img src="{{ asset('theme/app-assets/images/lmsicon/more/report.png') }}"
                                                 alt="element 01" width="150" class="mb-1">
                                         </div>
                                         <a href="{{ route('student.videolessonscategories') }}"
                                             class="btn btn-primary waves-effect waves-light">Create a Report</a>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-lg-4 col-md-6 col-sm-6">
                             <div class="card text-white bg-gradient-primary text-center">
                                 <div class="card-content">
                                     <div class="card-body">
                                        <div class="d-block mb-1">
                                             <img src="{{ asset('theme/app-assets/images/lmsicon/more/archive.png') }}"
                                                 alt="element 01" width="150" class="mb-1">
                                         </div>
                                         <a href="{{ route('student.videolessonscategories') }}"
                                             class="btn btn-primary waves-effect waves-light">Manage Files</a>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-lg-4 col-md-6 col-sm-6">
                             <div class="card text-white bg-gradient-primary text-center">
                                 <div class="card-content">
                                     <div class="card-body">
                                        <div class="d-block mb-1">
                                             <img src="{{ asset('theme/app-assets/images/lmsicon/more/bill.png') }}"
                                                 alt="element 01" width="150" class="mb-1">
                                         </div>
                                         <a href="{{ route('student.videolessonscategories') }}"
                                             class="btn btn-primary waves-effect waves-light">Invoice Creator</a>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-lg-4 col-md-6 col-sm-6">
                             <div class="card text-white bg-gradient-primary text-center">
                                 <div class="card-content">
                                     <div class="card-body">
                                        <div class="d-block mb-1">
                                             <img src="{{ asset('theme/app-assets/images/lmsicon/more/new-employee.png') }}"
                                                 alt="element 01" width="150" class="mb-1">
                                         </div>
                                         <a href="{{ route('student.videolessonscategories') }}"
                                             class="btn btn-primary waves-effect waves-light">New Sign-ups</a>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-lg-4 col-md-6 col-sm-6">
                             <div class="card text-white bg-gradient-primary text-center">
                                 <div class="card-content">
                                     <div class="card-body">
                                        <div class="d-block mb-1">
                                             <img src="{{ asset('theme/app-assets/images/lmsicon/more/line-chart.png') }}"
                                                 alt="element 01" width="150" class="mb-1">
                                         </div>
                                         <a href="{{ route('student.videolessonscategories') }}"
                                             class="btn btn-primary waves-effect waves-light">Cohort Report</a>
                                     </div>
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
