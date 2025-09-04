<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Sidenav Menu Start -->
<div class="sidenav-menu">

    <!-- Brand Logo -->
    <a href=" {{ route('admin.dashboard') }}" class="sidebar-logo logo">
        <span class="logo-light">
            <span class="logo-lg sidebar-logo"><img src="{{ asset('/images/all-about-linguitics.png') }}"></span>
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

                    <div class="dropdown-divider"></div>



                    <!-- item-->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button class="dropdown-item active fw-semibold text-danger">
                            <i class="ri-logout-box-line me-1 fs-16 align-middle"></i>
                            <span class="align-middle">Sign Out</span>
                        </button>
                </div>
                </form>

            </div>
        </div>

        <!--- Sidenav Menu -->
        <ul class="side-nav">
            <li class="side-nav-item">
                <a href="{{ route('admin.dashboard') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-user"></i></span>
                    <span class="menu-text"> Dashboard </span>

                </a>
            </li>
             <li class="side-nav-item">
                <a href="javascript:void(0);" class="side-nav-link" data-bs-toggle="collapse"
                    data-bs-target="#practiceDialogueMenu">
                    <span class="menu-icon"><i class="ti ti-help-circle"></i></span>
                    <span class="menu-text">Practice Dialogue</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="collapse side-nav-sub" id="practiceDialogueMenu">
                    <li class="side-nav-item">

                
                    <a href="{{ route('admin.practices.create') }}" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-book"></i></span>
                        <span class="menu-text"> Add Practice Dialogue </span>
                    </a>
                </li>
                 <li class="side-nav-item">

                
                    <a href="{{ route('admin.practices.manage') }}" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-book"></i></span>
                        <span class="menu-text"> Manage Practice Dialogue </span>
                    </a>
                </li>
            
                </ul>
            </li>
                
            <li class="side-nav-item">


                <a href="{{ route('admin.language.index') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-language"></i></span>
                    <span class="menu-text"> Add Language </span>
                </a>
            </li>

            <li class="side-nav-item">


                <a href="{{ route('admin.category.index') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-category"></i></span>
                    <span class="menu-text"> Add Category </span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="javascript:void(0);" class="side-nav-link" data-bs-toggle="collapse"
                    data-bs-target="#faqsMenu">
                    <span class="menu-icon"><i class="ti ti-help-circle"></i></span>
                    <span class="menu-text">Faqs</span>
                    <span class="menu-arrow"></span>
                </a>

                <ul class="collapse side-nav-sub" id="faqsMenu">
                     <li class="side-nav-item">
                        <a href="{{ route('admin.faqs.add') }}" class="side-nav-link">
                             <span class="menu-icon"><i class="ti ti-circle-plus"></i></span>
                            <span class="menu-text"> Add Faqs </span>
                        </a>
                    </li>
                     <li class="side-nav-item">
                        <a href="{{ route('admin.faqs.list') }}" class="side-nav-link">
                            <span class="menu-icon"><i class="ti ti-edit"></i></span>
                            <span class="menu-text"> Manage Faqs </span>
                        </a>
                    </li>
                </ul>
            </li>
           

            <li class="side-nav-item">
                <a href="javascript:void(0);" class="side-nav-link" data-bs-toggle="collapse"
                    data-bs-target="#mockTestMenu">
                    <span class="menu-icon"><i class="ti ti-pencil"></i></span>
                    <span class="menu-text">Mock Test</span>
                    <span class="menu-arrow"></span>
                </a>

                <ul class="collapse side-nav-sub" id="mockTestMenu">
                    <li class="side-nav-item">
                        <a href="{{ route('admin.mock-tests.addMockTest') }}">
                            <span class="menu-icon"><i class="ti ti-clipboard-plus"></i></span>
                            <span class="menu-text">Add Mock Test</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('admin.mock-tests.reviews.index') }}">
                            <span class="menu-icon"><i class="ti ti-edit"></i></span>
                            <span class="menu-text">Review Mock Tests</span>
                        
                        </a>
                    </li>
                </ul>
            </li>


            {{-- <li class="side-nav-item">
                <a href="{{ route('admin.mock-testssss.index') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-message-circle"></i></span>
                    <span class="menu-text"> Feedback </span>
                </a>
            </li> --}}

            {{-- <li class="side-nav-item">
                <a href="#" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-notes"></i></span>
                    <span class="menu-text">Portal Access</span>
                </a>
            </li> --}}

            {{-- <li class="side-nav-item">
                <a href="#" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-bulb"></i></span>
                    <span class="menu-text"> Types of Plans</span>
                </a>
            </li> --}}

            <li class="side-nav-item">
                <a href="{{ route('admin.vip-exams.create')}}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-devices-question"></i></span>
                    <span class="menu-text"> Add VIP Exam Material</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#vocabularyMenu" aria-expanded="false"
                    aria-controls="vocabularyMenu" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-alphabet-latin"></i></span>
                    <span class="menu-text">Vocabulary</span>
                    <span class="menu-arrow"></span>
                </a>

                        <div class="collapse" id="vocabularyMenu">
                            <ul class="side-nav-second-level">
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.vocabulary.words') }}">
                                        <span class="menu-icon"><i class="ti ti-file-plus"></i></span>
                                        <span class="menu-text">Add Words</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.vocabulary.category') }}">
                                        <span class="menu-icon"><i class="ti ti-library-plus"></i></span>
                                        <span class="menu-text">View Words</span>
                        
                                    </a>
                                </li>
                                
                            </ul>
                        </div>
            </li>




            <li class="side-nav-item">
                <a href="#" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-device-laptop"></i></span>
                    <span class="menu-text"> NAATI CCL Online Classes</span>
                </a>
            </li>
            {{-- @can('show-role') 
             <li class="side-nav-item">
                <a href="{{ route('admin.roles.index')}}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-user-cog"></i></span>
                    <span class="menu-text"> Roles</span>
                </a>
            </li>
            @endcan
            @can('create-role')   
            <li class="side-nav-item">
                <a href="{{ route('admin.roles.create')}}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-user-cog"></i></span>
                    <span class="menu-text">Create Role</span>
                </a>
            </li>
            @endcan
            @can('create-user')   
            <li class="side-nav-item">
                <a href="{{ route('admin.roles.add-user')}}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-user-plus"></i></span>
                    <span class="menu-text">Create  User</span>
                </a>
            </li>
            @endcan --}}
        </ul>
        <div class="clearfix"></div>
    </div>
</div>
<!-- Sidenav Menu End -->
<style>
    li.side-nav-item {
    list-style: none;
    padding: 8px;
}
li.active {
    list-style: none;
    padding: 8px;
}
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
