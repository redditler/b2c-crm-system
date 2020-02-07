<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Route::get('/test1C', 'StockPointsController@index')->name('test1C');
//Route::get('/test1C_2', 'StockPointsController@sumSalonPay')->name('test1C_2');
//Route::get('/exportXls', 'ExcelController@exportXls')->name('exportXls');
//
//Route::any('/welcome', function (){
//    return view('testxls');
//});

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Password reset link request routes...
//Route::get('password/email', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.email');
//Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
//
//// Password reset routes...
//Route::get('password/reset/{token}/', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
//Route::get('password/reset/', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
//Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');

Route::get('/2fa','PasswordSecurityController@show2faForm');
Route::post('/generate2faSecret','PasswordSecurityController@generate2faSecret')->name('generate2faSecret');
Route::post('/2fa','PasswordSecurityController@enable2fa')->name('enable2fa');
Route::post('/disable2fa','PasswordSecurityController@disable2fa')->name('disable2fa');

Route::post('/verify2FA','HomeController@verify2FA');

Route::post('/2faVerify', function () {
    return redirect(request()->session()->get('_previous')['url']);
})->name('2faVerify')->middleware('2fa');

Route::group(['middleware' => ['auth', '2fa', 'isWorking']], function () {

//    Route::get('/leeds', 'HomeController@index')->name('statistics_new');
    Route::get('/leeds', function () {
        return Redirect::to('/leads');
    });
    Route::get('/', function () {
        return Redirect::to('/leads');
    });
    Route::get('/promo', 'HomeController@promo')->name('promo');
    Route::get('/done', 'HomeController@done')->name('done');
//    Route::get('/contacts', 'HomeController@contacts')->name('contacts');


    Route::delete('/delete2fa/{id}','PasswordSecurityController@delete2fa')->name('delete2fa');
//    Route::get('/daily-report', 'ReportController@dailyReport')->name('daily-report');


    Route::get('/fin-report', 'HomeController@finReport')->name('fin-report');
    Route::get('/statistics', 'HomeController@statistics')->name('statistics');
    Route::any('/statistics_new', 'HomeController@statisticsNew')->name('statisticsNew');
    Route::get('/statisticsRmShow', 'HomeController@statisticsRmShow')->name('statisticsRmShow');
    Route::post('/statisticsRm', 'HomeController@statisticsRm')->name('statisticsRm');
    Route::get('/statisticsChief', 'HomeController@statisticsChief')->name('statisticsChief');
    Route::post('/statisticsDate', 'HomeController@statisticsDate')->name('statisticsDate');
    Route::post('/statisticsSumDate', 'HomeController@statisticsSumDate')->name('statisticsSumDate');


    Route::get('/contacts/history/{phone}', 'ContactsController@historyByPhone')->name('history-by-phone');
    Route::get('/sendmail', 'LeedController@testMail')->name('sendmail');

    // --LEED--
    Route::get('/store_lead', 'LaedsConrroler@storeLead')->name('storeLead');
    Route::post('/lead_create', 'LaedsConrroler@createLead')->name('createLead');
    Route::post('rejectTrue', 'RejectedLeadController@rejectTrue')->name('rejectTrue');
    Route::post('rejectFalse', 'RejectedLeadController@rejectFalse')->name('rejectFalse');

    Route::get('/leads', 'LaedsConrroler@index')->name('leads');
    Route::post('/indexAddEditRemoveColumnData', 'LaedsConrroler@indexAddEditRemoveColumnData')->name('indexAddEditRemoveColumnData');

    Route::get('/leadInWorkShow', 'LaedsConrroler@leadInWorkShow')->name('leadInWorkShow');
    Route::post('/leadInWork', 'LaedsConrroler@leadInWork')->name('leadInWork');

    Route::get('/leadFrozeShow', 'LaedsConrroler@leadFrozeShow')->name('leadFrozeShow');
    Route::post('/leadFroze', 'LaedsConrroler@leadFroze')->name('leadFroze');

    Route::get('/leadOfferShow', 'LaedsConrroler@leadOfferShow')->name('leadOfferShow');
    Route::post('/leadOffer', 'LaedsConrroler@leadOffer')->name('leadOffer');

    Route::get('/leadBilledShow', 'LaedsConrroler@leadBilledShow')->name('leadBilledShow');
    Route::post('/leadBilled', 'LaedsConrroler@leadBilled')->name('leadBilled');

    Route::get('/leadPaidShow', 'LaedsConrroler@leadPaidShow')->name('leadPaidShow');
    Route::post('/leadPaid', 'LaedsConrroler@leadPaid')->name('leadPaid');

    Route::get('/leadCanceledShow', 'LaedsConrroler@leadCanceledShow')->name('leadCanceledShow');
    Route::post('/leadCanceled', 'LaedsConrroler@leadCanceled')->name('leadCanceled');

    Route::post('/leadFilterAll','LaedsConrroler@leadFilterAll')->name('leadFilterAll');
    Route::post('/startLeadFilter','LaedsConrroler@startLeadFilter')->name('startLeadFilter');
    Route::post('/secondLeadFilter','LaedsConrroler@secondLeadFilter')->name('secondLeadFilter');

    Route::post('/updateLead', 'LaedsConrroler@updateLead')->name('updateLead');
    Route::post('/oneClientLead', 'LaedsConrroler@oneClientLead')->name('oneClientLead');

    Route::post('/renderMultiselect','LaedsConrroler@renderMultiselect')->name('renderMultiselect');
    //Route::post('renderFilterBranch', 'LaedsConrroler@renderFilterBranch')->name('renderFilterBranch');
    Route::post('renderFilterBranch', 'LaedsConrroler@renderFilterBranch')->name('renderFilterBranch');
    Route::post('/getStatusLead', 'LaedsConrroler@getStatusLead')->name('getStatusLead');
    //Route::get('/lead', 'LaedsConrroler@lead')->name('lead');
    Route::post('searchCustomerLead', 'LaedsConrroler@searchCustomerLead')->name('searchCustomerLead');

    //--LeadsPromo
    Route::get('/leadsPromo', 'LeadPromoController@index')->name('leadsPromo');
    Route::post('/indexLeadsPromo', 'LeadPromoController@indexLeadsPromo')->name('indexLeadsPromo');



//    Route::get('/leedsAll', 'LeedController@leedsAll')->name('leedsAll');
//    Route::post('/leedsUpdate/{id}', 'LeedController@leedsUpdate')->name('leedsUpdate');

    Route::post('/get-leeds', 'HomeController@getLeeds')->name('get-leeds');
    //Route::post('/get-promo-leeds', 'HomeController@getPromoLeeds')->name('get-promo-leeds');
    Route::post('/get-promo-leeds', 'PromoController@getPromoLeeds')->name('get-promo-leeds');
    Route::post('/update-leeds', 'HomeController@updateLeeds')->name('update-leeds');
    Route::post('/get-done-leeds', 'HomeController@getDoneLeeds')->name('get-done-leeds');
    Route::post('/get-history-leeds', 'HomeController@getHistoryLeeds')->name('get-history-leeds');
    // --LEED--

    //-=-PHONE-=-
        Route::get('/phone-info', 'CallAllController@index')->name('phoneInfo');
        Route::post('/phone-info-table', 'CallAllController@getPhoneTable')->name('phoneInfoTable');
        Route::post('/phone-info/webcall', 'CallAllController@requestWebcall')->name('phoneInfoRequestWebcall');

    //-=-PHONE-=-

    //ORGANIZER
//    Route::get('/organizer', 'OrganizerController@index')->name('organizer');
    Route::get('/events', 'EventController@index')->name('events');
    Route::post('/events', 'EventController@setEvent')->name('events');
    Route::post('/showEvents', 'EventController@showEvents')->name('showEvents');
    Route::delete('/event_delete/{id}','EventController@deleteEvent')->name('eventDelete');

    //ORGANIZER


    Route::post('/get-contacts', 'ContactsController@getContacts')->name('get-contacts');
    Route::post('/get-daily-reports', 'DailyReportController@getDailyReports')->name('get-daily-reports');
    Route::post('/get-stat-daily-reports', 'DailyReportController@getStatDailyReports')->name('get-stat-daily-reports');
    Route::post('/get-monthly-plans', 'MonthlyPlanController@getMonthlyPlans')->name('get-monthly-plans');
    Route::post('/get-fin-plans', 'FinPlanController@getFinPlans')->name('get-fin-plans');


    //********************************************************************************************************//

    Route::get('/employees', 'UsersController@employees')->name('employees');

    //********************************************************************************************************//

    Route::group(['middleware' => 'is.analyst'], function () {

        // ****************************************** EXCEL testing ******************************************//\\
        Route::post('/exportXls', 'ContactNewController@exportXls')->name('exportXls');
        Route::post('/createLeadXls', 'LaedsConrroler@createLeadXls')->name('createLeadXls');
        // ****************************************** EXCEL testing ******************************************//\\

        Route::resource('users', 'UsersController', [
            'names' => [
                'index' => 'users',
                'destroy' => 'user.destroy'
            ]
        ]);
        Route::post('userShowTable', 'UsersController@showUser')->name('showUser');
        Route::post('changeUser2fa', 'UsersController@changeUser2fa');
        Route::post('restartUser2fa', 'UsersController@restartUser2fa');
        Route::post('firedUser', 'UsersController@firedUser');
        Route::post('userEditBranch', 'UsersController@userEditBranch');

        Route::post('/user_transfer', 'UsersController@userTransfer')->name('userTransfer');
        Route::post('/userAllGroup', 'HomeController@getAllGroup')->name('userAllGroup');
        Route::post('/getUserWitGroup', 'UsersController@getUserWitGroup')->name('getUserWitGroup');
        Route::resource('roles', 'RolesController');
        Route::resource('regions', 'RegionsController');
        Route::post('/changeApi', 'RegionsController@changeApi')->name('changeApi');
        Route::resource('leadStatus', 'LeadStatusesController');
        Route::resource('leadType', 'LeadTypeController');
        Route::resource('leadReceive', 'LeadReceiveController');
        Route::resource('groups', 'GroupController');

        //---------------Callscripts begin
        Route::get('/callscripts/management', 'CallscriptsManagementController@default')->name('callscriptsManagementDefault');
        Route::post('/callscripts/management/topics', 'CallscriptsManagementController@topicsManager')->name('callscriptsTopicsManager');
        Route::get('/callscripts/management/directed-{question}', 'CallscriptsManagementController@default')->name('callscriptsManagementDirected');
        Route::post('/callscripts/management/getQuestionDetails', 'CallscriptsManagementController@getQuestionData')->name('callscriptsGetQuestionDetails');
        Route::post('/callscripts/management/update_question', 'CallscriptsManagementController@updateQuestion')->name('callscriptsUpdateQuestion');
        Route::post('/callscripts/management/create_question', 'CallscriptsManagementController@createQuestion')->name('callscriptsCreateQuestion');
        Route::post('/callscripts/management/remove_answer', 'CallscriptsHomeController@removeAnswer')->name('callscriptsRemoveAnswer');
        Route::post('/callscripts/management/describe_failure', 'CallscriptsHomeController@describeFailure')->name('callscriptsDescribeFailure');
        Route::get('/callscripts/management/list', 'CallscriptsManagementController@dialoguesList')->name('callscriptsDialoguesList');
        Route::get('/callscripts/management/dialogue/{callid}', 'CallscriptsManagementController@replayDialogue')->name('callscriptsReplayDialogue');
        Route::get('/callscripts/management/improvement/{improvementId}', 'CallscriptsManagementController@viewImprovement')->name('callscriptsViewImprovement');
        Route::get('/callscripts/management/noticements', 'CallscriptsManagementController@viewNoticements')->name('callscriptsViewNoticements');
        Route::post('/callscripts/management/noticements', 'CallscriptsManagementController@manageNoticements')->name('callscriptsManageNoticements');
        //---------------Callscripts end

        //----------------------Video-Courses
        Route::post('video-courses/upload', 'VideoCoursesController@upload')->name('videocourses.upload');
        Route::post('video-courses/manage', 'VideoCoursesController@manage')->name('videocourses.manage');
        Route::get('video-courses/proxy/videos/{video_id}', 'VideoCoursesController@proxy')->name('videocourses.proxy');
        Route::get('video-courses/views', 'VideoCoursesController@getViews')->name('videocourses.getViews');
        Route::get('video-courses/views/{video_id}', 'VideoCoursesController@getViews')->name('videocourses.getViewsByID');
        Route::get('video-courses/categories', 'VideoCoursesController@categoriesIndex')->name('videocourses.categoriesIndex');
        Route::post('video-courses/categories', 'VideoCoursesController@categoriesManage')->name('videocourses.categoriesManage');
        //----------------------Video-Courses

    });

    //----------------------Video-Courses
    Route::get('video-courses', 'VideoCoursesController@index')->name('videocourses.index');
    Route::post('video-courses/view/{video_id}', 'VideoCoursesController@setViewed')->name('videocourses.setViewed');
    Route::get('video-courses/{category_id}', 'VideoCoursesController@detailed')->name('videocourses.detailed');
    //----------------------Video-Courses

    //----------------------Dashboards
    Route::get('dashboards', 'DashboardsController@index')->name('Dashboards.index');
    Route::post('DashboardsInit', 'DashboardsController@init')->name('Dashboards.init');
    Route::get('/dashboards/details/telephony', 'DashboardsController@telephonyDetailed')->name('Dashboards.detailedTelephony');
    Route::get('/dashboards/details/{detailsType}', 'DashboardsController@detailedGraph')->name('Dashboards.detailedGraph');
    Route::get('dashboards/details/{detailsType}/{id}', 'DashboardsController@branchDetails')->where('id', '[0-9]+')->name('Dashboards.details');
    //----------------------Dashboards

    Route::resource('managerReports', 'Chief\ManagerReportsController');
    Route::post('managerReportTable', 'Chief\ManagerReportsController@getReports');
    Route::resource('monthly-plan', 'MonthlyPlanController');
    Route::resource('salons', 'SalonsController');
    Route::post('/setStatus', 'SalonsController@setStatus')->name('setStatus');
    Route::resource('rm', 'RegionlManagerConreller');
    Route::resource('fin-reports', 'FinPlanController');


   //------------------------CONTACT
    Route::resource('contact', 'ContactNewController');
    Route::post('/indexShow', 'ContactNewController@indexShow')->name('indexShow');
    Route::post('/showComment', 'ContactNewController@showComment')->name('showComment');
    Route::post('/showHistory', 'ContactNewController@showHistory')->name('showHistory');
    Route::post('/showContactPhone', 'ContactNewController@showContactPhone')->name('showContactPhone');
    Route::post('/addContactComment', 'ContactNewController@addContactComment')->name('addContactComment');
    Route::post('/contactPhoneUpdate', 'ContactNewController@contactPhoneUpdate')->name('contactPhoneUpdate');
    //------------------------CONTACT

    //------------------------CONTACT_QUALITY
    Route::get('/contact_quality', 'ContactQualityController@index')->name('contactQuality');
    Route::post('/add_contact_quality', 'ContactQualityController@addContactQuality')->name('addContactQuality');
    //------------------------CONTACT_QUALITY

    //------------------------Contact-Price-Category
    Route::resource('/contactPriceCategory', 'ContactPriceCategoryController');
    //------------------------Contact-Price-Category

    //----------------------CustomerSource
    Route::group(['middleware' => 'onlyAdmin'], function (){
        Route::get('/sources', 'CustomerSourceController@index')->name('sources');
        Route::post('/add-sources', 'CustomerSourceController@addSources')->name('addSources');
        Route::post('/show-edit-sources/{id}', 'CustomerSourceController@showEditSources')->name('showEditSources');
        Route::post('/edit-sources/{id}', 'CustomerSourceController@editSources')->name('EditSources');

    });
    //----------------------CustomerSource

    //----------------------Callscripts
    Route::get('/callscripts', 'CallscriptsHomeController@begin')->name('callscriptsBegin');
    Route::post('/callscripts/questionnaire', 'CallscriptsHomeController@getQuestion')->name('callscriptsGetQuestion');
    Route::post('/callscripts/getNoticements', 'CallscriptsHomeController@getNoticements')->name('callscriptsGetNoticements');
    Route::post('/callscripts/improve', 'CallscriptsHomeController@improveQuestion')->name('callscriptsImproveQuestion');

    Route::get('/callscripts/getTopicCall', 'CallscriptsHomeController@getTopicCall')->name('callscriptsGetTopicCall');
    Route::post('/callscripts/getQuickQuestion', 'CallscriptsHomeController@getQuickQuestion')->name('callscriptsGetQuickQuestion');
    
    
    //----------------------Callscripts

//    Route::resource('contacts', 'ContactsController', [
//        'names' => [
//            'index' => 'contacts',
//            'destroy' => 'contacts.destroy'
//        ]
//    ]);

    Route::resource('daily-reports', 'DailyReportController', [
        'names' => [
            'destroy' => 'contacts.destroy'
        ]
    ]);

});

//--------------------Callmanager API
Route::post('/callmanager/279d46f6-c8b3-4a5f-8dd8-ac03e1d6eb75', 'CallAllController@getCallerID')->name('callmanagerGetCallerID');
//--------------------Callmanager API
