<?php

use App\Models\Segment;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DialogueController;

use App\Http\Controllers\Auth\AdminLoginController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

// naati controllers
use App\Http\Controllers\Naati\Admin\AdminMockTestController;
use App\Http\Controllers\Admin\AdminSubcriptionPlanController;
use App\Http\Controllers\Naati\MockTestController;
use App\Http\Controllers\Naati\FeedbackController;
use App\Http\Controllers\Naati\LanguageController;
use App\Http\Controllers\Naati\PracticeDialogueController;
use App\Http\Controllers\Naati\ResultsController;
use App\Http\Controllers\Naati\RoleController;
use App\Http\Controllers\Naati\RoutingController;
use App\Http\Controllers\Naati\SegmentController;
use App\Http\Controllers\Naati\TeacherController;
use App\Http\Controllers\Naati\UserController;
use App\Http\Controllers\Naati\UserMockTestDialogueController;
use App\Http\Controllers\Naati\UserRecordingController;
use App\Http\Controllers\Naati\UserSegmentController;
use App\Http\Controllers\Naati\VocabularyController;
use App\Http\Controllers\Naati\ProfileController;
use App\Http\Controllers\Naati\VipExamController;
use App\Http\Controllers\Naati\UserVipExamController;
use App\Http\Controllers\Naati\CategoryController;
use App\Http\Controllers\Naati\UserDashboardController;
use App\Http\Controllers\Naati\FaqController;
use App\Http\Controllers\Naati\UserFaqController;
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// 🔹 User Login & Logout
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('auth.login', ['isAdmin' => false]))->name('login');
});

// Route::post('/logout', function () {
//     Auth::logout();
//     return redirect('/login')->with('success', 'User logged out successfully.');
// })->name('logout');
Route::post('/logout', function () {
    if (Auth::guard('admin')->check()) {
        Auth::guard('admin')->logout();
        return redirect('/admin/login')->with('success', 'Admin logged out successfully.');
    }

    if (Auth::guard('web')->check()) {
        Auth::guard('web')->logout();
        return redirect('/login')->with('success', 'User logged out successfully.');
    }

    return redirect('/login');
})->name('logout');


