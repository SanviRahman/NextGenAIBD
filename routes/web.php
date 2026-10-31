<?php

use App\Http\Controllers\Backend\AdminAboutController;
use App\Http\Controllers\Backend\AdminAdminController;
use App\Http\Controllers\Backend\AdminApplyCourseController;
use App\Http\Controllers\Backend\AdminAppreanceController;
use App\Http\Controllers\Backend\AdminBannerController;
use App\Http\Controllers\Backend\HomepageHeroController;
use App\Http\Controllers\Backend\HomepageSpotlightController;
use App\Http\Controllers\Backend\HomepageServicesController;
use App\Http\Controllers\Backend\HomepageConsultationController;
use App\Http\Controllers\Backend\HomepageRatingsController;
use App\Http\Controllers\Backend\HomepageBenefitsController;
use App\Http\Controllers\Backend\HomepageClientsController;
use App\Http\Controllers\Backend\HomepageTestimonialsController;
use App\Http\Controllers\Backend\HomepageGalleryController;
use App\Http\Controllers\Frontend\ConsultationController;
use App\Http\Controllers\Backend\AdminBlogsController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\AdminCountryController;
use App\Http\Controllers\Backend\AdminCourseController;
use App\Http\Controllers\Backend\AdminIpAddressController;
use App\Http\Controllers\Backend\AdminMembershipController;
use App\Http\Controllers\Backend\AdminPersonalInFoController;
use App\Http\Controllers\Backend\AdminProjectController;
use App\Http\Controllers\Backend\AdminSEOController;
use App\Http\Controllers\Backend\AdminServicesController;
use App\Http\Controllers\Backend\AdminSuccessStoryController;
use App\Http\Controllers\Backend\AdminTeamsController;
use App\Http\Controllers\Backend\AdminUserController;
use App\Http\Controllers\Backend\AdminVisitorCounterController;
use App\Http\Controllers\Backend\AdminWebsiteInfoController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\ApplyCourseController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\CourseController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\MyselfController;
use App\Http\Controllers\Frontend\ProjectController;
use App\Http\Controllers\Frontend\ServiceController;
use App\Http\Controllers\Frontend\SuccessStoryController;
use App\Http\Controllers\Frontend\TeamController;
use App\Http\Controllers\Settings\SettingsCacheController;
use App\Http\Controllers\Settings\SettingsSitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


#=====>[AppUpNext]==================>=>{ARI}======> # [{( = )}] <== FRONTEND ==>
#=====>[AppUpNext]==================>=>{ARI}===>( -- )==> # [{( START )}] <== FRONTEND ==>
#=====>[AppUpNext]==================>=>{ARI}======> # [{( = )}] <== FRONTEND ==>


#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(HomeController)==> # [{( START )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
Route::get('/', [HomeController::class, 'home_Index'])->name('home_Index');

// Step-04: Public consultation (web CSRF + rate limit).
Route::post('/consultations', [ConsultationController::class, 'store'])->middleware('throttle:5,10')->name('consultations.store');
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(HomeController)==> # [{( END )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(CourseController)==> # [{( START )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
Route::group(['prefix' => 'course'], function () {
    Route::get('/', [CourseController::class, 'frontend_Course_Index'])->name('frontend_Course_Index');
    Route::get('/{course}', [CourseController::class, 'frontend_Course_Single'])->name('frontend_Course_Single');
});
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(CourseController)==> # [{( END )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(ServiceController)==> # [{( START )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
Route::group(['prefix' => 'service'], function () {
    Route::get('/', [ServiceController::class, 'frontend_Service_Index'])->name('frontend_Service_Index');
    Route::get('/{service}', [ServiceController::class, 'frontend_Service_Single'])->name('frontend_Service_Single');
});
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(ServiceController)==> # [{( END )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(ProjectController)==> # [{( START )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
Route::group(['prefix' => 'project'], function () {
    Route::get('/', [ProjectController::class, 'frontend_Project_Index'])->name('frontend_Project_Index');
    Route::get('/{project}', [ProjectController::class, 'frontend_Project_Single'])->name('frontend_Project_Single');
});
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(ProjectController)==> # [{( END )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(BlogController)==> # [{( START )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
Route::group(['prefix' => 'blog'], function () {
    Route::get('/', [BlogController::class, 'frontend_Blog_Index'])->name('frontend_Blog_Index');
    Route::get('/{blog}', [BlogController::class, 'frontend_Blog_Single'])->name('frontend_Blog_Single');
});
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(BlogController)==> # [{( END )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(TeamController)==> # [{( START )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
Route::group(['prefix' => 'team'], function () {
    Route::get('/', [TeamController::class, 'frontend_Team_Index'])->name('frontend_Team_Index');
    Route::get('/{team}', [TeamController::class, 'frontend_Team_Single'])->name('frontend_Team_Single');
});
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(TeamController)==> # [{( END )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(AboutController)==> # [{( START )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
Route::group(['prefix' => 'about'], function () {
    Route::get('/', [AboutController::class, 'frontend_About_Index'])->name('frontend_About_Index');
});
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(AboutController)==> # [{( END )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(MyselfController)==> # [{( START )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
Route::group(['prefix' => 'me'], function () {
    Route::get('/', [MyselfController::class, 'frontend_MySelf_Index'])->name('frontend_MySelf_Index');
});
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(MyselfController)==> # [{( END )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(ApplyCourseController)==> # [{( START )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
Route::group(['prefix' => 'apply'], function () {
    Route::get('/', [ApplyCourseController::class, 'frontend_Apply_Index'])->name('frontend_Apply_Index');
    Route::post('/db', [ApplyCourseController::class, 'frontend_ApplyDB_Index'])->name('frontend_ApplyDB_Index');
});
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(ApplyCourseController)==> # [{( END )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


