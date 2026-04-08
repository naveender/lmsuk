@extends('layouts.app')

@section('title', 'Topic Focus Areas')
<!-- END: Custom CSS-->
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
                                    <li class="breadcrumb-item"><a href="index.html">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Charts &amp; Maps</a>
                                    </li>
                                    <li class="breadcrumb-item active">Apex
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <!-- Accordion with margin start -->
                <section id="accordion-with-margin">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card collapse-icon accordion-icon-rotate">
                                <div class="card-header">
                                    <h4 class="card-title">Margin</h4>
                                </div>
                                <div class="card-body">
                                    <p>
                                        To create a collapse with margin use
                                        <code>.collapse-margin</code> class as a wrapper for your collapse
                                        header
                                    </p>
                                    <div class="accordion" id="accordionExample">
                                        <div class="collapse-margin">
                                            <div class="card-header" id="headingOne" data-toggle="collapse" role="button"
                                                data-target="#collapseOne" aria-expanded="false"
                                                aria-controls="collapseOne">
                                                <span class="lead collapse-title">
                                                    Accordion Item 1
                                                </span>
                                            </div>

                                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    Pastry pudding cookie toffee bonbon jujubes jujubes powder topping.
                                                    Jelly beans gummi bears sweet roll
                                                    bonbon muffin liquorice. Wafer lollipop sesame snaps. Brownie macaroon
                                                    cookie muffin cupcake candy
                                                    caramels tiramisu.

                                                    Oat cake chocolate cake sweet jelly-o brownie biscuit marzipan. Jujubes
                                                    donut marzipan chocolate bar.
                                                    Jujubes sugar plum jelly beans tiramisu icing cheesecake.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="collapse-margin">
                                            <div class="card-header" id="headingTwo" data-toggle="collapse" role="button"
                                                data-target="#collapseTwo" aria-expanded="false"
                                                aria-controls="collapseTwo">
                                                <span class="lead collapse-title">
                                                    Accordion Item 2
                                                </span>
                                            </div>
                                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    Sweet pie candy jelly. Sesame snaps biscuit sugar plum. Sweet roll
                                                    topping fruitcake. Caramels
                                                    liquorice biscuit ice cream fruitcake cotton candy tart.

                                                    Donut caramels gingerbread jelly-o gingerbread pudding. Gummi bears
                                                    pastry marshmallow candy canes
                                                    pie. Pie apple pie carrot cake.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="collapse-margin">
                                            <div class="card-header" id="headingThree" data-toggle="collapse" role="button"
                                                data-target="#collapseThree" aria-expanded="false"
                                                aria-controls="collapseThree">
                                                <span class="lead collapse-title">
                                                    Accordion Item 3
                                                </span>
                                            </div>
                                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    Tart gummies dragée lollipop fruitcake pastry oat cake. Cookie jelly
                                                    jelly macaroon icing jelly beans
                                                    soufflé cake sweet. Macaroon sesame snaps cheesecake tart cake sugar
                                                    plum.

                                                    Dessert jelly-o sweet muffin chocolate candy pie tootsie roll marzipan.
                                                    Carrot cake marshmallow
                                                    pastry. Bonbon biscuit pastry topping toffee dessert gummies. Topping
                                                    apple pie pie croissant cotton
                                                    candy dessert tiramisu.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="collapse-margin">
                                            <div class="card-header" id="headingFour" data-toggle="collapse" role="button"
                                                data-target="#collapseFour" aria-expanded="false"
                                                aria-controls="collapseFour">
                                                <span class="lead collapse-title">
                                                    Accordion Item 4
                                                </span>
                                            </div>
                                            <div id="collapseFour" class="collapse" aria-labelledby="headingFour"
                                                data-parent="#accordionExample">
                                                <div class="card-body">
                                                    Cheesecake muffin cupcake dragée lemon drops tiramisu cake gummies
                                                    chocolate cake. Marshmallow tart
                                                    croissant. Tart dessert tiramisu marzipan lollipop lemon drops. Cake
                                                    bonbon bonbon gummi bears topping
                                                    jelly beans brownie jujubes muffin.

                                                    Donut croissant jelly-o cake marzipan. Liquorice marzipan cookie wafer
                                                    tootsie roll. Tootsie roll
                                                    sweet cupcake.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Accordion with margin end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection
