<?php

use App\Http\Controllers\Blogs\BlogsController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\Course\WebCoursesController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\FileUpload;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JournalEntriesController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\SendEmailController;
use App\Http\Controllers\ThirdpartyController;
use App\Models\Faq;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('send-email-pdf', [SendEmailController::class, 'index']);
Route::get('/', function () {
    return view('welcome');
});

Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoice_index');
Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/otp', [HomeController::class, 'otp_page'])->name('otp_page');
Route::get('/exit', [HomeController::class, 'exit'])->name('exit');
Route::post('/checkotp', [HomeController::class, 'checkotp'])->name('checkotp');
Route::get('account', [HomeController::class, 'account'])->name('account');
Route::get('switch', [HomeController::class, 'switch'])->name('switch');
Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    // return what you want
});

Route::prefix('/admin')->group(function () {
    Route::get('/', [HomeController::class, 'view_admins'])->name('view_admins');
    Route::get('/view/{id}', [HomeController::class, 'view_admin'])->name('view_admin');
    Route::get('/float', [FinanceController::class, 'view_float'])->name('view_float');
    Route::post('/float/add', [FinanceController::class, 'add_float'])->name('add_float');
    Route::post('/float/approve/{id}', [FinanceController::class, 'update_float'])->name('update_float');
    Route::get('/view/{id}', [HomeController::class, 'view_admin'])->name('view_admin');
    Route::get('/edit/{id}', [HomeController::class, 'edit_admin'])->name('edit_admin');
    Route::post('/update/{id}', [HomeController::class, 'update_admin'])->name('update_admin');
    Route::get('/export', [HomeController::class, 'export_admin'])->name('export_admin');
    Route::post('/changer', [HomeController::class, 'changer'])->name('changer');
    Route::post('/add', [HomeController::class, 'add_admin'])->name('add_admin');
    Route::post('/password-change/{id}', [HomeController::class, 'password_changer'])->name('password_changer');

    
});
Route::prefix('/customer')->group(function () {
    Route::get('/add', [HomeController::class, 'add'])->name('add');
    Route::get('/pending', [HomeController::class, 'pending_customers'])->name('pending');
    Route::get('/approved', [HomeController::class, 'all'])->name('approved');
    Route::get('/view/{id}', [HomeController::class, 'view_customer'])->name('view_customer');
    Route::get('/edit/{id}', [HomeController::class, 'edit_customer'])->name('edit_customer');
    Route::post('/register', [HomeController::class, 'register'])->name('register_customer');
    Route::post('/update/{id}', [HomeController::class, 'update_customer'])->name('update_customer');
    Route::post('/assign/group/{id}', [HomeController::class, 'assign_group'])->name('assign_group');
    Route::post('/update/correct/{id}', [HomeController::class, 'update_correct_customer'])->name('update_correct_customer');
    Route::get('/resetpin/{id}', [HomeController::class, 'reset_pin'])->name('reset_pin');
    Route::get('/alerts-on/{id}', [HomeController::class, 'alerts_on'])->name('alerts_on');
    Route::get('/online/{id}', [HomeController::class, 'online_access'])->name('online_access');
});
Route::prefix('/messages')->group(function () {
    Route::get('/', [HomeController::class, 'view_messages'])->name('view_messages');
    Route::post('/record', [HomeController::class, 'record_message'])->name('record_message');
});
Route::prefix('/loantypes')->group(function () {
    Route::get('/', [HomeController::class, 'view_loantypes'])->name('loantypes');
    Route::post('/register', [HomeController::class, 'register_loantype'])->name('register_loantype');
    Route::get('/add', [HomeController::class, 'add_loantypes'])->name('add_loantypes');
    Route::get('/view/{id}', [HomeController::class, 'single_loantype'])->name('single_loantype');
    Route::get('/edit/{id}', [HomeController::class, 'edit_loantype'])->name('edit_loantype');
    Route::post('/update', [HomeController::class, 'update_loantype'])->name('update_loantype');
});