// 🔹 Admin Login & Logout
Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', fn() => view('auth.login', ['isAdmin' => true]))->name('admin.login');
    Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');
});

Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::resource('roles', RoleController::class)->except(['show']);
    // language
    Route::get('/add-language',[LanguageController::class,'index'])->name('language.index');
    Route::post('/store-language',[LanguageController::class,'store'])->name('language.store');
    // Faqs
    Route::get('/add-faqs',[FaqController::class,'addFaqs'])->name('faqs.add');
    Route::post('/store-faqs',[FaqController::class,'storeFaqs'])->name('faqs.store');

    Route::get('/manage-faqs',[FaqController::class,'faqsList'])->name('faqs.list');
    Route::post('/store-faqs',[FaqController::class,'storeFaqs'])->name('faqs.store');
    Route::get('/edit/{id}/faq',[FaqController::class,'faqEdit'])->name('faqs.edit');
    Route::put('/update-faqs/{id}/faq',[FaqController::class,'faqUpdate'])->name('faqs.update');
    Route::delete('/delete/{id}/faq', [FaqController::class, 'faqDelete'])->name('faqs.delete');
 

    // Practice Dialogue
    Route::get('/practices', [PracticeDialogueController::class, 'index'])->name('practices.index');
    Route::get('/practices/create', [PracticeDialogueController::class, 'create'])->name('practices.create');
    Route::post('/practices/store', [PracticeDialogueController::class, 'store'])->name('practices.store');
    Route::get('/segments/{practice}', [SegmentController::class, 'index'])->name('segments.index');
    Route::get('/segments/{practice}/create', [SegmentController::class, 'create'])->name('segments.create');
    Route::post('/segments/{practice}/store', [SegmentController::class, 'store'])->name('segments.store');
    Route::get('/manage-practice-dialogue', [PracticeDialogueController::class, 'manageView'])->name('practices.manage');
    Route::get('/edit/{id}/practice-dialogue', [PracticeDialogueController::class, 'practiceDialogueEdit'])->name('pratice-dialogue.edit');
    Route::put('/update/{id}/practice-dialogue', [PracticeDialogueController::class, 'practiceDialogueUpdate'])->name('pratice-dialogue.update');

    // Route::get('/segment/create/{id}', [SegmentController::class, 'create'])->name('practices.create');
    // Route::post('/practices/store', [PracticeDialogueController::class, 'store'])->name('practices.store');
    Route::get('/select/practice-dialogue', [AdminSubcriptionPlanController::class, 'selectPracticeDialogue'])
            ->name('select-practice-dialogue');
    Route::post('/select/practice-dialogue', [AdminSubcriptionPlanController::class, 'updateSelectedDialogues'])
           ->name('update-selected-dialogue');
         
  
    // Vip Exam Material


    Route::get('/vip-exam/create', [VipExamController::class, 'create'])->name('vip-exams.create');
    Route::post('/vip-exam/store', [VipExamController::class, 'store'])->name('vip-exams.store');
    Route::get('/vip-exam/manage', [VipExamController::class, 'manageVipExam'])->name('vip-exams.manage');
    Route::get('/edit/{id}/vip-exam', [VipExamController::class, 'editVipExam'])->name('vip-exams.edit');
    Route::put('/update/{id}/vip-exam', [VipExamController::class, 'vipexamUpdate'])->name('vip-exams.update');


    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::put('/feedback/{user_recording}', [FeedbackController::class, 'update'])->name('feedback.update');


    Route::get('/dialogues', [DialogueController::class, 'index'])->name('dialogues.index');
    Route::post('/dialogue', [DialogueController::class, 'store'])->name('dialogues.store');

    // Admin role management
    
    // Route::middleware(['role:admin'])->group(function () {
    // Admin role management
    // Route::get('/roles/create', [RoleController::class, 'createRole'])->name('roles.create')->middleware('permission:create-role');

    // Route::post('/roles/create', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:create-role');
    // Route::get('/roles', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:show-role');
    // Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:edit-role');
    // Route::put('/roles/{id}/update', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:create-role');
    // Route::delete('/roles/{id}/delete', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:delete-role');

    // Instructor add
    Route::get('/user-add', [RoleController::class, 'createUser'])->name('roles.add-user')->middleware('permission:create-user');
    Route::post('/user-store', [RoleController::class, 'storeUser'])->name('roles.store-user')->middleware('permission:create-user');
        // });

         
    // mock test
    // Route::get('/mock-tests/create', [MockTestController::class, 'create'])->name('mock-tests.create');
    // Route::post('/mock-tests/store', [MockTestController::class, 'store'])->name('mock-tests.store');

    // Route::get('/mock-tests/', [MockTestController::class, 'index'])->name('mock-tests.index');
    Route::get('/mock-tests/create', [AdminMockTestController::class, 'create'])->name('mock-tests.addMockTest');
    Route::post('/mock-tests/store', [AdminMockTestController::class, 'store'])->name('mock-tests.store');
    Route::get('/mock-tests/manage', [AdminMockTestController::class, 'manage'])->name('mock-tests.manage');
    Route::get('/mock-tests/{id}/edit', [AdminMockTestController::class, 'edit'])->name('mock-tests.edit');
    Route::put('/mock-tests/{id}/update', [AdminMockTestController::class, 'updateMockTest'])->name('mock-tests.update');
    
    // Route::get('/mock-tests/{mockTest}', [MockTestController::class, 'show'])->name('mock-tests.show');
    // Route::get('/mock-tests/{mockTest}/edit', [MockTestController::class, 'edit'])->name('mock-tests.edit');
    // Route::put('/mock-tests/{mockTest}', [MockTestController::class, 'update'])->name('mock-tests.update');
    // Route::delete('/mock-tests/{mockTest}', [MockTestController::class, 'destroy'])->name('mock-tests.destroy');

    //  vocabulary routes for admin
    Route::get('/vocabulary',[App\Http\Controllers\Admin\AdminVocabularyController::class,'addVocabulary'])->name('vocabulary.category');
    // Route::post('/store-vocabulary',[App\Http\Controllers\Admin\AdminVocabularyController::class,'storeVocabulary'])->name('category.store');
    //  words routes for admin
    Route::get('/add-words',[App\Http\Controllers\Admin\AdminVocabularyController::class,'addWords'])->name('vocabulary.words');
    Route::post('/store-word',[App\Http\Controllers\Admin\AdminVocabularyController::class,'storeWord'])->name('word.store');
    Route::get('/words/{category}',[App\Http\Controllers\Admin\AdminVocabularyController::class,'wordslist'])->name('vocabulary.words-list');
    Route::get('/words/{id}/edit',[App\Http\Controllers\Admin\AdminVocabularyController::class,'wordEdit'])->name('vocabulary.words-edit');
    Route::put('/words/{id}/update',[App\Http\Controllers\Admin\AdminVocabularyController::class,'wordUpdate'])->name('vocabulary.words-update');
    
    // admin.vocabulary.words-list
    Route::post('/single-words-store', [App\Http\Controllers\Admin\AdminVocabularyController::class, 'storesingleWord'])->name('single-word.store');

    Route::get('/mock-tests/reviews', [AdminMockTestController::class, 'reviewsIndex'])
            ->name('mock-tests.reviews.index');


    Route::get('/mock-tests/reviews/{id}', [AdminMockTestController::class, 'getUserMockTestDetails'])
        ->name('mock-tests.reviews.show');

    Route::post('/mocktest/feedback/update', [AdminMockTestController::class, 'updateFeedback'])
        ->name('mocktest.feedback.update');

    // Category
    Route::get('/add-category',[CategoryController::class,'index'])->name('category.index');
    Route::post('/add-category',[CategoryController::class,'store'])->name('category.store');
    Route::get('/download/sample-words', function () {
        return response()->download(public_path('downloadables/sample_words.xlsx'));
    })->name('download.sample-words');

});
// Route::middleware('auth:admin')->group(function () {
//     Route::get('/admin/dashboard',[AdminController::class, 'dashboard'])->name('admin.dashboard');
//     Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
// });


