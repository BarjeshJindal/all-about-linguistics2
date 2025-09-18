<!-- Sidenav Menu Start -->
<div class="sidenav-menu">

    <!-- Brand Logo -->
    <a href="{{ route('users.dashboard') }}" class="sidebar-logo logo">
        <span class="logo-light">
            <span class="logo-lg sidebar-logo"><img src="{{ asset('/images/all-about-linguitics.png') }}"
                    alt="logo"></span>
            <span class="logo-sm sidebar-small-logo"><img src="{{ asset('/images/all-about-linguitics.png') }}"
                    alt="small logo"></span>
        </span>

        <span class="logo-dark">
            <span class="logo-lg sidebar-logo"><img src="{{ asset('/images/all-about-linguitics.png') }}"
                    alt="dark logo"></span>
            <span class="logo-sm sidebar-small-logo"><img src="{{ asset('/images/all-about-linguitics.png') }}"
                    alt="small logo"></span>
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <button class="button-sm-hover">
        <i class="ri-circle-line align-middle"></i>
    </button>

    <!-- Sidebar Menu Toggle Button -->
    <button class="sidenav-toggle-button">
        <i class="ri-menu-5-line fs-20"></i>
    </button>

    <!-- Full Sidebar Menu Close Button -->
    <button class="button-close-fullsidebar">
        <i class="ti ti-x align-middle"></i>
    </button>

    <div data-simplebar>

        <!-- User -->
        <div class="sidenav-user">
            <div class="dropdown-center text-center">
                <a class="topbar-link dropdown-toggle text-reset drop-arrow-none px-2" data-bs-toggle="dropdown"
                    type="button" aria-haspopup="false" aria-expanded="false">
                    <img src="/images/users/avatar-1.jpg" width="46" class="rounded-circle" alt="user-image">
                    <span class="d-flex gap-1 sidenav-user-name my-2">
                        <span>
                            <span class="mb-0 fw-semibold lh-base fs-15">{{ auth()->user()->name }}</span>

                        </span>
                        <i class="ri-arrow-down-s-line d-block sidenav-user-arrow align-middle"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">
                        <i class="ri-account-circle-line me-1 fs-16 align-middle"></i>
                        <span class="align-middle">My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">
                        <i class="ri-question-line me-1 fs-16 align-middle"></i>
                        <span class="align-middle">Support</span>
                    </a>
                      <!-- Edit Profile-->
                    <a href="{{route('edit.profile')}}" class="dropdown-item">
                        <i class="ti ti-user-edit"></i>
                        <span class="align-middle">Edit Profile</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <!-- item-->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item active fw-semibold text-danger">
                            <i class="ri-logout-box-line me-1 fs-16 align-middle"></i>
                            <span class="align-middle">Sign Out</span>
                        </button>
                    </form> <!-- ✅ moved closing form outside of dropdown-menu -->
                </div>
            </div>
        </div>

        <!--- Sidenav Menu -->
        <ul class="side-nav">
            <li class="side-nav-item">
                <a href="{{ route('users.dashboard')}}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-user"></i></span>
                    <span class="menu-text"> Dashboard </span>

                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('practiceDialogue') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-book"></i></span>
                    <span class="menu-text"> CCL Practice Dialogues </span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('vip-exam')}}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-pencil"></i></span>
                    <span class="menu-text"> VIP Exam Material</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('vocabulary.view')}}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-alphabet-latin"></i></span>
                    <span class="menu-text">Vocabulary</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{route('user.MockTests.list')}}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-notes"></i></span>
                    <span class="menu-text">Mock Test</span>
                </a>
            </li>

            {{-- <li class="side-nav-item">
                <a href="#" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-device-laptop"></i></span>
                    <span class="menu-text"> NAATI CCL Online Classes</span>
                </a>
            </li> --}}

            {{-- <li class="side-nav-item">
                <a href="{{ route('users.videos') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-player-play"></i></span>
                    <span class="menu-text"> Videos</span>
                </a>
            </li> --}}

            <li class="side-nav-item">
                <a href="{{route('edit.profile')}}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-user"></i></span>
                    <span class="menu-text">Profile</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('users.faq') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-question-mark"></i></span>
                    <span class="menu-text">Frequent Question</span>
                </a>
            </li>

            {{-- <li class="side-nav-item">
                <a href="#" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-messages"></i></span>
                    <span class="menu-text">Forum</span>
                </a>
            </li> --}}

            {{-- <li class="side-nav-item">
                <a href="#" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-file-text"></i></span>
                    <span class="menu-text">Book NAATI CCL Exam</span>
                </a>
            </li> --}}
             <li class="side-nav-item">
                <a href="{{route('users.subscription')}}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-file-text"></i></span>
                    <span class="menu-text">Subscription Plans</span>
                </a>
            </li>
            {{-- <li class="side-nav-item">
                <a href="javascript:void(0);" class="side-nav-link" data-bs-toggle="collapse"
                    data-bs-target="#helpDeskMenu" aria-expanded="false" aria-controls="helpDeskMenu">
                    <span class="menu-icon"><i class="ti ti-help-circle"></i></span>
                    <span class="menu-text">Help Desk</span>
                    <span class="menu-arrow"></span>
                </a>

                <ul class="collapse side-nav-sub" id="helpDeskMenu">
                    <li class="side-nav-item">
                        <a href="{{ route('user.create-ticket') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-circle-plus"></i></span>
                            <span class="menu-text">Create Ticket</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('user.tickets.list') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-edit"></i></span>
                            <span class="menu-text">Ticket History</span>
                        </a>
                    </li>
                </ul>
            </li> --}}

        </ul>

        <div class="clearfix"></div>
    </div>
</div>
<!-- Sidenav Menu End -->

<style>
    span.logo-lg.sidebar-logo img {
        width: 130px;
        height: 130px;
    }

    a.sidebar-logo.logo {
        padding: 0px;
    }

    a.sidebar-logo span.logo-sm.sidebar-small-logo img {
        width: 75px;
        height: 75px !important;
    }
</style>