Route::prefix('/loans')->group(function () {
    Route::get('/', [HomeController::class, 'pending_loans'])->name('pending_loans');
    Route::get('/edit/{id}', [HomeController::class, 'edit_loans'])->name('edit_loans');
    Route::post('/reject/{id}', [HomeController::class, 'reject_loans'])->name('reject_loans');
    Route::post('/update/confirm/{id}', [HomeController::class, 'action_loan'])->name('action_loan');
    Route::post('/assign/{id}', [HomeController::class, 'assign_loan'])->name('assign_loan');
    Route::get('/view/{id}', [HomeController::class, 'view_loans'])->name('view_loans');
    Route::get('/approved', [HomeController::class, 'approved_loans'])->name('approved_loans');
    Route::get('/disbursed', [HomeController::class, 'disbursed_loans'])->name('disbursed_loans');
    Route::get('/paid', [HomeController::class, 'paid_loans'])->name('paid_loans');
    Route::get('/today', [HomeController::class, 'loans_today'])->name('loans_today');
    Route::get('/weekly', [HomeController::class, 'loans_next_week'])->name('loans_next_week');
    Route::get('/overdue', [HomeController::class, 'overdue_loans'])->name('overdue_loans');
    Route::get('/rejected', [HomeController::class, 'rejected_loans'])->name('rejected_loans');
    Route::post('/update', [HomeController::class, 'update_loan'])->name('update_loan');
    Route::post('/manual', [HomeController::class, 'update_manual_loan'])->name('update_manual_loan');
    Route::post('/user/{id}', [HomeController::class, 'update_manual_loan_single'])->name('update_manual_loan_single');
    Route::post('/waive/{id}', [HomeController::class, 'waive_loan'])->name('waive_loan');
    Route::post('/correct', [HomeController::class, 'correct_loan'])->name('correct_loan');
    Route::post('/note/{id}', [HomeController::class, 'add_note'])->name('add_note');
    Route::post('/topup/{id}', [HomeController::class, 'topup_loan'])->name('topup_loan');
    Route::post('/pause/{id}', [HomeController::class, 'add_pause'])->name('add_pause');
    Route::post('/activate/{id}', [HomeController::class, 'activate_penalty'])->name('activate_penalty');
    Route::post('/generate/{id}', [HomeController::class, 'generate_statement'])->name('generate_statement');
    Route::get('/repayments', [HomeController::class, 'loans_repayments_monthly'])->name('loans_repayments_monthly');
});


Route::prefix('/savings')->group(function () {
    Route::get('/', [HomeController::class, 'all_savings'])->name('all_savings');
    Route::get('/per-product/{id}', [HomeController::class, 'savings_per_product'])->name('savings_per_product');
    Route::post('/product/add', [HomeController::class, 'product_add'])->name('product_add');
    Route::post('/product/update/{id}', [HomeController::class, 'product_update'])->name('product_update');
    Route::get('/product/view/{id}', [HomeController::class, 'view_product'])->name('view_product');
    Route::get('/products', [HomeController::class, 'products'])->name('products');
    Route::get('/view/{id}', [HomeController::class, 'view_savings'])->name('view_savings');
    Route::get('/awithdrawals', [HomeController::class, 'approved_withdrawals'])->name('approved_withdrawals');
    Route::get('/pwithdrawals', [HomeController::class, 'pending_withdrawals'])->name('pending_withdrawals');
    Route::get('/withdrawal/{id}', [HomeController::class, 'view_withdrawal'])->name('view_withdrawal');
    Route::post('/withdrawal/process/{id}', [HomeController::class, 'process_withdrawal'])->name('process_withdrawal');
    Route::post('/manual', [HomeController::class, 'manual_savings'])->name('manual_savings');
    Route::post('/user/{id}', [HomeController::class, 'manual_user_savings'])->name('manual_user_savings');
    Route::post('/approve/withdrawal/{id}', [HomeController::class, 'approve_withdrawal'])->name('approve_withdrawal');
    Route::post('/reject/withdrawal/{id}', [HomeController::class, 'reject_withdrawal'])->name('reject_withdrawal');
    Route::post('/approve/user/withdrawal/{id}/{user}', [HomeController::class, 'approve_user_withdrawal'])->name('approve_user_withdrawal');
    Route::post('/reject/user/withdrawal/{id}/{user}', [HomeController::class, 'reject_user_withdrawal'])->name('reject_user_withdrawal');
    Route::post('/payment/{id}', [HomeController::class, 'approve_pay'])->name('approve_pay');
    Route::post('/initiate/manual', [HomeController::class, 'initiate_manual'])->name('initiate_manual');
    Route::post('/initiate/user/{id}', [HomeController::class, 'initiate_user_manual'])->name('initiate_user_manual');
    Route::post('/statement/{phone}/{id}', [HomeController::class, 'generate_savings_statement'])->name('generate_savings_statement');    
    Route::prefix('/groups')->group(function () {
        Route::post('/assign', [HomeController::class, 'assign_product'])->name('assign_product');
        Route::post('/add', [HomeController::class, 'add_groups'])->name('add_groups');
        Route::post('/remove/{phone}/{id}', [HomeController::class, 'remove_group'])->name('remove_group');
        Route::get('/view/{id}', [HomeController::class, 'savings_per_group'])->name('savings_per_group');
    });
});

