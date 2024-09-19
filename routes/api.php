<?php

use App\Http\Controllers\API\CourseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\B4MController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\BuyForMe;
use App\Http\Controllers\Course\CategoriesController;
use App\Http\Controllers\Course\CoursesController;
use App\Http\Controllers\Course\ProvidersController;
use App\Http\Controllers\Course\StudentsController;
use App\Http\Controllers\JengaController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FCMController;
use App\Http\Controllers\Health\DrugsController;
use App\Http\Controllers\IPayController;
use App\Http\Controllers\KopoController;
use App\Http\Controllers\LoansController;
use App\Http\Controllers\Mailer;
use App\Http\Controllers\SavingsController;
use App\Http\Controllers\MpesaController;
use App\Http\Controllers\ParametersController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\Statistics;
use App\Http\Controllers\TillController;
use App\Http\Controllers\TinyController;

 
// Route::prefix('/course')->group(function () {
//     Route::get('/', [CourseController::class, 'index']); 
// });
 
Route::prefix('/testing')->group(function () {
    Route::get('sample', [EmailController::class, 'test_email_local']);
    Route::get('/info', [EmailController::class, 'php_information']);
    Route::get('basic_email', [EmailController::class, 'basic_email']);
});
Route::prefix('/qr')->group(function () {
    Route::post('generate', [QRCodeController::class, 'index']);
});
Route::get('getAvailableBalance', [SavingsController::class, 'getAvailableBalance']);
Route::prefix('/courses')->group(function () {
    Route::get('/config', [CoursesController::class, 'config']);
    Route::post('/login', [StudentsController::class, 'login_courses']);
    Route::post('/forget-password', [StudentsController::class, 'forget_password']);
    Route::prefix('/register')->group(function () {
        Route::post('/step/1', [StudentsController::class, 'step_one_register']);
        Route::post('/step/2', [StudentsController::class, 'step_two_register']);
        Route::post('/step/3', [StudentsController::class, 'step_three_register']);
    });
});


Route::prefix('/kopo')->group(function () {
    Route::post('/token', [KopoController::class, 'generate_token']);
    Route::post('/stk', [KopoController::class, 'initiate_stk']);
    Route::post('/response', [KopoController::class, 'response']);
});


Route::prefix('/user')->group(function () {
    Route::post('/create-account', [AuthenticationController::class, 'createAccount']);
    Route::post('/signin', [AuthenticationController::class, 'signin']);
    Route::post('/reset', [AuthenticationController::class, 'reset']);
});

Route::prefix('/customer')->group(function () {
    Route::get('/token/{id}', [CustomerController::class, 'autoload_token']);
    Route::post('/signin', [CustomerController::class, 'login']);
    Route::post('/signup', [CustomerController::class, 'store']);
    Route::post('/code', [CustomerController::class, 'send_code']);
    Route::post('/verify_code', [CustomerController::class, 'verify_code']);
    Route::post('/confirm_pass', [CustomerController::class, 'confirm_pass']);
});

Route::prefix('/reminders')->group(function () { 
    
    Route::get('/next_week', [ReminderController::class, 'loans_due_next_week']);   
    Route::get('/send_next_week', [ReminderController::class, 'send_loans_due_next_week']); 
    Route::get('/log', [ReminderController::class, 'log_reminder']);
    Route::get('/over', [ReminderController::class, 'generate_overdue_reminder']);
    Route::get('/overdue', [ReminderController::class, 'log_overdue']);
    Route::get('/log/savings', [ReminderController::class, 'log_savings_reminder']);
    Route::get('/send', [ReminderController::class, 'send_reminder']);
    Route::get('/send/savings', [ReminderController::class, 'send_savings_reminder']);
    Route::get('/penalty', [ReminderController::class, 'apply_penalty']);
    Route::get('/test', [ReminderController::class, 'test_mail']);
    Route::post('/manual/penalty', [ReminderController::class, 'manual_penalty']);
});

