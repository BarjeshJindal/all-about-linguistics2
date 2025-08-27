@extends('layouts.vertical', ['title' => 'Dashboard', 'topbarTitle' => 'Dashboard'])

@section('content')
    <div class="container dashboard-background">
            <div class="row">
                <div class="col-xl-6 col-md-6">
                    <div class="row">
                        <div class="col-xxl-6 col-xl-6">
                            <div class="col">
                                <div class="card">
                                    <div class="d-flex card-header justify-content-between align-items-center">
                                        <div>
                                            {{-- <a href="{{ route('admin.practices.index') }}">Practices</a> --}}
                                            {{-- <a href="{{ route('admin.dialogues.index') }}">Upload Dialogue</a> --}}
                                            <h4 class="header-title">Practice Dialogue</h4>
                                        </div>
                                        <div class="dropdown">
                                            <a href="#" class="dropdown-toggle drop-arrow-none card-drop"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-2-fill fs-18"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body pt-0">
                                        <div class="d-flex align-items-end gap-2 justify-content-between">
                                            <div class="text-end flex-shrink-0">
                                                <div id="total-orders-chart" data-colors="#ff5b5b,#F6F7FB"></div>
                                                <h3 class="completed">Completed:</h3>
                                            </div>
                                            <div class="text-end">
                                                <h3 class="fw-semibold">687</h3>
                                                <p class="text-muted mb-0">Total</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end col -->

                            {{-- <div class="card border-0 shadow" style="width: 100%; border-radius: 20px;">
                                <div class="card-header text-white fw-bold d-flex align-items-center"
                                    style="background-color: #ff5b5b; border-top-left-radius: 20px; border-top-right-radius: 20px;">
                                    <i class="bi bi-calendar2-event-fill me-2"></i> Exam Date
                                </div>
                                <div class="card-body text-center">
                                    <i class="bi bi-calendar2-check-fill" style="font-size: 48px; color: #ff5b5b;"></i>
                                    <h5 class="fw-bold mt-3">Upcoming Exam</h5>
                                    <p class="text-muted mb-3">2025–05–29</p>
                                    <hr style="width: 60%; margin: auto; border-top: 2px solid #ffb3b3; opacity: 0.5;">
                                    <button class="btn mt-4 px-4 py-2 fw-semibold text-white"
                                        style="background-color: #ff5b5b; border-radius: 10px;">
                                        Set Exam Date
                                    </button>
                                </div>
                            </div> --}}
                        </div>


                        <div class="col-xxl-6 col-xl-6">
                            <div class="col">
                                <div class="card">
                                    <div class="d-flex card-header justify-content-between align-items-center">
                                        <div>
                                            <h4 class="header-title">Mock Test</h4>
                                        </div>
                                        <div class="dropdown">
                                            <a href="#" class="dropdown-toggle drop-arrow-none card-drop"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-2-fill fs-18"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body pt-0">
                                        <div class="d-flex align-items-center gap-2 justify-content-between">
                                            <span class="badge bg-success rounded-pill fs-13">32% <i
                                                    class="ti ti-trending-up"></i>
                                            </span>

                                            <div class="text-end">
                                                <h3 class="fw-semibold">582</h3>
                                                <p class="text-muted mb-0">Total</p>
                                            </div>
                                        </div>
                                        <h3 class="completed">Completed:</h3>
                                        <div class="progress progress-soft progress-sm mt-3">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 25%"
                                                aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end col -->
                            {{-- <div class="youtubr">
                                <iframe width="100%" height="286px" src="https://www.youtube.com/embed/VIDEO_ID"
                                    title="YouTube video player" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen>
                                </iframe>
                            </div> --}}
                        </div>
                        <div class="col-xxl-6 col-xl-6">
                            <div class="col">
                                <div class="card">
                                    <div class="d-flex card-header justify-content-between align-items-center">
                                        <div>
                                            <h4 class="header-title">VIP Test</h4>
                                        </div>
                                        <div class="dropdown">
                                            <a href="#" class="dropdown-toggle drop-arrow-none card-drop"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-2-fill fs-18"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body pt-0">
                                        <div class="d-flex align-items-end gap-2 justify-content-between">
                                            <div class="text-end flex-shrink-0">
                                                <div id="new-users-chart" data-colors="#f9c851,#F6F7FB"></div>
                                                <h3 class="completed">Completed:</h3>
                                            </div>
                                            <div class="text-end">
                                                <h3 class="fw-semibold">464</h3>
                                                <p class="text-muted mb-0">Total</p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end col -->
                            {{-- <div class="youtubr">
                                <iframe width="100%" height="286px" src="https://www.youtube.com/embed/VIDEO_ID"
                                    title="YouTube video player" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen>
                                </iframe>
                            </div> --}}
                        </div>
                        <div class="col-xxl-6 col-xl-6">
                            <div class="col">
                                <div class="card">
                                    <div class="d-flex card-header justify-content-between align-items-center">
                                        <div>
                                            <h4 class="header-title">Weekly Website Statistics</h4>
                                        </div>
                                        <div class="dropdown">
                                            <a href="#" class="dropdown-toggle drop-arrow-none card-drop"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-2-fill fs-18"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                                                <!-- item-->
                                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body pt-0">
                                        <div class="d-flex align-items-center gap-2 justify-content-between">
                                            <span class="badge bg-info rounded-pill fs-13">5.7% <i
                                                    class="ti ti-trending-down"></i>
                                            </span>
                                            <div class="text-end">
                                                <h3 class="fw-semibold">94</h3>
                                                <p class="text-muted mb-0">Total</p>
                                            </div>
                                        </div>
                                        <h3 class="completed">Completed:</h3>
                                        <div class="progress progress-soft progress-sm mt-3">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 94.3%"
                                                aria-valuenow="94.3" aria-valuemin="0" aria-valuemax="100"></div>

                                        </div>
                                    </div>
                                </div>
                            </div><!-- end col -->
                            {{-- <div class="youtubr">
                                <iframe width="100%" height="286px" src="https://www.youtube.com/embed/VIDEO_ID"
                                    title="YouTube video player" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen>
                                </iframe>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-6">
                    <div class="card">
                        <div
                            class="card-header d-flex justify-content-between align-items-center border-bottom border-dashed">
                            <h4 class="header-title mb-0">
                                Category Practice Dialogues</h4>
                        </div>

                        <div class="card-body card-body practice-dialogues-categories">
                            <div class="row">
                                <div class="col-xl-6 col-md-6">
                                    <h5 class="mt-0">Medical <span class="text-primary float-end">80%</span></h5>
                                    <h5 class="mt-0">Question</h5>
                                    <div class="progress progress-soft progress-sm mt-0 mb-3">
                                        <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="80"
                                            aria-valuemin="0" aria-valuemax="100" style="width: 80%;">
                                        </div>

                                    </div>

                                </div> <!-- col-xl-6 col-md-6 end -->

                                <div class="col-xl-6 col-md-6">
                                    <h5 class="mt-0">Welfare<span class="text-secondary float-end">50%</span></h5>
                                    <h5 class="mt-0">Question</h5>
                                    <div class="progress progress-soft progress-sm mt-0 mb-3">
                                        <div class="progress-bar bg-secondary" role="progressbar" aria-valuenow="50"
                                            aria-valuemin="0" aria-valuemax="100" style="width: 50%;">
                                        </div><!-- /.progress-bar .progress-bar-secondary -->
                                    </div>
                                </div> <!-- col-xl-6 col-md-6 end -->
                                <div class="col-xl-6 col-md-6">
                                    <h5 class="mt-0">Employment<span class="text-info float-end">70%</span></h5>
                                    <h5 class="mt-0">Question</h5>
                                    <div class="progress progress-soft progress-sm mt-0 mb-3">
                                        <div class="progress-bar bg-info" role="progressbar" aria-valuenow="70"
                                            aria-valuemin="0" aria-valuemax="100" style="width: 70%;">
                                        </div><!-- /.progress-bar .progress-bar-info -->
                                    </div>
                                </div> <!-- col-xl-6 col-md-6 end -->
                                <div class="col-xl-6 col-md-6">
                                    <h5 class="mt-0">Immigration<span class="text-warning float-end">65%</span></h5>
                                    <h5 class="mt-0">Question</h5>
                                    <div class="progress progress-soft progress-sm mt-0 mb-3">
                                        <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="65"
                                            aria-valuemin="0" aria-valuemax="100" style="width: 65%;">
                                        </div><!-- /.progress-bar .progress-bar-warning -->
                                    </div>
                                </div> <!-- col-xl-6 col-md-6 end -->
                                <div class="col-xl-6 col-md-6">
                                    <h5 class="mt-0">Insurance<span class="text-danger float-end">65%</span></h5>
                                    <h5 class="mt-0">Question</h5>
                                    <div class="progress progress-soft progress-sm mt-0 mb-3">
                                        <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="65"
                                            aria-valuemin="0" aria-valuemax="100" style="width: 65%;">
                                        </div><!-- /.progress-bar .progress-bar-warning -->
                                    </div>
                                </div> <!-- col-xl-6 col-md-6 end -->
                                <div class="col-xl-6 col-md-6">
                                    <h5 class="mt-0">Education<span class="text-success float-end">40%</span></h5>
                                    <h5 class="mt-0">Question</h5>
                                    <div class="progress progress-soft progress-sm mt-0">
                                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="40"
                                            aria-valuemin="0" aria-valuemax="100" style="width: 40%;">
                                        </div><!-- /.progress-bar .progress-bar-success -->
                                    </div>
                                </div> <!-- col-xl-6 col-md-6 end -->

                                <div class="col-xl-6 col-md-6">
                                    <h5 class="mt-0">Legal<span class="text-secondary float-end">50%</span></h5>
                                    <h5 class="mt-0">Question</h5>
                                    <div class="progress progress-soft progress-sm mt-0 mb-3">
                                        <div class="progress-bar bg-secondary" role="progressbar" aria-valuenow="50"
                                            aria-valuemin="0" aria-valuemax="100" style="width: 50%;">
                                        </div><!-- /.progress-bar .progress-bar-secondary -->
                                    </div>
                                </div> <!-- col-xl-6 col-md-6 end -->

                                <div class="col-xl-6 col-md-6">
                                    <h5 class="mt-0">Community <span class="text-primary float-end">80%</span></h5>
                                    <h5 class="mt-0">Question</h5>
                                    <div class="progress progress-soft progress-sm mt-0 mb-3">
                                        <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="80"
                                            aria-valuemin="0" aria-valuemax="100" style="width: 80%;">
                                        </div>

                                    </div>

                                </div> <!-- col-xl-6 col-md-6 end -->


                                <div class="col-xl-6 col-md-6">
                                    <h5 class="mt-0">Housing<span class="text-info float-end">70%</span></h5>
                                    <h5 class="mt-0">Question</h5>
                                    <div class="progress progress-soft progress-sm mt-0 mb-3">
                                        <div class="progress-bar bg-info" role="progressbar" aria-valuenow="70"
                                            aria-valuemin="0" aria-valuemax="100" style="width: 70%;">
                                        </div><!-- /.progress-bar .progress-bar-info -->
                                    </div>
                                </div> <!-- col-xl-6 col-md-6 end -->
                                <div class="col-xl-6 col-md-6">
                                    <h5 class="mt-0">Business<span class="text-warning float-end">65%</span></h5>
                                    <h5 class="mt-0">Question</h5>
                                    <div class="progress progress-soft progress-sm mt-0 mb-3">
                                        <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="65"
                                            aria-valuemin="0" aria-valuemax="100" style="width: 65%;">
                                        </div><!-- /.progress-bar .progress-bar-warning -->
                                    </div>
                                </div> <!-- col-xl-6 col-md-6 end -->
                                <div class="col-xl-6 col-md-6">
                                    <h5 class="mt-0">Consumer Affairs<span class="text-danger float-end">65%</span></h5>
                                    <h5 class="mt-0">Question</h5>
                                    <div class="progress progress-soft progress-sm mt-0 mb-3">
                                        <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="65"
                                            aria-valuemin="0" aria-valuemax="100" style="width: 65%;">
                                        </div><!-- /.progress-bar .progress-bar-warning -->
                                    </div>
                                </div> <!-- col-xl-6 col-md-6 end -->
                                <div class="col-xl-6 col-md-6">
                                    <h5 class="mt-0">Financial<span class="text-success float-end">40%</span></h5>
                                    <h5 class="mt-0">Question</h5>
                                    <div class="progress progress-soft progress-sm mt-0">
                                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="40"
                                            aria-valuemin="0" aria-valuemax="100" style="width: 40%;">
                                        </div><!-- /.progress-bar .progress-bar-success -->
                                    </div>
                                </div> <!-- col-xl-6 col-md-6 end -->

                                <div class="col-xl-6 col-md-6">
                                    <h5 class="mt-0">General<span class="text-secondary float-end">50%</span></h5>
                                    <h5 class="mt-0">Question</h5>
                                    <div class="progress progress-soft progress-sm mt-0 mb-3">
                                        <div class="progress-bar bg-secondary" role="progressbar" aria-valuenow="50"
                                            aria-valuemin="0" aria-valuemax="100" style="width: 50%;">
                                        </div><!-- /.progress-bar .progress-bar-secondary -->
                                    </div>
                                </div> <!-- col-xl-6 col-md-6 end -->

                                <div class="col-xl-6 col-md-6">
                                    <h5 class="mt-0">Social Services<span class="text-primary float-end">80%</span></h5>
                                    <h5 class="mt-0">Question</h5>
                                    <div class="progress progress-soft progress-sm mt-0 mb-3">
                                        <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="80"
                                            aria-valuemin="0" aria-valuemax="100" style="width: 80%;">
                                        </div>

                                    </div>

                                </div> <!-- col-xl-6 col-md-6 end -->


                            </div>
                        </div> <!-- end card-body -->
                    </div> <!-- end card-->
                </div> <!-- end col-->
                
            </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
@endsection