// #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
// #=====>[AppUpNext]===>=>{ARI}===>(ApplyCourseController)==> # [{( START )}] <== FRONTEND ==>
// #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
// Route::group(['prefix' => 'apply'], function () {
//     Route::get('/', [ApplyCourseController::class, 'frontend_Apply_Index'])->name('frontend_Apply_Index');
// });
// #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
// #=====>[AppUpNext]===>=>{ARI}===>(ApplyCourseController)==> # [{( END )}] <== FRONTEND ==>
// #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(SuccessStoryController)==> # [{( START )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
Route::group(['prefix' => 'success-story'], function () {
    Route::get('/', [SuccessStoryController::class, 'frontend_Success_Story_Index'])->name('frontend_Success_Story_Index');
});
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(SuccessStoryController)==> # [{( END )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(ContactController)==> # [{( START )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
Route::group(['prefix' => 'contact'], function () {
    Route::get('/', [ContactController::class, 'frontend_Contact_Index'])->name('frontend_Contact_Index');
});
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
#=====>[AppUpNext]===>=>{ARI}===>(ContactController)==> # [{( END )}] <== FRONTEND ==>
#=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>

#=====>[AppUpNext]==================>=>{ARI}======> # [{( = )}] <== FRONTEND ==>
#=====>[AppUpNext]==================>=>{ARI}===>( -- )==> # [{( END )}] <== FRONTEND ==>
#=====>[AppUpNext]==================>=>{ARI}======> # [{( = )}] <== FRONTEND ==>




#=====>[AppUpNext]==================>=>{ARI}======> # [{( = )}] <== BACKEND ==>
#=====>[AppUpNext]==================>=>{ARI}===>( -- )==> # [{( START )}] <== BACKEND ==>
#=====>[AppUpNext]==================>=>{ARI}======> # [{( = )}] <== BACKEND ==>