Route::prefix('/penalties')->group(function () {
    Route::get('/half_rate', [ReminderController::class, 'half_rate']);
});

Route::prefix('/shedules')->group(function () {
    Route::post('/recreate', [LoansController::class, 'recreate_schedule']);
    Route::get('/earnings', [SavingsController::class, 'earn_interest']);
    Route::get('/earn_active_interest', [SavingsController::class, 'earn_active_interest']);
    Route::get('/pay_dates', [BackupController::class, 'reset_dates']);
    Route::get('/daily_payment', [ReminderController::class, 'daily_payment']);
});

Route::prefix('/emails')->group(function () {
    Route::get('/test', [EmailController::class, 'basic_email']);
    Route::get('/mailer', [Mailer::class, 'sample_email']);
});

Route::prefix('/receivables')->group(function () {
    Route::get('/callback', [MpesaController::class, 'store']);
    Route::post('/external', [IPayController::class, 'ipaycallback']);
    Route::post('/sample', [IPayController::class, 'sample']);
    Route::get('/ipaycallback', [IPayController::class, 'ipaycallback']);
    Route::get('/tiny_callback', [TinyController::class, 'tiny_callback']);
    Route::post('/tiny_external', [TinyController::class, 'tiny_external']);
    Route::post('/sample', [TinyController::class, 'tiny_sample']);
    Route::post('/mpesa_till', [TillController::class, 'safaricom_callback']);
});
Route::prefix('/backup')->group(function () {
    Route::get('/create', [BackupController::class, 'create_cool_backup']);
    Route::get('/billing', [BillingController::class, 'generate_billing']);
    Route::get('/generate_from_admin', [BillingController::class, 'generate_admin_fee_billing']);
    Route::get('/unpaid_principal', [BillingController::class, 'unpaid_principal']);
    Route::post('/stk', [TillController::class, 'initiate_stk_push']);
    Route::post('/till_balance', [TillController::class, 'check_till_balance']);
});