// my routes

// Route::prefix('admin')->name('admin.')->group(function () {

//     // GET: Admin login form
//     // Route::get('/login', fn() => view('auth.admin-login'))->name('login');

//     // POST: Handle admin login using Fortify's controller
//     Route::post('/login', [AuthenticatedSessionController::class, 'store'])
//         ->middleware('web') // Important: 'web' middleware for session
//         ->name('login.attempt');

//     // Routes behind auth:admin middleware
//     Route::middleware(['auth:admin'])->group(function () {
//         Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

//         // Admin role management
//         Route::get('/roles/create', [RoleController::class, 'createRole'])->name('roles.create');
//         Route::post('/roles/create', [RoleController::class, 'store'])->name('roles.store');
//         Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
//         Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
//         Route::put('/roles/{id}/update', [RoleController::class, 'update'])->name('roles.update');
//         Route::delete('/roles/{id}/delete', [RoleController::class, 'destroy'])->name('roles.destroy');
//     });
// });

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:web'])->group(function () {

    Route::get('/home', function () {
        return redirect()->route('users.dashboard');
    });

    Route::prefix('/naati')->group(function () {
        Route::get('/subscription', function () {
                return view('users.subscription');
                })->name('users.subscription');
        Route::get('/dashboard',[UserDashboardController::class, 'dashboard'] )->name('users.dashboard');
        Route::get('/practice-dialogue',[UserController::class, 'practiceDialogue'])->name('practiceDialogue');
        Route::get('/completed/{dialogue}/practice-dialogue',[UserController::class, 'resultsPracticeDialogue'])->name('results-PracticeDialogue');
        Route::get('/completed/{dialogue}/vip-exam',[UserVipExamController::class, 'completedVipExam'])->name('results-VipExam');
        Route::get('/feedback/pending/{practice}',[UserController::class,'pendingFeedback'])->name('pendingFeedback');
        Route::get('/practice-dialogue/{practice}', [UserSegmentController::class, 'index'])->name('user.segments.index');

        Route::post('/segments/store-all', [UserSegmentController::class, 'submitResponses'])->name('user.segments.storeAll');

        Route::get('/results', [ResultsController::class, 'index'])->name('results');
        Route::get('/notes/{practice}', [UserSegmentController::class, 'getNote'])->name('notes.get');

        Route::post('/notes/update/{practice}', [UserSegmentController::class, 'note'])->name('notes.update');


        // mock tests routes
        Route::get('/mock-tests',[MockTestController::class, 'showMockTestsList'])->name('user.MockTests.list');
        Route::get('/mock-tests/{id}', [MockTestController::class, 'viewMockTest'])->name('user.MockTest.view');
        Route::get('/mock-tests/{id}/feedback', [MockTestController::class, 'viewMockTestfeedback'])->name('user.MockTest.feedbackview');


        // Edit Profile routes
        Route::get('/edit',[ProfileController::class,'editProfile'])->name('edit.profile');
        Route::put('/edit',[ProfileController::class,'updateProfile'])->name('update.profile');
        // Vip Exams
        Route::get('/vip-exams',[UserVipExamController::class,'vipExam'])->name('vip-exam');
        Route::get('/vip-exams/{dialogue}',[UserVipExamController::class,'vipexamSegment'])->name('vip-exam-segments');
        Route::post('/vip-exams-segments/store-all',[UserVipExamController::class,'submitVipExamResponses'])->name('user.vip-exam-segments.storeAll');
        
        Route::post('/label/{practice}', [UserSegmentController::class, 'updateLabel'])->name('label.update');

        // vocabulary routes
        Route::get('/vocabulary',[VocabularyController::class,'vocabularyView'])->name('vocabulary.view');
        Route::get('/words/{category}',[VocabularyController::class,'wordsView'])->name('vocabulary.words');
        Route::get('/ccl-words', [VocabularyController::class, 'cclWordsview'])->name('vocabulary.ccl-words');
        Route::get('/my-words',[VocabularyController::class,'myWords'])->name('vocabulary.my-words');
        Route::post('/words/increment-view', [VocabularyController::class, 'incrementView'])->name('words.increment');
        Route::post('/my-word/increment-memorized', [VocabularyController::class, 'incrementMemorized'])->name('vocabulary.my-words.memorized');
        // to add  word to my word
        Route::post('/add-my-word', [VocabularyController::class, 'addMyWord'])->name('add.my.word');


        Route::get('/videos', function () {
            return view('naati.users.videos');
        })->name('users.videos');
        Route::get('/faq',[UserFaqController::class,'showFaqs'])->name('users.faq');
        // Route::get('/subscription', function () {
        //     return view('naati.users.faq');
        // })->name('users.subscriptions');


        Route::post('/user-mock-test-dialogues/submit', [UserMockTestDialogueController::class, 'submitResponses'])
        ->name('user.mocktest.submit');

    });
});
// Exclude sample files from catch-all
Route::get('sample/{file}', function ($file) {
    $path = public_path("sample/$file");
    if (file_exists($path)) {
        return response()->download($path);
    }
    abort(404);
});
// Public user login and register views (handled via Fortify config)
// Fortify::loginView(fn() => request()->is('admin/*')
//     ? view('auth.admin-login')
//     : view('auth.login'));