Route::prefix('/welfare')->group(function () {
    Route::get('/', [HomeController::class, 'welfare_members'])->name('welfare_members');
    Route::get('/view/{id}', [HomeController::class, 'view_welfare'])->name('view_welfare');
    Route::post('/add', [HomeController::class, 'add_welfare'])->name('add_welfare');
});

Route::prefix('/shares')->group(function () {
    Route::get('/', [HomeController::class, 'shares_members'])->name('shares_members');
    Route::get('/view/{id}', [HomeController::class, 'view_shares'])->name('view_shares');
    Route::post('/add', [HomeController::class, 'add_shares'])->name('add_shares'); 
});
Route::prefix('/investment')->group(function () {
    Route::get('/', [HomeController::class, 'all_investment'])->name('all_investment');
    Route::get('/pending', [HomeController::class, 'pending_investment'])->name('pending_investment');
    Route::get('/approved', [HomeController::class, 'approved_investment'])->name('approved_investment');
    Route::post('/register', [ThirdpartyController::class, 'add_investor'])->name('add_investor');
    Route::get('/view/{id}', [HomeController::class, 'view_investment'])->name('view_investment');
    Route::post('/deposit/{id}', [ThirdpartyController::class, 'deposit_investor'])->name('deposit_investor');
    Route::post('/withdrawal/{id}', [ThirdpartyController::class, 'withdrawal_investor'])->name('withdrawal_investor');
    Route::post('/reject/{id}', [ThirdpartyController::class, 'withdrawal_reject'])->name('withdrawal_reject');
    Route::post('/approve/{id}', [ThirdpartyController::class, 'withdrawal_approve'])->name('withdrawal_approve');
    Route::post('/transfer', [ThirdpartyController::class, 'transfer_shares'])->name('transfer_shares');
});
Route::prefix('/reports')->group(function () {
    Route::get('/', [JournalEntriesController::class, 'index'])->name('index');
    Route::post('/accounts/add', [JournalEntriesController::class, 'add_account'])->name('add_account');
    Route::get('/balance', [JournalEntriesController::class, 'balance_reports'])->name('balance_reports');
    Route::get('/income', [JournalEntriesController::class, 'income_reports'])->name('income_reports');
    Route::get('/consolidated', [JournalEntriesController::class, 'consolidated_reports'])->name('consolidated_reports');
    Route::get('/ledgers', [JournalEntriesController::class, 'ledgers_reports'])->name('ledgers_reports');
    Route::get('/trial', [JournalEntriesController::class, 'trial_reports'])->name('trial_reports');
    Route::get('/monthly', [JournalEntriesController::class, 'monthly_reports'])->name('monthly_reports');
    Route::get('/accumulated', [JournalEntriesController::class, 'accumulated_reports'])->name('accumulated_reports');

    Route::prefix('/filter')->group(function () {
        Route::post('/balance', [JournalEntriesController::class, 'filter_balance'])->name('filter_balance');
        Route::post('/income', [JournalEntriesController::class, 'filter_income'])->name('filter_income');
        Route::post('/consolidated', [JournalEntriesController::class, 'filter_consolidated'])->name('filter_consolidated');
        Route::post('/accumulated', [JournalEntriesController::class, 'filter_accumulated_reports'])->name('filter_accumulated_reports');
    });
});
Route::prefix('/installments')->group(function () {
    Route::get('/', [JournalEntriesController::class, 'all_installment'])->name('all_installment');
    Route::get('/today', [JournalEntriesController::class, 'today_installment'])->name('today_installment');
    Route::get('/arrears', [JournalEntriesController::class, 'arrears_installment'])->name('arrears_installment');
});
Route::prefix('/payments')->group(function () {
    Route::get('/', [HomeController::class, 'payments'])->name('payments');
    Route::post('/record', [HomeController::class, 'record'])->name('record');
});
Route::prefix('/billing')->group(function () {
    Route::get('/', [HomeController::class, 'view_billing'])->name('view_billing');
    Route::post('/pay/old', [HomeController::class, 'pay_billing_old'])->name('pay_billing_old');
    Route::post('/pay/{id}', [HomeController::class, 'pay_billing'])->name('pay_billing');
    Route::post('/payall', [HomeController::class, 'pay_all_bills'])->name('pay_all_bills');
});
Route::prefix('/repayments')->group(function () {
    Route::get('/', [HomeController::class, 'loans_repayments'])->name('loans_repayments');
    Route::get('/savings', [HomeController::class, 'savings_repayments'])->name('savings_repayments');
    Route::get('/savings/view/{id}', [HomeController::class, 'view_savings_repayments'])->name('view_savings_repayments');
    Route::get('/view/{id}', [HomeController::class, 'view_repayments'])->name('view_repayments');
    Route::post('/update', [HomeController::class, 'update_repayments'])->name('update_repayments');
    Route::post('/update/savings', [HomeController::class, 'update_savings_repayments'])->name('update_savings_repayments');
});
Route::prefix('/b4m')->group(function () {
    Route::get('/', [HomeController::class, 'b4m_members'])->name('b4m_members');
    Route::get('/open', [HomeController::class, 'open_b4m'])->name('open_b4m');
    Route::get('/closed', [HomeController::class, 'closed_b4m'])->name('closed_b4m');
    Route::get('/recent', [HomeController::class, 'recent_b4m'])->name('recent_b4m');
    Route::get('/view/{id}', [HomeController::class, 'view_b4m'])->name('view_b4m');
    Route::get('/commit/{id}', [HomeController::class, 'commit_b4m'])->name('commit_b4m');
    Route::post('/create-pool', [HomeController::class, 'create_pool'])->name('create_pool');
    Route::post('/add', [HomeController::class, 'add_b4m_member'])->name('add_b4m_member');
    Route::post('/contribute', [HomeController::class, 'contribute_b4m'])->name('contribute');
    Route::post('/update', [HomeController::class, 'update_repayments'])->name('update_b4m_repayments');
    Route::post('/lock', [HomeController::class, 'lock_b4m'])->name('lock_b4m');
});