Route::prefix('/fcm')->group(function () {
    Route::post('/add', [FCMController::class, 'store']);
    Route::post('/send', [FCMController::class, 'send_message']);
    Route::get('/web/{phone}/{amount}', [IPayController::class, 'web_payment']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {


    Route::prefix('/admin')->group(function () {
        Route::prefix('/customer')->group(function () {
            Route::get('/all', [CustomerController::class, 'all']);
            Route::post('/approve', [CustomerController::class, 'approve']);
        });
        Route::prefix('/parameters')->group(function () {
            Route::post('/loantype/add', [ParametersController::class, 'addLoantype']);
            Route::get('/loantype/view', [ParametersController::class, 'viewLoantype']);
        });
    });

    Route::prefix('/customer')->group(function () {
        Route::get('/qualification/{id}', [CustomerController::class, 'show']);
        Route::post('/web/qualification/{id}', [CustomerController::class, 'show_web']);
        Route::get('/view/{id}', [CustomerController::class, 'view']);
        Route::get('/logs/{id}', [CustomerController::class, 'logs']);
        Route::post('/upload', [CustomerController::class, 'upload']);
        Route::post('/reset', [CustomerController::class, 'reset_password']);
        Route::post('/verify_login', [CustomerController::class, 'verify_login']);
        // Route::post('/confirm_pass', [CustomerController::class, 'confirm_pass']);
        Route::get('/profile', function (Request $request) {
            return auth()->user();
        });
        Route::post('/sign-out', [AuthenticationController::class, 'logout']);
    });

    Route::prefix('/loans')->group(function () {
        Route::get('/loantypes/{id}', [LoansController::class, 'user_loan_types']);
        Route::get('/verify/{id}', [LoansController::class, 'verify']);
        Route::post('/apply', [LoansController::class, 'store']);
        Route::post('/old', [LoansController::class, 'sample_store']);
        Route::get('/active/{id}', [LoansController::class, 'activeLoan']);
        Route::post('/active/web', [LoansController::class, 'active_web_loan']);
        Route::get('/history/{id}', [LoansController::class, 'viewLoans']);
        Route::get('/schedule/{id}', [LoansController::class, 'schedule']);
        Route::get('/repayments/{id}', [LoansController::class, 'repayments']);
        Route::get('/reset_penalty', [LoansController::class, 'update_penalty_date']);
        Route::get('/penalty', [LoansController::class, 'apply_penalty']);
        Route::post('/pay', [LoansController::class, 'payLoanLive']);
        Route::post('/waive', [LoansController::class, 'waive_loan']);
        Route::post('/stk', [MpesaController::class, 'stkPush']);
        Route::post('/tiny_pesa', [MpesaController::class, 'tiny_pesa']);
        Route::prefix('/web')->group(function () {
            Route::get('/{id}/{phone}', [LoansController::class, 'web_loans'])->name('web_loans');
        });
    });
    Route::prefix('/reports')->group(function () {
        Route::post('/close_month', [LoansController::class, 'close_month']);
        Route::get('/view_months', [LoansController::class, 'view_months']);
    });

    Route::prefix('/kopo_old')->group(function () {
        Route::post('/token', [KopoController::class, 'generate_token']);
        Route::post('/transact', [KopoController::class, 'create_transaction']);
    });

    Route::prefix('/b4m')->group(function () {
        Route::post('/contribute', [B4MController::class, 'contribute']);
        Route::post('/qualification', [B4MController::class, 'qualification']);
        Route::get('/approved/{id}', [B4MController::class, 'approved_b4m']);
        Route::post('/pledges', [B4MController::class, 'pledges_b4m']);
        Route::get('/pending', [B4MController::class, 'index']);
    });
    Route::prefix('/mailing')->group(function () {
        Route::post('/basic_email', [EmailController::class, 'basic_email']);
        Route::post('/html_email', [EmailController::class, 'html_email']);
    });
    Route::prefix('/savings')->group(function () {
        Route::get('/assigned_products/{id}', [SavingsController::class, 'assigned_products']);
        Route::get('/products/{id}', [SavingsController::class, 'products']);
        Route::post('/add', [SavingsController::class, 'store_tiny']);
        Route::get('/view/{id}', [SavingsController::class, 'show']);
        Route::get('/view/byproduct/{id}', [SavingsController::class, 'savings_by_products']);
        Route::post('/withdraw', [SavingsController::class, 'withdraw']);
        Route::get('/withdraws/{id}', [SavingsController::class, 'show_withdraw']);
    });

    Route::prefix('/welfare')->group(function () {
        Route::post('/add', [SavingsController::class, 'store_welfare']);
        Route::get('/view/{id}', [SavingsController::class, 'show_welfare']);
    });

    Route::prefix('/shares')->group(function () {
        Route::post('/add', [SavingsController::class, 'store_shares']);
        Route::get('/view/{id}', [SavingsController::class, 'show_shares']);
    });
    Route::prefix('/payments')->group(function () {
        Route::post('/callback', [MpesaController::class, 'store']);
    });
    Route::prefix('/notification')->group(function () {
        Route::get('/view/{id}', [CustomerController::class, 'view_notification']);
    });
    Route::prefix('/chats')->group(function () {
        Route::post('/send', [CustomerController::class, 'send_chats']);
        Route::get('/view/{id}', [CustomerController::class, 'view_chats']);
    });
    Route::prefix('/ipay')->group(function () {
        Route::post('/initiate', [IPayController::class, 'initiate_payment']);
        Route::post('/stkPush', [IPayController::class, 'stkPush']);
    });

    Route::prefix('/courses')->group(function () {
       
        Route::prefix('/users')->group(function () {
            Route::get('/{id}/profile', [ProvidersController::class, 'user_profile']); 
            Route::get('/instructor/{id}/profile', [ProvidersController::class, 'instructor_profile']); 
        }); 
        Route::get('/all', [CoursesController::class, 'all_courses']); 
        Route::get('/featured-courses', [CoursesController::class, 'featured_courses']); 
        Route::get('/courses/{id}', [CoursesController::class, 'course_details']); 
        Route::get('/trend-categories', [CategoriesController::class, 'trend_categories']);
        Route::get('/categories', [CategoriesController::class, 'categories']);
        
        Route::prefix('/user')->group(function () {
            Route::post('/{id}/profile', [StudentsController::class, 'view']);
        });
        Route::prefix('/blogs')->group(function () {
            Route::get('/', [CategoriesController::class, 'blogs']);
            Route::get('/categories', [CategoriesController::class, 'blogs_categories']);
        });
        Route::prefix('/providers')->group(function () {
            Route::get('/instructors', [ProvidersController::class, 'view_instructors']);
            Route::get('/organizations', [ProvidersController::class, 'view_organizations']); 
            Route::get('/consultations', [ProvidersController::class, 'view_consultations']); 
        });
        Route::prefix('/panel')->group(function () {
            Route::get('/quick-info', [ProvidersController::class, 'quick_information']); 
            Route::get('/classes', [ProvidersController::class, 'quick_information']); 
            Route::get('/meetings', [ProvidersController::class, 'panel_meetings']); 
            Route::get('/comments', [ProvidersController::class, 'panel_comments']);  
            Route::get('/subscribe', [ProvidersController::class, 'panel_subscribe']); 
            Route::get('/favorites', [ProvidersController::class, 'panel_favorites']);   
            Route::get('/notifications', [ProvidersController::class, 'notifications']);   
            Route::post('/profile-setting', [StudentsController::class, 'profile_setting']); 
            Route::post('/reviews', [StudentsController::class, 'add_reviews']); 
            Route::post('/comments', [StudentsController::class, 'add_comments']); 
           
            Route::prefix('/webinars')->group(function () {
                Route::get('/purchases', [ProvidersController::class, 'webinars_purchases']); 
                Route::get('/organizations', [ProvidersController::class, 'webinars_organizations']);  
            });
            Route::prefix('/cart')->group(function () {
                Route::get('/list', [ProvidersController::class, 'cart_list']);   
            });
            Route::prefix('/financial')->group(function () {
                Route::get('/summary', [ProvidersController::class, 'financial_summary']); 
                Route::get('/offline-payments', [ProvidersController::class, 'offline_payments']);  
            });
            Route::prefix('/support')->group(function () {
                Route::get('/tickets', [ProvidersController::class, 'tickets_support']);  
                Route::get('/class_support', [ProvidersController::class, 'class_support']);  
                Route::get('/departments', [ProvidersController::class, 'support_departments']);  
            });
            Route::prefix('/quizzes')->group(function () {
                Route::get('/not_participated', [ProvidersController::class, 'quizzes_not_participated']);  
                Route::get('/results/my-results', [ProvidersController::class, 'quizzes_my_results']);     
            });
            Route::prefix('/certificates')->group(function () {
                Route::get('/achievements', [StudentsController::class, 'achievements']);   
            });
            
        });
    });

     

    // Jenga APIs
    Route::prefix('/jenga')->group(function () {
        Route::post('/token', [JengaController::class, 'generateToken']);
        Route::get('/checkBalance', [JengaController::class, 'checkBalance']);
        Route::post('/buyAirtime', [JengaController::class, 'buyAirtime']);
        Route::post('/billPayments', [JengaController::class, 'billPayments']);
        Route::get('/getAllBillers', [JengaController::class, 'getAllBillers']);
        Route::post('/withinEquity', [JengaController::class, 'withinEquity']);
        Route::post('/equitytoMobile', [JengaController::class, 'equitytoMobile']);
        Route::post('/pesalinkBank', [JengaController::class, 'pesalinkBank']);
        Route::post('/pesalinkMobile', [JengaController::class, 'pesalinkMobile']);
    });
});

// 
// https://tinypesa.com/smp

// Revolut.com
// Wise.com

// php artisan migrate --path=/database/migrations/full_migration_file_name_migration.php