// Fortify::registerView(fn() => view('auth.register'));

/*
|--------------------------------------------------------------------------
| User Management
|--------------------------------------------------------------------------
*/
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
Route::delete('/users/{id}/delete', [UserController::class, 'destroy'])->name('users.destroy');

/*
|--------------------------------------------------------------------------
| Dialogue & Recording Routes
|--------------------------------------------------------------------------
*/


Route::get('/practice', [UserRecordingController::class, 'index'])->name('practice.index');
Route::post('/practice/upload', [UserRecordingController::class, 'store'])->name('practice.store');

Route::post('/recordings', [UserRecordingController::class, 'store'])->name('recordings.store');

/*
|--------------------------------------------------------------------------
| Teacher Review Routes
|--------------------------------------------------------------------------
*/
Route::get('/teacher/reviews', [TeacherController::class, 'index'])->name('teacher.reviews');
Route::post('/teacher/reviews/{id}', [TeacherController::class, 'review'])->name('teacher.reviews.store');

/*
|--------------------------------------------------------------------------
| Test Page
|--------------------------------------------------------------------------
*/
// Route::get('/test', fn() => view('test.test'))->name('test');

/*
|--------------------------------------------------------------------------
| Catch-All & RoutingController (Dynamic Routes)
|--------------------------------------------------------------------------
*/
Route::get('', [RoutingController::class, 'login']);
Route::get('/register', [RoutingController::class, 'register'])->name('register');
Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
Route::get('{any}', [RoutingController::class, 'root'])->name('any');