Route::prefix('/uploads')->group(function () {
    Route::post('/upload-customers', [FileUpload::class, 'uploadContent'])->name('customers');
    Route::post('/upload-loans', [FileUpload::class, 'uploadContent'])->name('loans');
    Route::post('/upload-schedules', [FileUpload::class, 'uploadContent'])->name('schedules');
    Route::post('/upload-repayments', [FileUpload::class, 'uploadContent'])->name('repayments');
    Route::post('/chat', [FileUpload::class, 'chat_upload'])->name('chat_upload');
});
Route::prefix('/chats')->group(function () {
    Route::get('/', [HomeController::class, 'view_chats'])->name('view_chats');
    Route::get('/view/{id}', [HomeController::class, 'single_chat'])->name('single_chat');
    Route::post('/add', [HomeController::class, 'add_chat'])->name('add_chat');
    Route::post('/compose', [HomeController::class, 'compose'])->name('compose');
    Route::post('/upload/{id}', [HomeController::class, 'upload_compose'])->name('upload_compose');
});

Route::prefix('/category')->group(function () {
    Route::get('/', [JournalEntriesController::class, 'view_category'])->name('view_category');
    Route::post('/add', [JournalEntriesController::class, 'add_category'])->name('add_category');
});


Route::prefix('/settings')->group(function () {
    Route::get('/', [HomeController::class, 'view_settings'])->name('view_settings');
    Route::post('/add', [HomeController::class, 'new_variable'])->name('new_variable');
    Route::post('/socials', [HomeController::class, 'socials'])->name('socials');
    Route::get('/variables', [HomeController::class, 'add_variables'])->name('add_variables');
    Route::get('/sync', [HomeController::class, 'bulk_online_access'])->name('bulk_online_access');
    Route::post('/phone/update', [HomeController::class, 'phone_update'])->name('phone_update');
});
Route::prefix('/learning')->group(function () {
    Route::get('/', [WebCoursesController::class, 'learning_dashboard'])->name('learning_dashboard');
    Route::prefix('/instructors')->group(function () {
        Route::get('/', [WebCoursesController::class, 'list_instructors'])->name('list_instructors');
        Route::get('/view/{id}', [WebCoursesController::class, 'view_instructor'])->name('view_instructor');
        Route::post('/new', [WebCoursesController::class, 'add_instructor'])->name('add_instructor');
        Route::post('/avatar/{id}', [WebCoursesController::class, 'update_profile'])->name('update_profile');
        Route::post('/update/{id}', [WebCoursesController::class, 'update_instructor'])->name('update_instructor');
    });
    Route::prefix('/students')->group(function () {
        Route::get('/', [WebCoursesController::class, 'list_students'])->name('list_students');
        Route::post('/new', [WebCoursesController::class, 'add_student'])->name('add_student');
    });

    Route::prefix('/blogs')->group(function () {
        Route::get('/', [BlogsController::class, 'list_blogs'])->name('list_blogs');
        Route::get('/new', [BlogsController::class, 'new_blog'])->name('new_blog');
        Route::get('/view/{id}', [BlogsController::class, 'view_blog'])->name('view_blog');
        Route::get('/edit/{id}', [BlogsController::class, 'edit_blog'])->name('edit_blog');
        Route::post('/update/{id}', [BlogsController::class, 'update_blog'])->name('update_blog');
        Route::post('/register', [BlogsController::class, 'register_blog'])->name('register_blog');
        Route::get('/per-category/{id}', [BlogsController::class, 'blogs_per_category'])->name('blogs_per_category');
        Route::post('/new/category', [BlogsController::class, 'new_category'])->name('new_category');
        Route::get('/categories', [BlogsController::class, 'list_categories'])->name('list_categories');
        Route::get('/categories/edit/{id}', [BlogsController::class, 'edit_category'])->name('edit_category');
        Route::post('/category/update/{id}', [BlogsController::class, 'update_category'])->name('update_category');
        Route::prefix('/comments')->group(function () {
            Route::get('/', [BlogsController::class, 'list_comments'])->name('list_comment');
            Route::post('/add/{id}', [BlogsController::class, 'new_comment'])->name('new_comment');
            Route::post('/approve/{id}', [BlogsController::class, 'approve_comment'])->name('approve_comment');
            Route::post('/reject/{id}', [BlogsController::class, 'reject_comment'])->name('reject_comment');
        });
    });
    Route::prefix('/courses')->group(function () {
        Route::get('/', [WebCoursesController::class, 'list_courses'])->name('list_courses');
        Route::get('/featured', [WebCoursesController::class, 'list_featured_courses'])->name('list_featured_courses');
        Route::get('/new', [WebCoursesController::class, 'new_course'])->name('new_course');
        Route::get('/view/{id}', [WebCoursesController::class, 'view_course'])->name('view_course');
        Route::post('/create', [WebCoursesController::class, 'create_course'])->name('create_course');
        Route::post('/update/{id}', [WebCoursesController::class, 'update_course'])->name('update_course');
        Route::get('/types', [WebCoursesController::class, 'list_types'])->name('list_types');
        Route::get('/categories', [WebCoursesController::class, 'list_course_categories'])->name('list_course_categories');
        Route::post('/new_type', [WebCoursesController::class, 'new_type'])->name('new_type');
        Route::post('/new_category', [WebCoursesController::class, 'new_course_category'])->name('new_course_category');
        Route::prefix('/discounts')->group(function () {
            Route::get('/', [WebCoursesController::class, 'list_discounts'])->name('list_discounts');
            Route::post('/add', [WebCoursesController::class, 'new_discount'])->name('new_discount');
            Route::post('/apply/{id}', [WebCoursesController::class, 'apply_discount'])->name('apply_discount');
        });
        Route::prefix('/featured')->group(function () {
            Route::post('/apply/{id}', [WebCoursesController::class, 'apply_featured'])->name('apply_featured');
        });
        Route::prefix('/reviews')->group(function () {
            Route::get('/', [WebCoursesController::class, 'list_reviews'])->name('list_reviews');
        });
        Route::prefix('/comments')->group(function () {
            Route::get('/', [WebCoursesController::class, 'list_comments'])->name('list_comments');
        });
        Route::prefix('/faq')->group(function () {
            Route::post('/add/{id}', [WebCoursesController::class, 'add_faq'])->name('add_faq');
            Route::post('/edit/{book}/{id}', [WebCoursesController::class, 'edit_faq'])->name('edit_faq');
            Route::get('/delete/{book}/{id}', [WebCoursesController::class, 'delete_faq'])->name('delete_faq');
        });
        Route::prefix('/chapters')->group(function () {
            Route::post('/add/{id}', [WebCoursesController::class, 'add_chapter'])->name('add_chapter');
            Route::post('/edit/{book}/{id}', [WebCoursesController::class, 'edit_chapter'])->name('edit_chapter');
            Route::get('/delete/{book}/{id}', [WebCoursesController::class, 'delete_chapter'])->name('delete_chapter');
            Route::prefix('/lesson')->group(function () {
                Route::get('/add/{course}/{id}', [WebCoursesController::class, 'add_chapter_lesson'])->name('add_chapter_lesson');
                Route::post('/{course}/{id}/create', [WebCoursesController::class, 'create_chapter_lesson'])->name('create_chapter_lesson');
            });
        });
    }); 
   
}); 
Route::prefix('/branches')->group(function () {
    Route::get('/', [BranchController::class, 'index'])->name('branch_index'); 
    Route::get('/select', [BranchController::class, 'select'])->name('branch_select'); 
    Route::post('/add', [BranchController::class, 'store'])->name('branch_add'); 
    Route::post('/edit/{id}', [BranchController::class, 'update'])->name('branch_update'); 
    Route::post('/update', [BranchController::class, 'update_branch'])->name('branch_update');
    Route::post('/delete/{id}', [BranchController::class, 'destroy'])->name('branch_delete'); 
    Route::post('/restore/{id}', [BranchController::class, 'restore'])->name('branch_restore'); 
    Route::post('/recruit/{id}', [BranchController::class, 'recruit'])->name('branch_recruit'); 
});
Route::prefix('/downloads')->group(function () {
    Route::get('/loan/{id}', [PdfController::class, 'download_loan'])->name('download_loan');
    Route::get('/graphs', [PdfController::class, 'graphs'])->name('graphs');
    Route::get('/graphs-pdf', [PdfController::class, 'graphsPdf'])->name('graphsPdf');
});

Route::prefix('/faqs')->group(function () {
    Route::get('/', [FAQController::class, 'list_faqs'])->name('list_faqs');
    Route::post('/add', [FAQController::class, 'add_faq'])->name('add_faqs');
    Route::get('/edit/{id}', [FAQController::class, 'edit_faq'])->name('edit_faqs');
    Route::post('/update/{id}', [FAQController::class, 'update_faq'])->name('update_faqs');
    Route::post('/delete/{id}', [FAQController::class, 'delete_faq'])->name('delete_faqs');
});

//accounts