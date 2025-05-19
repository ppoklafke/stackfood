<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vendor\SubscriptionController;


Route::group(['namespace' => 'Vendor', 'as' => 'vendor.'], function () {
    Route::group(['middleware' => ['vendor' ,'maintenance']], function () {
        Route::get('lang/{locale}', 'LanguageController@lang')->name('lang');

        Route::post('search-routing', 'SearchRoutingController@index')->name('search.routing');
        Route::get('recent-search', 'SearchRoutingController@recentSearch')->name('recent.search');
        Route::post('store-clicked-route', 'SearchRoutingController@storeClickedRoute')->name('store.clicked.route');


        Route::get('/', 'DashboardController@dashboard')->name('dashboard');
        Route::get('/get-restaurant-data', 'DashboardController@restaurant_data')->name('get-restaurant-data');
        Route::post('/store-token', 'DashboardController@updateDeviceToken')->name('store.token');
        Route::get('/reviews', 'ReviewController@index')->name('reviews')->middleware(['module:reviews' ,'subscription:reviews']);
        Route::post('/store-reply/{id}', 'ReviewController@update_reply')->name('review-reply')->middleware(['module:reviews' ,'subscription:reviews']);


        Route::group(['prefix' => 'pos', 'as' => 'pos.'], function () {
            Route::post('variant_price', 'POSController@variant_price')->name('variant_price');
            Route::group(['middleware' => ['module:pos','subscription:pos']], function () {
                Route::get('/', 'POSController@index')->name('index');
                Route::get('quick-view', 'POSController@quick_view')->name('quick-view');
                Route::get('quick-view-cart-item', 'POSController@quick_view_card_item')->name('quick-view-cart-item');
                Route::post('add-to-cart', 'POSController@addToCart')->name('add-to-cart');
                Route::post('add-delivery-info', 'POSController@addDeliveryInfo')->name('add-delivery-info');
                Route::post('remove-from-cart', 'POSController@removeFromCart')->name('remove-from-cart');
                Route::post('cart-items', 'POSController@cart_items')->name('cart_items');
                Route::post('update-quantity', 'POSController@updateQuantity')->name('updateQuantity');
                Route::post('empty-cart', 'POSController@emptyCart')->name('emptyCart');
                Route::post('tax', 'POSController@update_tax')->name('tax');
                Route::post('paid', 'POSController@update_paid')->name('paid');
                Route::post('discount', 'POSController@update_discount')->name('discount');
                Route::get('customers', 'POSController@get_customers')->name('customers');
                Route::post('order', 'POSController@place_order')->name('order');
                Route::get('orders', 'POSController@order_list')->name('orders');
                Route::post('search', 'POSController@search')->name('search');
                Route::get('order-details/{id}', 'POSController@order_details')->name('order-details');
                Route::get('invoice/{id}', 'POSController@generate_invoice');
                Route::post('customer-store', 'POSController@customer_store')->name('customer-store');
                Route::get('data', 'POSController@extra_charge')->name('extra_charge');

            });
        });

        Route::group([ 'prefix' => 'advertisement', 'as' => 'advertisement.' ,'middleware' => ['module:advertisement','subscription:advertisement' ]], function () {

            Route::get('/', 'AdvertisementController@index')->name('index');
            Route::get('create/', 'AdvertisementController@create')->name('create');
            Route::get('details/{advertisement}', 'AdvertisementController@show')->name('show');
            Route::get('{advertisement}/edit', 'AdvertisementController@edit')->name('edit');
            Route::post('store', 'AdvertisementController@store')->name('store');
            Route::put('update/{advertisement}', 'AdvertisementController@update')->name('update');
            Route::delete('delete/{id}', 'AdvertisementController@destroy')->name('destroy');
            Route::get('/status', 'AdvertisementController@status')->name('status');
            Route::get('/copy-advertisement/{advertisement}', 'AdvertisementController@copyAdd')->name('copyAdd');
            Route::post('/copy-add-post/{advertisement}', 'AdvertisementController@copyAddPost')->name('copyAddPost');
        });



        Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
            Route::post('order-stats', 'DashboardController@order_stats')->name('order-stats');
        });

        Route::group(['prefix' => 'category', 'as' => 'category.', 'middleware' => ['module:food','subscription:food']], function () {
            Route::get('get-all', 'CategoryController@get_all')->name('get-all');
            Route::get('list', 'CategoryController@index')->name('add');
            Route::get('sub-category-list', 'CategoryController@sub_index')->name('add-sub-category');
        });

        Route::group(['prefix' => 'custom-role', 'as' => 'custom-role.', 'middleware' => ['module:custom_role','subscription:custom_role']], function () {
            Route::get('create', 'CustomRoleController@create')->name('create');
            Route::post('create', 'CustomRoleController@store')->name('store');
            Route::get('edit/{id}', 'CustomRoleController@edit')->name('edit');
            Route::post('update/{id}', 'CustomRoleController@update')->name('update');
            Route::delete('delete/{id}', 'CustomRoleController@distroy')->name('delete');
        });

        Route::group(['prefix' => 'delivery-man', 'as' => 'delivery-man.', 'middleware' => ['module:deliveryman','subscription:deliveryman']], function () {
            Route::get('add', 'DeliveryManController@index')->name('add');
            Route::post('store', 'DeliveryManController@store')->name('store');
            Route::get('list', 'DeliveryManController@list')->name('list');
            Route::get('preview/{id}/{tab?}', 'DeliveryManController@preview')->name('preview');
            Route::get('status/{id}/{status}', 'DeliveryManController@status')->name('status');
            Route::get('earning/{id}/{status}', 'DeliveryManController@earning')->name('earning');
            Route::get('edit/{id}', 'DeliveryManController@edit')->name('edit');
            Route::post('update/{id}', 'DeliveryManController@update')->name('update');
            Route::delete('delete/{id}', 'DeliveryManController@delete')->name('delete');
            Route::get('get-deliverymen', 'DeliveryManController@get_deliverymen')->name('get-deliverymen');

            Route::group(['prefix' => 'reviews', 'as' => 'reviews.'], function () {
                Route::get('list', 'DeliveryManController@reviews_list')->name('list');
            });
        });

        Route::group(['prefix' => 'employee', 'as' => 'employee.', 'middleware' => ['module:employee','subscription:employee']], function () {
            Route::get('add-new', 'EmployeeController@add_new')->name('add-new');
            Route::post('add-new', 'EmployeeController@store');
            Route::get('list', 'EmployeeController@list')->name('list');
            Route::get('list-export', 'EmployeeController@list_export')->name('export-employee');
            Route::get('edit/{id}', 'EmployeeController@edit')->name('edit');
            Route::post('update/{id}', 'EmployeeController@update')->name('update');
            Route::delete('delete/{id}', 'EmployeeController@distroy')->name('delete');
            Route::post('search', 'EmployeeController@search')->name('search');
        });

        Route::post('food/food-variation-generate', 'FoodController@food_variation_generator')->name('food.food-variation-generate');
        Route::group(['prefix' => 'food', 'as' => 'food.', 'middleware' => ['module:food','subscription:food']], function () {
            Route::get('add-new', 'FoodController@index')->name('add-new');
            Route::post('variant-combination', 'FoodController@variant_combination')->name('variant-combination');
            Route::post('store', 'FoodController@store')->name('store');
            Route::get('edit/{id}', 'FoodController@edit')->name('edit');
            Route::post('update/{id}', 'FoodController@update')->name('update');
            Route::get('list', 'FoodController@list')->name('list');
            Route::delete('delete/{id}', 'FoodController@delete')->name('delete');
            Route::get('status/{id}/{status}', 'FoodController@status')->name('status');
            Route::get('recommended/{id}/{status}', 'FoodController@recommended')->name('recommended');
            Route::post('search', 'FoodController@search')->name('search');
            Route::get('view/{id}', 'FoodController@view')->name('view');
            Route::get('get-categories', 'FoodController@get_categories')->name('get-categories');
            Route::get('out-of-stock-list', 'FoodController@stockOutList')->name('stockOutList');
            Route::post('update-stock', 'FoodController@updateStock')->name('updateStock');
            Route::post('/add-to-session', 'FoodController@addToSession')->name('addToSession');


            //Import and export
            Route::get('bulk-import', 'FoodController@bulk_import_index')->name('bulk-import');
            Route::post('bulk-import', 'FoodController@bulk_import_data');
            Route::get('bulk-export', 'FoodController@bulk_export_index')->name('bulk-export-index');
            Route::post('bulk-export', 'FoodController@bulk_export_data')->name('bulk-export');
        });

        // Route::group(['prefix' => 'banner', 'as' => 'banner.', 'middleware' => ['module:banner','subscription:banner']], function () {
        //     Route::get('list', 'BannerController@list')->name('list');
        //     Route::get('join_campaign/{id}/{status}', 'BannerController@status')->name('status');
        // });

        Route::group(['prefix' => 'campaign', 'as' => 'campaign.', 'middleware' => ['module:campaign','subscription:campaign']], function () {
            Route::get('list', 'CampaignController@list')->name('list');
            Route::get('item/list', 'CampaignController@itemlist')->name('itemlist');
            Route::get('remove-restaurant/{campaign}/{restaurant}', 'CampaignController@remove_restaurant')->name('remove-restaurant');
            Route::get('add-restaurant/{campaign}/{restaurant}', 'CampaignController@addrestaurant')->name('addrestaurant');
        });

        Route::group(['prefix' => 'wallet', 'as' => 'wallet.', 'middleware' => ['module:wallet','subscription:wallet']], function () {
            Route::get('/', 'WalletController@index')->name('index');
            Route::post('request', 'WalletController@w_request')->name('withdraw-request');
            Route::delete('close/{id}', 'WalletController@close_request')->name('close-request');
            Route::get('method-list', 'WalletController@method_list')->name('method-list');
            Route::post('make-collected-cash-payment', 'WalletController@make_payment')->name('make_payment');
            Route::post('make-wallet-adjustment', 'WalletController@make_wallet_adjustment')->name('make_wallet_adjustment');

            Route::get('wallet-payment-list', 'WalletController@wallet_payment_list')->name('wallet_payment_list');
            Route::get('disbursement-list', 'WalletController@getDisbursementList')->name('getDisbursementList');
            Route::get('export', 'WalletController@getDisbursementExport')->name('export');

        });
        Route::group(['prefix' => 'withdraw-method', 'as' => 'wallet-method.', 'middleware' => ['module:wallet','subscription:wallet']], function () {
            Route::get('/', 'WalletMethodController@index')->name('index');
            Route::post('store/', 'WalletMethodController@store')->name('store');
            Route::get('default/{id}/{default}', 'WalletMethodController@default')->name('default');
            Route::delete('delete/{id}', 'WalletMethodController@delete')->name('delete');
        });


        Route::group(['prefix' => 'coupon', 'as' => 'coupon.', 'middleware' => ['module:coupon','subscription:coupon']], function () {
            Route::get('add-new', 'CouponController@add_new')->name('add-new');
            Route::post('store', 'CouponController@store')->name('store');
            Route::get('update/{id}', 'CouponController@edit')->name('update');
            Route::post('update/{id}', 'CouponController@update');
            Route::get('status/{id}/{status}', 'CouponController@status')->name('status');
            Route::delete('delete/{id}', 'CouponController@delete')->name('delete');
            Route::post('search', 'CouponController@search')->name('search');
            Route::get('check-code', 'CouponController@checkCode')->name('check.code');
        });

        Route::group(['prefix' => 'addon', 'as' => 'addon.', 'middleware' => ['module:addon','subscription:addon']], function () {
            Route::get('add-new', 'AddOnController@index')->name('add-new');
            Route::post('store', 'AddOnController@store')->name('store');
            Route::get('edit/{id}', 'AddOnController@edit')->name('edit');
            Route::post('update/{id}', 'AddOnController@update')->name('update');
            Route::delete('delete/{id}', 'AddOnController@delete')->name('delete');
        });

        Route::group(['prefix' => 'order', 'as' => 'order.' , 'middleware' => ['module:order']], function () {
            Route::get('list/{status}', 'OrderController@list')->name('list');
            Route::put('status-update/{id}', 'OrderController@status')->name('status-update');
            Route::post('search', 'OrderController@search')->name('search');
            Route::post('add-to-cart', 'OrderController@add_to_cart')->name('add-to-cart');
            Route::post('remove-from-cart', 'OrderController@remove_from_cart')->name('remove-from-cart');
            Route::get('update/{order}', 'OrderController@update')->name('update');
            Route::get('edit-order/{order}', 'OrderController@edit')->name('edit');
            Route::get('details/{id}', 'OrderController@details')->name('details');
            Route::get('status', 'OrderController@status')->name('status');
            Route::get('quick-view', 'OrderController@quick_view')->name('quick-view');
            Route::get('quick-view-cart-item', 'OrderController@quick_view_cart_item')->name('quick-view-cart-item');
            Route::get('generate-invoice/{id}', 'OrderController@generate_invoice')->name('generate-invoice');
            Route::post('add-payment-ref-code/{id}', 'OrderController@add_payment_ref_code')->name('add-payment-ref-code');

            Route::get('orders-export/{status}', 'OrderController@orders_export')->name('export');
            Route::post('add-order-proof/{id}', 'OrderController@add_order_proof')->name('add-order-proof');
            Route::get('remove-proof-image', 'OrderController@remove_proof_image')->name('remove-proof-image');


            Route::group([ 'as' => 'subscription.'], function () {
                Route::get('subscription/update-status/{supscription_id}/{status}', 'OrderSubscriptionController@view')->name('update-status');
                Route::get('subscription', 'OrderSubscriptionController@index')->name('index');
                Route::get('subscription/show/{subscription}', 'OrderSubscriptionController@show')->name('show');
                Route::get('subscription/edit/{subscription}', 'OrderSubscriptionController@edit')->name('edit');
                Route::put('subscription/update/{subscription}', 'OrderSubscriptionController@update')->name('update');
            });

            Route::get('add-delivery-man/{order_id}/{delivery_man_id}', 'OrderController@add_delivery_man')->name('add-delivery-man');
            Route::put('add-dine-in-table-number/{order}', 'OrderController@add_dine_in_table_number')->name('add_dine_in_table_number');

        });
        Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.', 'middleware' => ['module:restaurant_setup','subscription:restaurant_setup' ]], function () {
            Route::get('restaurant-setup', 'BusinessSettingsController@restaurant_index')->name('restaurant-setup');
            Route::get('notification-setup', 'BusinessSettingsController@notification_index')->name('notification-setup');
            Route::get('notification-status-change/{key}/{type}', 'BusinessSettingsController@notification_status_change')->name('notification_status_change');
            Route::post('add-schedule', 'BusinessSettingsController@add_schedule')->name('add-schedule');
            Route::get('remove-schedule/{restaurant_schedule}', 'BusinessSettingsController@remove_schedule')->name('remove-schedule');
            Route::get('update-active-status', 'BusinessSettingsController@active_status')->name('update-active-status');
            Route::post('update-setup/{restaurant}', 'BusinessSettingsController@restaurant_setup')->name('update-setup');
            Route::get('toggle-settings-status/{restaurant}/{status}/{menu}', 'BusinessSettingsController@restaurant_status')->name('toggle-settings');
            Route::get('site_direction_vendor', 'BusinessSettingsController@site_direction_vendor')->name('site_direction_vendor');
            Route::post('update-meta-data/{restaurant}', 'BusinessSettingsController@updateStoreMetaData')->name('update-meta-data');

        });

        Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => ['module:bank_info','subscription:bank_info' ]], function () {
            Route::get('view', 'ProfileController@view')->name('view');
            // Route::get('update', 'ProfileController@edit')->name('update');
            Route::post('update', 'ProfileController@update')->name('update');
            Route::post('settings-password', 'ProfileController@settings_password_update')->name('settings-password');
            // Route::get('bank-view', 'ProfileController@bank_view')->name('bankView');
            // Route::get('bank-edit', 'ProfileController@bank_edit')->name('bankInfo');
            // Route::post('bank-update', 'ProfileController@bank_update')->name('bank_update');
            // Route::post('bank-delete', 'ProfileController@bank_delete')->name('bank_delete');
        });

        Route::group(['prefix' => 'restaurant', 'as' => 'shop.', 'middleware' => ['module:my_shop','subscription:my_shop' ]], function () {
            Route::get('view', 'RestaurantController@view')->name('view');
            Route::get('edit', 'RestaurantController@edit')->name('edit');
            Route::post('update', 'RestaurantController@update')->name('update');
            Route::post('update-message', 'RestaurantController@update_message')->name('update-message');
            Route::post('qr-store', 'RestaurantController@qr_store')->name('qr-store');
            Route::get('qr-view', 'RestaurantController@qr_view')->name('qr-view');
            Route::get('qr-pdf', 'RestaurantController@qr_pdf')->name('qr-pdf');
            Route::get('qr-print', 'RestaurantController@qr_print')->name('qr-print');
        });

        Route::group(['prefix' => 'message', 'as' => 'message.', 'middleware' => ['module:chat','subscription:chat'] ], function () {
            Route::get('list', 'ConversationController@list')->name('list');
            Route::post('store/{user_id}/{user_type}', 'ConversationController@store')->name('store');
            Route::get('view/{conversation_id}/{user_id}', 'ConversationController@view')->name('view');
        });

        Route::group(['prefix' => 'subscription' , 'as' => 'subscriptionackage.'], function () {
            Route::get('/subscriber-detail',  [SubscriptionController::class, 'subscriberDetail'])->name('subscriberDetail');
            Route::get('/invoice/{id}',  [SubscriptionController::class, 'invoice'])->name('invoice');
            Route::get('/subscriber-list',  [SubscriptionController::class, 'subscriberList'])->name('subscriberList');
            Route::post('/cancel-subscription/{id}',  [SubscriptionController::class, 'cancelSubscription'])->name('cancelSubscription');
            Route::post('/switch-to-commission/{id}',  [SubscriptionController::class, 'switchToCommission'])->name('switchToCommission');
            Route::get('/package-view/{id}/{store_id}',  [SubscriptionController::class, 'packageView'])->name('packageView');
            Route::get('/subscriber-transactions/{id}',  [SubscriptionController::class, 'subscriberTransactions'])->name('subscriberTransactions');
            Route::get('/subscriber-transaction-export',  [SubscriptionController::class, 'subscriberTransactionExport'])->name('subscriberTransactionExport');
            Route::get('/subscriber-wallet-transactions',  [SubscriptionController::class, 'subscriberWalletTransactions'])->name('subscriberWalletTransactions');
            Route::post('/package-buy',  [SubscriptionController::class, 'packageBuy'])->name('packageBuy');
            Route::post('/add-to-session',  [SubscriptionController::class, 'addToSession'])->name('addToSession');
        });


        Route::group(['prefix' => 'report', 'as' => 'report.', 'middleware' => ['module:report' ,'subscription:report']], function () {
            Route::post('set-date', 'ReportController@set_date')->name('set-date');
            Route::get('expense-report', 'ReportController@expense_report')->name('expense-report');
            Route::get('expense-export', 'ReportController@expense_export')->name('expense-export');
            Route::post('expense-report-search', 'ReportController@expense_search')->name('expense-report-search');
            Route::get('transaction-report', 'ReportController@day_wise_report')->name('day-wise-report');
            Route::get('transaction-report-export', 'ReportController@day_wise_report_export')->name('day-wise-report-export');
            Route::get('generate-statement/{id}', 'ReportController@generate_statement')->name('generate-statement');
            Route::get('order-report', 'ReportController@order_report')->name('order-report');
            Route::get('order-report-export', 'ReportController@order_report_export')->name('order-report-export');
            Route::get('campaign-order-report', 'ReportController@campaign_order_report')->name('campaign_order-report');
            Route::get('campaign-order-report-export', 'ReportController@campaign_report_export')->name('campaign_report_export');
            Route::get('food-wise-report', 'ReportController@food_wise_report')->name('food-wise-report');
            Route::get('food-wise-report-export', 'ReportController@food_wise_report_export')->name('food-wise-report-export');
            Route::get('disbursement-report', 'ReportController@disbursement_report')->name('disbursement-report');
            Route::get('disbursement-report-export/{type}', 'ReportController@disbursement_report_export')->name('disbursement-report-export');

        });

        Route::group(['prefix' => 'file-manager', 'as' => 'file-manager.'], function () {
            Route::get('/download/{file_name}/{storage?}', 'OrderController@download')->name('download');
        });
    });

    Route::post('digital_payment', 'SubscriptionController@digital_payment')->name('subscription.digital_payment');
    Route::get('pay/now/{subscription_transaction_id}', 'SubscriptionController@getPaymentMethods')->name('subscription.digital_payment_methods');
});
