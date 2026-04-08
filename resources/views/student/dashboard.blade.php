 @extends('layouts.app')

 @section('title', 'Student Dashboard')

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
                     <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="card text-white bg-gradient-primary text-center">
                                <div class="card-content d-flex">
                                    <div class="card-body">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Lessons.png') }}" alt="element 01" width="150" class="float-left px-1">
                                        <h4 class="card-title text-white mt-3">Lessons</h4>
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
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Analytics.png') }}" alt="element 01" width="150" class="float-left px-1">
                                        <h4 class="card-title text-white mt-3">Analytics</h4>
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
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Assessment.png') }}" alt="element 01" width="150" class="float-left px-1">
                                        <h4 class="card-title text-white mt-3">Assessment</h4>
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
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Centertestscore.png') }}" alt="element 01" width="150" class="float-left px-1">
                                        <h4 class="card-title text-white mt-3">Focus Areas</h4>
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
                                <div class="card-content d-flex">
                                    <div class="card-body">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Anouncement.png') }}" alt="element 01" width="150" class="float-left px-1">
                                        <h4 class="card-title text-white mt-3">Announcements</h4>
                                        <p class="card-text">Stay updated with the latest news and announcements.</p>
                                         <div class="divider">
                                            <div class="divider-text"><i class="feather icon-star"></i></div>
                                        </div>
                                        <button class="btn btn-primary waves-effect waves-light">View Announcements</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="card text-white bg-gradient-primary text-center">
                                <div class="card-content d-flex">
                                    <div class="card-body">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/Centertestscore.png') }}" alt="element 01" width="150" class="float-left px-1">
                                        <h4 class="card-title text-white mt-3">Center Test Scores</h4>
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