Route::group(['prefix' => 'admin'], function () {

    #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
    #=====>[AppUpNext]===>=>{ARI}===>(AdminController)==> # [{( START )}] <== BACKEND ==>
    #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
    Route::group(['middleware' => 'guest:admin'], function () {
        Route::match(['GET', 'POST'], '/login', [AdminController::class, 'admin_Login'])->name('admin_Login');
        Route::get('/register', [AdminController::class, 'admin_Register'])->name('admin_Register');
        Route::post('/register', [AdminController::class, 'admin_Register_DB'])->name('admin_Register_DB');
    });
    Route::get('/logout', [AdminController::class, 'admin_Logout'])->name('admin_Logout');
    #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
    #=====>[AppUpNext]===>=>{ARI}===>(AdminController)==> # [{( END )}] <== BACKEND ==>
    #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


    Route::group(['prefix' => 'dashboard', 'middleware' => 'admin'], function () {
        // Step-09: public photo mosaic, private CRUD; reuse abouts with gallery role.
        Route::prefix('homepage/gallery')->name('admin.gallery.')->group(function () {
            Route::get('/', [HomepageGalleryController::class, 'index'])->name('index');
            Route::put('/settings', [HomepageGalleryController::class, 'updateSettings'])->name('settings.update');
            Route::post('/photos', [HomepageGalleryController::class, 'store'])->name('items.store');
            Route::put('/photos/{item}', [HomepageGalleryController::class, 'update'])->name('items.update');
            Route::delete('/photos/{item}', [HomepageGalleryController::class, 'destroy'])->name('items.destroy');
        });

        // Step-08: Admin-guarded client video testimonials on existing client_reviews.
        Route::prefix('homepage/testimonials')->name('admin.testimonials.')->group(function () {
            Route::get('/', [HomepageTestimonialsController::class, 'index'])->name('index');
            Route::put('/settings', [HomepageTestimonialsController::class, 'updateSettings'])->name('settings.update');
            Route::post('/videos', [HomepageTestimonialsController::class, 'store'])->name('videos.store');
            Route::put('/videos/{review}', [HomepageTestimonialsController::class, 'update'])->name('videos.update');
            Route::delete('/videos/{review}', [HomepageTestimonialsController::class, 'destroy'])->name('videos.destroy');
        });

        // Step-07: client showcase reuses abouts; authorized client logos only.
        Route::prefix('homepage/clients')->name('admin.clients.')->group(function () {
            Route::get('/', [HomepageClientsController::class, 'index'])->name('index');
            Route::put('/settings', [HomepageClientsController::class, 'updateSettings'])->name('settings.update');
            Route::post('/logos', [HomepageClientsController::class, 'store'])->name('logos.store');
            Route::put('/logos/{client}', [HomepageClientsController::class, 'update'])->name('logos.update');
            Route::delete('/logos/{client}', [HomepageClientsController::class, 'destroy'])->name('logos.destroy');
        });

        // Step-06: Homepage Benefits, safely stored in existing abouts records.
        Route::prefix('homepage/benefits')->name('admin.benefits.')->group(function () {
            Route::get('/', [HomepageBenefitsController::class, 'index'])->name('index');
            Route::put('/settings', [HomepageBenefitsController::class, 'updateSettings'])->name('settings.update');
            Route::post('/items', [HomepageBenefitsController::class, 'store'])->name('items.store');
            Route::put('/items/{benefit}', [HomepageBenefitsController::class, 'update'])->name('items.update');
            Route::delete('/items/{benefit}', [HomepageBenefitsController::class, 'destroy'])->name('items.destroy');
        });

        // Step-05: approved review platforms; inherited existing admin guard.
        Route::prefix('homepage/ratings')->name('admin.ratings.')->group(function () {
            Route::get('/', [HomepageRatingsController::class, 'index'])->name('index');
            Route::put('/settings', [HomepageRatingsController::class, 'updateSettings'])->name('settings.update');
            Route::post('/platforms', [HomepageRatingsController::class, 'store'])->name('platforms.store');
            Route::put('/platforms/{platform}', [HomepageRatingsController::class, 'update'])->name('platforms.update');
            Route::delete('/platforms/{platform}', [HomepageRatingsController::class, 'destroy'])->name('platforms.destroy');
        });

        // Step-04: Admin-only consultation settings/inbox.
        Route::prefix('homepage/consultation')->name('admin.consultation.')->group(function () {
            Route::get('/', [HomepageConsultationController::class, 'index'])->name('index');
            Route::put('/settings', [HomepageConsultationController::class, 'updateSettings'])->name('settings.update');
            Route::get('/requests/{consultation}', [HomepageConsultationController::class, 'show'])->name('requests.show');
            Route::put('/requests/{consultation}', [HomepageConsultationController::class, 'updateLead'])->name('requests.update');
        });

        // Step-03 — isolated Homepage Services settings/curation; existing Service CRUD preserved.
        Route::prefix('homepage/services')->name('admin.services.')->group(function () {
            Route::get('/', [HomepageServicesController::class, 'index'])->name('index');
            Route::put('/settings', [HomepageServicesController::class, 'updateSettings'])->name('settings.update');
            Route::put('/featured', [HomepageServicesController::class, 'updateFeatured'])->name('featured.update');
        });

        // Spotlight management — admin guard inherited from existing dashboard group.
        Route::prefix('homepage/spotlight')->name('admin.spotlight.')->group(function () {
            Route::get('/', [HomepageSpotlightController::class, 'index'])->name('index');
            Route::put('/settings', [HomepageSpotlightController::class, 'updateSettings'])->name('settings.update');
            Route::post('/videos', [HomepageSpotlightController::class, 'storeVideo'])->name('videos.store');
            Route::put('/videos/{video}', [HomepageSpotlightController::class, 'updateVideo'])->name('videos.update');
            Route::delete('/videos/{video}', [HomepageSpotlightController::class, 'deleteVideo'])->name('videos.destroy');
            Route::post('/mentions', [HomepageSpotlightController::class, 'storeMention'])->name('mentions.store');
            Route::put('/mentions/{mention}', [HomepageSpotlightController::class, 'updateMention'])->name('mentions.update');
            Route::delete('/mentions/{mention}', [HomepageSpotlightController::class, 'deleteMention'])->name('mentions.destroy');
        });

        // Hero management only. All writes use POST/PUT/DELETE + CSRF, never GET.
        Route::prefix('homepage/hero')->name('admin.hero.')->group(function () {
            Route::get('/', [HomepageHeroController::class, 'index'])->name('index');
            Route::put('/settings', [HomepageHeroController::class, 'updateSettings'])->name('settings.update');
            Route::post('/slides', [HomepageHeroController::class, 'storeSlide'])->name('slides.store');
            Route::put('/slides/{slide}', [HomepageHeroController::class, 'updateSlide'])->name('slides.update');
            Route::delete('/slides/{slide}', [HomepageHeroController::class, 'deleteSlide'])->name('slides.destroy');
            Route::post('/highlights', [HomepageHeroController::class, 'storeHighlight'])->name('highlights.store');
            Route::put('/highlights/{highlight}', [HomepageHeroController::class, 'updateHighlight'])->name('highlights.update');
            Route::delete('/highlights/{highlight}', [HomepageHeroController::class, 'deleteHighlight'])->name('highlights.destroy');
        });
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(DashboardController)==> # [{( START )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        Route::get('/', [DashboardController::class, 'backend_Home_Index'])->name('backend_Home_Index');
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(DashboardController)==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminCourseController)==> # [{( START )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        Route::group(['prefix' => 'courses'], function () {
            Route::get('/', [AdminCourseController::class, 'backend_Courses_Index'])->name('backend_Courses_Index');
            Route::get('/active', [AdminCourseController::class, 'backend_Courses_Active'])->name('backend_Courses_Active');
            Route::get('/pending', [AdminCourseController::class, 'backend_Courses_Pending'])->name('backend_Courses_Pending');
            Route::get('/drafts', [AdminCourseController::class, 'backend_Courses_Drafts'])->name('backend_Courses_Drafts');
            Route::get('/search', [AdminCourseController::class, 'backend_Courses_Search'])->name('backend_Courses_Search');
            Route::get('/create', [AdminCourseController::class, 'backend_Courses_Create'])->name('backend_Courses_Create');
            Route::post('/store', [AdminCourseController::class, 'backend_Courses_Store'])->name('backend_Courses_Store');
            Route::get('/edit/{course}', [AdminCourseController::class, 'backend_Courses_Edit'])->name('backend_Courses_Edit');
            Route::post('/update/{course}', [AdminCourseController::class, 'backend_Courses_Update'])->name('backend_Courses_Update');
            Route::get('/status/{course}', [AdminCourseController::class, 'backend_Courses_Status'])->name('backend_Courses_Status');
            Route::post('/discount/{course}', [AdminCourseController::class, 'backend_Courses_Discount'])->name('backend_Courses_Discount');
            Route::get('/destory/{course}', [AdminCourseController::class, 'backend_Courses_Destory'])->name('backend_Courses_Destory');
        });
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminCourseController)==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminCountryController)==> # [{( START )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        Route::group(['prefix' => 'country'], function () {
            Route::get('/', [AdminCountryController::class, 'backend_Country_Index'])->name('backend_Country_Index');
            Route::get('/active', [AdminCountryController::class, 'backend_Country_Active'])->name('backend_Country_Active');
            Route::get('/pending', [AdminCountryController::class, 'backend_Country_Pending'])->name('backend_Country_Pending');
            Route::get('/drafts', [AdminCountryController::class, 'backend_Country_Drafts'])->name('backend_Country_Drafts');
            Route::get('/search', [AdminCountryController::class, 'backend_Country_Search'])->name('backend_Country_Search');
            Route::get('/create', [AdminCountryController::class, 'backend_Country_Create'])->name('backend_Country_Create');
            Route::post('/store', [AdminCountryController::class, 'backend_Country_Store'])->name('backend_Country_Store');
            Route::get('/edit/{country}', [AdminCountryController::class, 'backend_Country_Edit'])->name('backend_Country_Edit');
            Route::post('/update/{country}', [AdminCountryController::class, 'backend_Country_Update'])->name('backend_Country_Update');
            Route::get('/status/{country}', [AdminCountryController::class, 'backend_Country_Status'])->name('backend_Country_Status');
            Route::post('/discount/{country}', [AdminCountryController::class, 'backend_Country_Discount'])->name('backend_Country_Discount');
            Route::get('/destory/{country}', [AdminCountryController::class, 'backend_Country_Destory'])->name('backend_Country_Destory');
        });
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminCountryController)==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminServicesController)==> # [{( START )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        Route::group(['prefix' => 'service'], function () {
            Route::get('/', [AdminServicesController::class, 'backend_Services_Index'])->name('backend_Services_Index');
            Route::get('/active', [AdminServicesController::class, 'backend_Services_Active'])->name('backend_Services_Active');
            Route::get('/pending', [AdminServicesController::class, 'backend_Services_Pending'])->name('backend_Services_Pending');
            Route::get('/drafts', [AdminServicesController::class, 'backend_Services_Drafts'])->name('backend_Services_Drafts');
            Route::get('/search', [AdminServicesController::class, 'backend_Services_Search'])->name('backend_Services_Search');
            Route::get('/create', [AdminServicesController::class, 'backend_Services_Create'])->name('backend_Services_Create');
            Route::post('/store', [AdminServicesController::class, 'backend_Services_Store'])->name('backend_Services_Store');
            Route::get('/edit/{service}', [AdminServicesController::class, 'backend_Services_Edit'])->name('backend_Services_Edit');
            Route::post('/update/{service}', [AdminServicesController::class, 'backend_Services_Update'])->name('backend_Services_Update');
            Route::get('/status/{service}', [AdminServicesController::class, 'backend_Services_Status'])->name('backend_Services_Status');
            Route::get('/destory/{service}', [AdminServicesController::class, 'backend_Services_Destory'])->name('backend_Services_Destory');
        });
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminServicesController)==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminProjectController)==> # [{( START )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        Route::group(['prefix' => 'project'], function () {
            Route::get('/', [AdminProjectController::class, 'backend_Projects_Index'])->name('backend_Projects_Index');
            Route::get('/active', [AdminProjectController::class, 'backend_Projects_Active'])->name('backend_Projects_Active');
            Route::get('/pending', [AdminProjectController::class, 'backend_Projects_Pending'])->name('backend_Projects_Pending');
            Route::get('/drafts', [AdminProjectController::class, 'backend_Projects_Drafts'])->name('backend_Projects_Drafts');
            Route::get('/search', [AdminProjectController::class, 'backend_Projects_Search'])->name('backend_Projects_Search');
            Route::get('/create', [AdminProjectController::class, 'backend_Projects_Create'])->name('backend_Projects_Create');
            Route::post('/store', [AdminProjectController::class, 'backend_Projects_Store'])->name('backend_Projects_Store');
            Route::get('/edit/{project}', [AdminProjectController::class, 'backend_Projects_Edit'])->name('backend_Projects_Edit');
            Route::post('/update/{project}', [AdminProjectController::class, 'backend_Projects_Update'])->name('backend_Projects_Update');
            Route::get('/status/{project}', [AdminProjectController::class, 'backend_Projects_Status'])->name('backend_Projects_Status');
            Route::get('/destory/{project}', [AdminProjectController::class, 'backend_Projects_Destory'])->name('backend_Projects_Destory');
        });
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminProjectController)==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminApplyCourseController)==> # [{( START )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        Route::group(['prefix' => 'course-apply'], function () {
            Route::get('/', [AdminApplyCourseController::class, 'backend_ApplyCourse_Index'])->name('backend_ApplyCourse_Index');
            Route::get('/active', [AdminApplyCourseController::class, 'backend_ApplyCourse_Active'])->name('backend_ApplyCourse_Active');
            Route::get('/pending', [AdminApplyCourseController::class, 'backend_ApplyCourse_Pending'])->name('backend_ApplyCourse_Pending');
            Route::get('/drafts', [AdminApplyCourseController::class, 'backend_ApplyCourse_Drafts'])->name('backend_ApplyCourse_Drafts');
            Route::get('/search', [AdminApplyCourseController::class, 'backend_ApplyCourse_Search'])->name('backend_ApplyCourse_Search');
            Route::get('/create', [AdminApplyCourseController::class, 'backend_ApplyCourse_Create'])->name('backend_ApplyCourse_Create');
            Route::post('/store', [AdminApplyCourseController::class, 'backend_ApplyCourse_Store'])->name('backend_ApplyCourse_Store');
            Route::get('/edit/{applyCourse}', [AdminApplyCourseController::class, 'backend_ApplyCourse_Edit'])->name('backend_ApplyCourse_Edit');
            Route::post('/update/{applyCourse}', [AdminApplyCourseController::class, 'backend_ApplyCourse_Update'])->name('backend_ApplyCourse_Update');
            Route::get('/status/{applyCourse}', [AdminApplyCourseController::class, 'backend_ApplyCourse_Status'])->name('backend_ApplyCourse_Status');
            Route::get('/destory/{applyCourse}', [AdminApplyCourseController::class, 'backend_ApplyCourse_Destory'])->name('backend_ApplyCourse_Destory');
        });
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminApplyCourseController)==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminMembershipController)==> # [{( START )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        Route::group(['prefix' => 'membership'], function () {
            Route::get('/', [AdminMembershipController::class, 'backend_Membership_Index'])->name('backend_Membership_Index');
            Route::get('/active', [AdminMembershipController::class, 'backend_Membership_Active'])->name('backend_Membership_Active');
            Route::get('/pending', [AdminMembershipController::class, 'backend_Membership_Pending'])->name('backend_Membership_Pending');
            Route::get('/drafts', [AdminMembershipController::class, 'backend_Membership_Drafts'])->name('backend_Membership_Drafts');
            Route::get('/search', [AdminMembershipController::class, 'backend_Membership_Search'])->name('backend_Membership_Search');
            Route::get('/create', [AdminMembershipController::class, 'backend_Membership_Create'])->name('backend_Membership_Create');
            Route::post('/store', [AdminMembershipController::class, 'backend_Membership_Store'])->name('backend_Membership_Store');
            Route::get('/edit/{membership}', [AdminMembershipController::class, 'backend_Membership_Edit'])->name('backend_Membership_Edit');
            Route::post('/update/{membership}', [AdminMembershipController::class, 'backend_Membership_Update'])->name('backend_Membership_Update');
            Route::get('/status/{membership}', [AdminMembershipController::class, 'backend_Membership_Status'])->name('backend_Membership_Status');
            Route::get('/destory/{membership}', [AdminMembershipController::class, 'backend_Membership_Destory'])->name('backend_Membership_Destory');
        });
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminMembershipController)==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminBlogsController)==> # [{( START )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        Route::group(['prefix' => 'blogs'], function () {
            Route::get('/', [AdminBlogsController::class, 'backend_Blogs_Index'])->name('backend_Blogs_Index');
            Route::get('/active', [AdminBlogsController::class, 'backend_Blogs_Active'])->name('backend_Blogs_Active');
            Route::get('/pending', [AdminBlogsController::class, 'backend_Blogs_Pending'])->name('backend_Blogs_Pending');
            Route::get('/drafts', [AdminBlogsController::class, 'backend_Blogs_Drafts'])->name('backend_Blogs_Drafts');
            Route::get('/search', [AdminBlogsController::class, 'backend_Blogs_Search'])->name('backend_Blogs_Search');
            Route::get('/create', [AdminBlogsController::class, 'backend_Blogs_Create'])->name('backend_Blogs_Create');
            Route::post('/store', [AdminBlogsController::class, 'backend_Blogs_Store'])->name('backend_Blogs_Store');
            Route::get('/edit/{blog}', [AdminBlogsController::class, 'backend_Blogs_Edit'])->name('backend_Blogs_Edit');
            Route::post('/update/{blog}', [AdminBlogsController::class, 'backend_Blogs_Update'])->name('backend_Blogs_Update');
            Route::get('/status/{blog}', [AdminBlogsController::class, 'backend_Blogs_Status'])->name('backend_Blogs_Status');
            Route::get('/destory/{blog}', [AdminBlogsController::class, 'backend_Blogs_Destory'])->name('backend_Blogs_Destory');
        });
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminBlogsController)==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminAdminController)==> # [{( START )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        Route::group(['prefix' => 'admins'], function () {
            Route::get('/', [AdminAdminController::class, 'backend_Admins_Index'])->name('backend_Admins_Index');
            Route::get('/active', [AdminAdminController::class, 'backend_Admins_Active'])->name('backend_Admins_Active');
            Route::get('/pending', [AdminAdminController::class, 'backend_Admins_Pending'])->name('backend_Admins_Pending');
            Route::get('/drafts', [AdminAdminController::class, 'backend_Admins_Drafts'])->name('backend_Admins_Drafts');
            Route::get('/search', [AdminAdminController::class, 'backend_Admins_Search'])->name('backend_Admins_Search');
            Route::get('/create', [AdminAdminController::class, 'backend_Admins_Create'])->name('backend_Admins_Create');
            Route::post('/store', [AdminAdminController::class, 'backend_Admins_Store'])->name('backend_Admins_Store');
            Route::get('/edit/{admin}', [AdminAdminController::class, 'backend_Admins_Edit'])->name('backend_Admins_Edit');
            Route::post('/update/{admin}', [AdminAdminController::class, 'backend_Admins_Update'])->name('backend_Admins_Update');
            Route::get('/status/{admin}', [AdminAdminController::class, 'backend_Admins_Status'])->name('backend_Admins_Status');
            Route::get('/destory/{admin}', [AdminAdminController::class, 'backend_Admins_Destory'])->name('backend_Admins_Destory');
        });
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminAdminController)==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminAdminController)==> # [{( START )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [AdminUserController::class, 'backend_Users_Index'])->name('backend_Users_Index');
            Route::get('/active', [AdminUserController::class, 'backend_Users_Active'])->name('backend_Users_Active');
            Route::get('/pending', [AdminUserController::class, 'backend_Users_Pending'])->name('backend_Users_Pending');
            Route::get('/drafts', [AdminUserController::class, 'backend_Users_Drafts'])->name('backend_Users_Drafts');
            Route::get('/search', [AdminUserController::class, 'backend_Users_Search'])->name('backend_Users_Search');
            Route::get('/create', [AdminUserController::class, 'backend_Users_Create'])->name('backend_Users_Create');
            Route::post('/store', [AdminUserController::class, 'backend_Users_Store'])->name('backend_Users_Store');
            Route::get('/edit/{user}', [AdminUserController::class, 'backend_Users_Edit'])->name('backend_Users_Edit');
            Route::post('/update/{user}', [AdminUserController::class, 'backend_Users_Update'])->name('backend_Users_Update');
            Route::get('/status/{user}', [AdminUserController::class, 'backend_Users_Status'])->name('backend_Users_Status');
            Route::get('/destory/{user}', [AdminUserController::class, 'backend_Users_Destory'])->name('backend_Users_Destory');
        });
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminUserController)==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminAboutController)==> # [{( START )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        Route::group(['prefix' => 'abouts'], function () {
            Route::get('/', [AdminAboutController::class, 'backend_Abouts_Index'])->name('backend_Abouts_Index');
            // Route::get('/active', [AdminAboutController::class, 'backend_Abouts_Active'])->name('backend_Abouts_Active');
            // Route::get('/pending', [AdminAboutController::class, 'backend_Abouts_Pending'])->name('backend_Abouts_Pending');
            // Route::get('/drafts', [AdminAboutController::class, 'backend_Abouts_Drafts'])->name('backend_Abouts_Drafts');
            // Route::get('/search', [AdminAboutController::class, 'backend_Abouts_Search'])->name('backend_Abouts_Search');
            // Route::get('/create', [AdminAboutController::class, 'backend_Abouts_Create'])->name('backend_Abouts_Create');
            // Route::post('/store', [AdminAboutController::class, 'backend_Abouts_Store'])->name('backend_Abouts_Store');
            Route::get('/edit', [AdminAboutController::class, 'backend_Abouts_Edit'])->name('backend_Abouts_Edit');
            Route::post('/update', [AdminAboutController::class, 'backend_Abouts_Update'])->name('backend_Abouts_Update');
            // Route::get('/edit/{about}', [AdminAboutController::class, 'backend_Abouts_Edit'])->name('backend_Abouts_Edit');
            // Route::post('/update/{about}', [AdminAboutController::class, 'backend_Abouts_Update'])->name('backend_Abouts_Update');
            // Route::get('/status/{about}', [AdminAboutController::class, 'backend_Abouts_Status'])->name('backend_Abouts_Status');
            // Route::get('/destory/{about}', [AdminAboutController::class, 'backend_Abouts_Destory'])->name('backend_Abouts_Destory');
        });
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminAboutController)==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminBannerController)==> # [{( START )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        Route::group(['prefix' => 'banner'], function () {
            Route::get('/', [AdminBannerController::class, 'backend_Banner_Index'])->name('backend_Banner_Index');
            Route::get('/edit', [AdminBannerController::class, 'backend_Banner_Edit'])->name('backend_Banner_Edit');
            Route::post('/update', [AdminBannerController::class, 'backend_Banner_Update'])->name('backend_Banner_Update');
        });
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminBannerController)==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminSuccessStoryController)==> # [{( START )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        Route::group(['prefix' => 'success-story'], function () {
            Route::get('/', [AdminSuccessStoryController::class, 'backend_Success_Story_Index'])->name('backend_Success_Story_Index');
            Route::get('/edit', [AdminSuccessStoryController::class, 'backend_Success_Story_Edit'])->name('backend_Success_Story_Edit');
            Route::post('/update', [AdminSuccessStoryController::class, 'backend_Success_Story_Update'])->name('backend_Success_Story_Update');
        });
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminSuccessStoryController)==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminWebsiteInfoController)==> # [{( START )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        Route::group(['prefix' => 'wensite-info'], function () {
            Route::get('/', [AdminWebsiteInfoController::class, 'backend_Website_Info_Index'])->name('backend_Website_Info_Index');
            Route::get('/edit', [AdminWebsiteInfoController::class, 'backend_Website_Info_Edit'])->name('backend_Website_Info_Edit');
            Route::post('/update', [AdminWebsiteInfoController::class, 'backend_Website_Info_Update'])->name('backend_Website_Info_Update');
        });
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminWebsiteInfoController)==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminPersonalInFoController)==> # [{( START )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        Route::group(['prefix' => 'personal-info'], function () {
            Route::get('/', [AdminPersonalInFoController::class, 'backend_Personal_Info_Index'])->name('backend_Personal_Info_Index');
            Route::get('/edit', [AdminPersonalInFoController::class, 'backend_Personal_Info_Edit'])->name('backend_Personal_Info_Edit');
            Route::post('/update', [AdminPersonalInFoController::class, 'backend_Personal_Info_Update'])->name('backend_Personal_Info_Update');
        });
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminPersonalInFoController)==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminTeamsController)==> # [{( START )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        Route::group(['prefix' => 'teams'], function () {
            Route::get('/', [AdminTeamsController::class, 'backend_Teams_Index'])->name('backend_Teams_Index');
            Route::get('/active', [AdminTeamsController::class, 'backend_Teams_Active'])->name('backend_Teams_Active');
            Route::get('/pending', [AdminTeamsController::class, 'backend_Teams_Pending'])->name('backend_Teams_Pending');
            Route::get('/drafts', [AdminTeamsController::class, 'backend_Teams_Drafts'])->name('backend_Teams_Drafts');
            Route::get('/search', [AdminTeamsController::class, 'backend_Teams_Search'])->name('backend_Teams_Search');
            Route::get('/create', [AdminTeamsController::class, 'backend_Teams_Create'])->name('backend_Teams_Create');
            Route::post('/store', [AdminTeamsController::class, 'backend_Teams_Store'])->name('backend_Teams_Store');
            Route::get('/edit/{team}', [AdminTeamsController::class, 'backend_Teams_Edit'])->name('backend_Teams_Edit');
            Route::post('/update/{team}', [AdminTeamsController::class, 'backend_Teams_Update'])->name('backend_Teams_Update');
            Route::get('/status/{team}', [AdminTeamsController::class, 'backend_Teams_Status'])->name('backend_Teams_Status');
            Route::get('/destory/{team}', [AdminTeamsController::class, 'backend_Teams_Destory'])->name('backend_Teams_Destory');
        });
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminTeamsController)==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminIpAddressController)==> # [{( START )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        Route::group(['prefix' => 'ip-address'], function () {
            Route::get('/', [AdminIpAddressController::class, 'backend_IP_Address_Index'])->name('backend_IP_Address_Index');
            Route::get('/search', [AdminIpAddressController::class, 'backend_IP_Address_Search'])->name('backend_IP_Address_Search');
            Route::post('/store', [AdminIpAddressController::class, 'backend_IP_Address_Store'])->name('backend_IP_Address_Store');
            Route::get('/status/{ipAddress}', [AdminIpAddressController::class, 'backend_IP_Address_Status'])->name('backend_IP_Address_Status');
            Route::get('/destory/{ipAddress}', [AdminIpAddressController::class, 'backend_IP_Address_Destory'])->name('backend_IP_Address_Destory');
        });
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminIpAddressController)==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminVisitorCounterController)==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        Route::group(['prefix' => 'website-visitor'], function () {
            Route::get('/ip-address', [AdminVisitorCounterController::class, 'backend_Website_Visitor'])->name('backend_Website_Visitor');
        });
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminVisitorCounterController)==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminSEOController)==> # [{( START )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        Route::group(['prefix' => 'seo'], function () {
            Route::get('/', [AdminSEOController::class, 'backend_Seo_Index'])->name('backend_Seo_Index');
            Route::get('/edit', [AdminSEOController::class, 'backend_Seo_Edit'])->name('backend_Seo_Edit');
            Route::post('/update', [AdminSEOController::class, 'backend_Seo_Update'])->name('backend_Seo_Update');
            Route::get('/status', [AdminSEOController::class, 'backend_Seo_Status'])->name('backend_Seo_Status');
            Route::get('/destory', [AdminSEOController::class, 'backend_Seo_Destory'])->name('backend_Seo_Destory');
        });
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
        #=====>[AppUpNext]===>=>{ARI}===>(AdminSEOController)==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


        #=====>[AppUpNext]==================>=>{ARI}======> # [{( = )}] <== BACKEND ==>
        #=====>[AppUpNext]==================>=>{ARI}===>( Admin settings )==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]==================>=>{ARI}======> # [{( = )}] <== BACKEND ==>
        Route::group(['prefix' => 'settings'], function () {

            #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
            #=====>[AppUpNext]===>=>{ARI}===>( AdminAppreanceController )==> # [{( START )}] <== BACKEND ==>
            #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
            Route::group(['prefix' => 'theme-options'], function () {
                Route::get('/', [AdminAppreanceController::class, 'backend_Settings_Logo'])->name('backend_Settings_Logo');
                Route::post('/update', [AdminAppreanceController::class, 'backend_Settings_Logo_Update'])->name('backend_Settings_Logo_Update');
            });
            #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
            #=====>[AppUpNext]===>=>{ARI}===>( AdminAppreanceController )==> # [{( END )}] <== BACKEND ==>
            #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


            #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
            #=====>[AppUpNext]===>=>{ARI}===>( SettingsSitemapController )==> # [{( START )}] <== BACKEND ==>
            #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
            Route::group(['prefix' => 'sitemap'], function () {
                Route::get('/', [SettingsSitemapController::class, 'admin_Settings_Sitemap'])->name('admin_Settings_Sitemap');
                Route::get('/update', [SettingsSitemapController::class, 'admin_Settings_Sitemap_Generate'])->name('admin_Settings_Sitemap_Generate');
            });
            #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
            #=====>[AppUpNext]===>=>{ARI}===>( SettingsSitemapController )==> # [{( END )}] <== BACKEND ==>
            #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>


            #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
            #=====>[AppUpNext]===>=>{ARI}===>( SettingsCacheController )==> # [{( START )}] <== BACKEND ==>
            #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
            Route::group(['prefix' => 'cache'], function () {
                Route::get('/', [SettingsCacheController::class, 'admin_Settings_Cache'])->name('admin_Settings_Cache');
                Route::get('/cache-clear', [SettingsCacheController::class, 'admin_Settings_Cache_Clear'])->name('admin_Settings_Cache_Clear');
                Route::get('/cache-cache', [SettingsCacheController::class, 'admin_Settings_Cache_Cache'])->name('admin_Settings_Cache_Cache');
                Route::get('/cache-optimize', [SettingsCacheController::class, 'admin_Settings_Cache_Optimize'])->name('admin_Settings_Cache_Optimize');
            });
            #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>
            #=====>[AppUpNext]===>=>{ARI}===>( SettingsCacheController )==> # [{( END )}] <== BACKEND ==>
            #=====>[AppUpNext]=================>=>{ARI}======> # [{( = )}] ==>

        });
        #=====>[AppUpNext]==================>=>{ARI}======> # [{( = )}] <== BACKEND ==>
        #=====>[AppUpNext]==================>=>{ARI}===>( Admin settings )==> # [{( END )}] <== BACKEND ==>
        #=====>[AppUpNext]==================>=>{ARI}======> # [{( = )}] <== BACKEND ==>
    });
});

// Route::get('/', function () {
//     return view('welcome');
// });

require __DIR__ . '/auth.php';
