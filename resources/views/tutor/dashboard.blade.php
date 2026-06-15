 @extends('layouts.app')

 @section('title', 'Tutor Dashboard')

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
             @if(isset($unreadHighPriority) && $unreadHighPriority->count() > 0)
                 <div class="alert alert-danger alert-dismissible fade show mb-2 shadow" role="alert" style="border-left: 6px solid #ea5455; background-color: #fff;">
                     <div class="d-flex align-items-center">
                         <i class="feather icon-alert-circle font-large-1 mr-2 text-danger pulsing-icon"></i>
                         <div>
                             <h5 class="alert-heading font-weight-bold mb-0 text-danger">Urgent Notices Attention Needed!</h5>
                             <p class="mb-0 font-small-3 text-secondary">You have <strong>{{ $unreadHighPriority->count() }}</strong> new urgent announcement(s). Please read them immediately.</p>
                         </div>
                         <div class="ml-auto">
                             <a href="{{ route('tutor.announcements') }}" class="btn btn-danger btn-sm text-uppercase font-weight-bold">View Notices</a>
                         </div>
                     </div>
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
                 <style>
                     @keyframes pulse {
                         0% { transform: scale(1); }
                         50% { transform: scale(1.15); }
                         100% { transform: scale(1); }
                     }
                     .pulsing-icon {
                         animation: pulse 1.5s infinite;
                     }
                 </style>
             @endif

             <!-- Dashboard Ecommerce Starts -->
             <section id="dashboard-ecommerce">
                 <div class="row">
                     <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="card text-white bg-gradient-primary text-center">
                                <div class="card-content ">
                                    <div class="card-body">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Lessons.png') }}" alt="element 01" width="150" >
                                        <h4 class="card-title text-white mt-1">Lessons</h4>
                                        <p class="card-text">Checkout the video lessons here to enhance your learning experience.</p>
                                        <div class="divider">
                                            <div class="divider-text"><i class="feather icon-star"></i></div>
                                        </div>
                                        <a href="{{ route('student.videolessonscategories') }}" class="btn btn-primary waves-effect waves-light">Watch Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="card text-white bg-gradient-primary text-center">
                                <div class="card-content d-flex">
                                    <div class="card-body">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Analytics.png') }}" alt="element 01" width="150" >
                                        <h4 class="card-title text-white mt-1">Analytics</h4>
                                        <p class="card-text">View your performance metrics and analytics.</p>
                                        <div class="divider">
                                            <div class="divider-text"><i class="feather icon-star"></i></div>
                                        </div>
                                        <button class="btn btn-primary waves-effect waves-light">View Details</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="card text-white bg-gradient-primary text-center">
                                <div class="card-content d-flex">
                                    <div class="card-body">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Assessment.png') }}" alt="element 01" width="150" >
                                        <h4 class="card-title text-white mt-1">Assessment</h4>
                                        <p class="card-text">Take quizzes and tests to evaluate your knowledge.</p>
                                         <div class="divider">
                                            <div class="divider-text"><i class="feather icon-star"></i></div>
                                        </div>
                                        <button class="btn btn-primary waves-effect waves-light">Start Assessment</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="card text-white bg-gradient-primary text-center">
                                <div class="card-content d-flex">
                                    <div class="card-body">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/FocusAreas.png') }}" alt="element 01" width="150">
                                        <h4 class="card-title text-white mt-1">Focus Areas</h4>
                                        <p class="card-text">Focus on your weak areas to improve your skills.</p>
                                        <div class="divider divider-dark">
                                            <div class="divider-text"></div>
                                        </div>
                                        <button class="btn btn-primary waves-effect waves-light">View Focus Areas</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="card text-white bg-gradient-primary text-center">
                                <div class="card-content">
                                    <div class="card-body">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Anouncement.png') }}" alt="element 01" width="150" height="128" >
                                        <h4 class="card-title text-white mt-1">Announcements</h4>
                                        <p class="card-text">Stay updated with the latest news and announcements.</p>
                                         <div class="divider">
                                            <div class="divider-text"><i class="feather icon-star"></i></div>
                                        </div>
                                        <a href="{{ route('tutor.announcements') }}" class="btn btn-primary waves-effect waves-light">View Announcements</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="card text-white bg-gradient-primary text-center">
                                <div class="card-content">
                                    <div class="card-body">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Centertestscore.png') }}" alt="element 01" width="150" >
                                        <h4 class="card-title text-white mt-1">Center Test Scores</h4>
                                        <p class="card-text">View your performance metrics and analytics.</p>
                                        <div class="divider">
                                            <div class="divider-text"><i class="feather icon-star"></i></div>
                                        </div>
                                        <button class="btn btn-primary waves-effect waves-light">View Details</button>
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