<?php

Route::get('login', ['as' => 'login', 'uses' => 'AuthenticationController@getLogin']);
Route::post('login', ['as' => 'login', 'uses' => 'AuthenticationController@postLogin']);

Route::any('login/restore', ['as' => 'admin.restore-session', 'uses' => 'Controller@restoreAdminSession']);

Route::any('logout', ['as' => 'logout', 'uses' => 'AuthenticationController@logout']);

Route::any('io/stream/{template_id}/{md5}', [
    'as' => 'io.stream-template',
    'uses' => function ($template_id, $md5) {
        $template = \App\Template::find($template_id);

        if (!$template) {
            return response('404 - NOT FOUND', 404);
        }

        if (md5_file($template->getPath()) != $md5) {
            return response('404 - NOT FOUND', 404);
        }

        return response()->download($template->getPath());
    }
]);

Route::group(['middleware' => 'auth'], function () {
    Route::any('/', ['as' => 'home', 'uses' => 'ClientHomeController@home']);

    Route::any('vms', ['as' => 'vms', 'uses' => 'ClientVMController@my_vms']);

    Route::any('table/vms', ['as' => 'table-vms', 'uses' => 'DatatablesController@customer_vms']);

    Route::any('vm/{vm_id}', ['as' => 'vm', 'uses' => 'ClientVMController@manage']);

    Route::get('resource_pools', ['as' => 'resourcepools', 'uses' => 'ClientResourcePoolController@resource_pools']);

    Route::get('vm/new/{resource_pool_id}', ['as' => 'resourcepool.vm-create', 'uses' => 'ClientResourcePoolController@createFromResourcePool']);
    Route::post('vm/new/{resource_pool_id}', ['as' => '@resourcepool.vm-create', 'uses' => 'ClientResourcePoolController@doCreateFromResourcePool']);
});

Route::group(['middleware' => 'admin.auth'], function () {
    Route::any('admin', ['as' => 'admin.home', 'uses' => 'AdminHomeController@home']);

    Route::any('admin/servers', ['as' => 'admin.servers', 'uses' => 'AdminServerController@servers']);

    Route::get('admin/server/new', ['as' => 'admin.server-create', 'uses' => 'AdminServerController@create']);
    Route::post('admin/server/new', ['as' => 'admin.server-create', 'uses' => 'AdminServerController@doCreate']);

    Route::get('admin/server/{server_id}', ['as' => 'admin.server', 'uses' => 'AdminServerController@server']);
    Route::post('admin/server/{server_id}', ['as' => '@server.update', 'uses' => 'AdminServerController@doUpdate']);

    Route::any('admin/server/{server_id}/stats', ['as' => 'admin.server-stats', 'uses' => 'AdminServerController@getStats']);

    Route::get('admin/server/{server_id}/delete', ['as' => 'admin.server-delete', 'uses' => 'AdminServerController@doDelete']);
    Route::get('admin/server/{server_id}/purge', ['as' => 'admin.server-purge', 'uses' => 'AdminServerController@doPurge']);

    Route::any('admin/backup_servers', ['as' => 'admin.backup-servers', 'uses' => 'AdminBackupServerController@servers']);

    Route::get('admin/backup_server/new', ['as' => 'admin.backup-server-create', 'uses' => 'AdminBackupServerController@create']);
    Route::post('admin/backup_server/new', ['as' => 'admin.backup-server-create', 'uses' => 'AdminBackupServerController@doCreate']);

    Route::any('admin/vms', ['as' => 'admin.vms', 'uses' => 'AdminVMController@vms']);

    Route::get('admin/vm/create', ['as' => 'admin.vm-create', 'uses' => 'AdminVMController@create']);
    Route::post('admin/vm/create', ['as' => 'admin.vm-create', 'uses' => 'AdminVMController@doCreate']);

    Route::any('admin/vm/{vm_id}', ['as' => 'admin.vm', 'uses' => 'AdminVMController@manage']);

    Route::post('admin/vm/{vm_id}/togglebackupmethod', ['as' => 'admin.vm-ajax.togglebackupmethod', 'uses' => 'AdminVMController@doAjaxToggleBackupMethod']);

    Route::post('admin/vm/{vm_id}/updatebackupmethod', ['as' => 'admin.vm-ajax.updatebackupmethod', 'uses' => 'AdminVMController@doAjaxUpdateBackupMethod']);

    Route::post('admin/vm/{vm_id}/ipassign', ['as' => 'admin.vm-ipassign', 'uses' => 'AdminVMController@assignIP']);

    Route::post('admin/vm/{vm_id}/tc', ['as' => 'admin.vm-tccontrol', 'uses' => 'AdminVMController@doSetTCControl']);

    Route::any('admin/packages', ['as' => 'admin.packages', 'uses' => 'AdminPackageController@all']);

    Route::get('admin/package/new', ['as' => 'admin.package-create', 'uses' => 'AdminPackageController@create']);
    Route::post('admin/package/new', ['as' => '@admin.package-create', 'uses' => 'AdminPackageController@doCreate']);

    Route::get('admin/package/{package_id}', ['as' => 'admin.package', 'uses' => 'AdminPackageController@package']);
    Route::post('admin/package/{package_id}', ['as' => '@admin.package', 'uses' => 'AdminPackageController@doUpdate']);

    Route::get('admin/package/{package_id}/delete', ['as' => 'admin.package-delete', 'uses' => 'AdminPackageController@doDelete']);

    Route::get('admin/users', ['as' => 'admin.users', 'uses' => 'AdminUserController@users']);

    Route::get('admin/user/new', ['as' => 'admin.user-create', 'uses' => 'AdminUserController@create']);
    Route::post('admin/user/new', ['as' => '@admin.user-create', 'uses' => 'AdminUserController@doCreate']);

    Route::get('admin/resource_pool/{resource_pool_id}', ['as' => 'admin.resourcepool', 'uses' => 'AdminResourcePoolController@resource_pool']);
    Route::post('admin/resource_pool/{resource_pool_id}', ['as' => '@admin.resourcepool', 'uses' => 'AdminResourcePoolController@resource_pool']);

    Route::get('admin/resource_pool/{resource_pool_id}/suspend', ['as' => 'admin.resourcepool-suspend', 'uses' => 'AdminResourcePoolController@doSuspendResourcePool']);

    Route::get('admin/resource_pool/{resource_pool_id}/unsuspend', ['as' => 'admin.resourcepool-unsuspend', 'uses' => 'AdminResourcePoolController@doUnsuspendResourcePool']);

    Route::get('admin/resource_pool/{resource_pool_id}/delete', ['as' => 'admin.resourcepool-delete', 'uses' => 'AdminResourcePoolController@doDeleteResourcePool']);

    Route::get('admin/vm/new/{resource_pool_id}', ['as' => 'admin.resourcepool.vm-create', 'uses' => 'AdminVMController@createFromResourcePool']);
    Route::post('admin/vm/new/{resource_pool_id}', ['as' => '@admin.resourcepool.vm-create', 'uses' => 'AdminVMController@doCreateFromResourcePool']);

    Route::get('admin/user/{user_id}', ['as' => 'admin.user', 'uses' => 'AdminUserController@user']);
    Route::post('admin/user/{user_id}', ['as' => '@admin.user.update', 'uses' => 'AdminUserController@doUpdate']);

    Route::get('admin/user/{user_id}/delete', ['as' => 'admin.user-delete', 'uses' => 'AdminUserController@doDelete']);

    Route::get('admin/user/{user_id}/purge', ['as' => 'admin.user-purge', 'uses' => 'AdminUserController@doPurge']);

    Route::any('admin/user/{user_id}/login', ['as' => 'admin.user-login', 'uses' => 'AdminUserController@doAdminLogin']);

    // Notifications
    Route::any('admin/alerts', ['as' => 'admin.notifications', 'uses' => 'AdminNotificationsController@notifications']);

    Route::get('admin/alerts/new', ['as' => 'admin.notification-create', 'uses' => 'AdminNotificationsController@create']);
    Route::post('admin/alerts/new', ['as' => '@admin.notification-create', 'uses' => 'AdminNotificationsController@doCreate']);

    Route::get('admin/alert/{alert_id}/delete', ['as' => 'admin.notification-delete', 'uses' => 'AdminNotificationsController@doDelete']);

    // TEMPLATES
    Route::get('admin/templates', ['as' => 'admin.templates', 'uses' => 'AdminTemplateController@templates']);

    Route::get('admin/template/new', ['as' => 'admin.template-create', 'uses' => 'AdminTemplateController@create']);
    Route::post('admin/template/new', ['as' => '@admin.template-create', 'uses' => 'AdminTemplateController@doCreate']);

    Route::get('admin/template/{templates_id}', ['as' => 'admin.template', 'uses' => 'AdminTemplateController@template']);
    Route::post('admin/template/{templates_id}', ['as' => '@admin.template.update', 'uses' => 'AdminTemplateController@doUpdate']);

    Route::get('admin/template/{templates_id}/deploy/{server_id}', ['as' => 'admin.template-deploy', 'uses' => 'AdminTemplateController@doDeploy']);

    Route::get('admin/template/{templates_id}/delete', ['as' => 'admin.template-delete', 'uses' => 'AdminTemplateController@doDelete']);

    // IPs
    Route::get('admin/ips', ['as' => 'admin.ips', 'uses' => 'AdminIPController@ips']);

    Route::get('admin/ip/new', ['as' => 'admin.ip-create', 'uses' => 'AdminIPController@create']);
    Route::post('admin/ip/new', ['as' => '@admin.ip-create', 'uses' => 'AdminIPController@doCreate']);

    Route::get('admin/ip/{ip_id}', ['as' => 'admin.ip', 'uses' => 'AdminIPController@ip']);
    Route::post('admin/ip/{ip_id}', ['as' => '@admin.ip.update', 'uses' => 'AdminIPController@doUpdate']);

    Route::get('admin/ip/{ip_id}/delete', ['as' => 'admin.ip-delete', 'uses' => 'AdminIPController@doDelete']);

    Route::any('admin/ip/remove/{ip_id}', ['as' => 'admin.ip-remove', 'uses' => 'AdminVMController@removeIp']);

    // Backups
    Route::get('admin/backups', ['as' => 'admin.backups', 'uses' => 'AdminBackupsController@backups']);
    Route::post('admin/backup/{backup_id}/delete', ['as' => 'admin.backup-delete', 'uses' => 'AdminBackupsController@doDeleteBackup']);

  
    // Resource Pools
    Route::get('admin/resourcepools', ['as' => 'admin.resourcepools', 'uses' => 'AdminResourcePoolController@resource_pools']);

    Route::get('admin/resourcepool/new', ['as' => 'admin.resourcepool-create', 'uses' => 'AdminResourcePoolController@create']);
    Route::post('admin/resourcepool/new', ['as' => 'admin.resourcepool-create', 'uses' => 'AdminResourcePoolController@doCreate']);

    // Settings
    Route::get('admin/settings/general', ['as' => 'admin.settings-general', 'uses' => 'AdminSettingsController@general']);
    Route::post('admin/settings/general', ['as' => '@admin.settings-general', 'uses' => 'AdminSettingsController@doUpdateGeneral']);

    Route::get('admin/settings/network', ['as' => 'admin.settings-network', 'uses' => 'AdminSettingsController@network']);
    Route::post('admin/settings/network', ['as' => '@admin.settings-network', 'uses' => 'AdminSettingsController@doUpdateNetwork']);

    Route::get('admin/settings/network/{status}','AdminSettingsController@bandwidthsuspension');
    //Route::post('admin/settings/network/{status}','AdminSettingsController@updateNetworkadapter');

    Route::get('admin/settings/email', ['as' => 'admin.settings-email', 'uses' => 'AdminSettingsController@email']);
    Route::post('admin/settings/email', ['as' => '@admin.settings-email', 'uses' => 'AdminSettingsController@doUpdateEmail']);

    Route::get('admin/settings/maintenance', ['as' => 'admin.settings-maintenance', 'uses' => 'AdminSettingsController@maintenance']);
    Route::post('admin/settings/maintenance', ['as' => '@admin.settings-maintenance', 'uses' => 'AdminSettingsController@doUpdateMaintenance']);

    Route::get('admin/settings/security', ['as' => 'admin.settings-security', 'uses' => 'AdminSettingsController@security']);
    Route::post('admin/settings/security', ['as' => 'admin.settings-security', 'uses' => 'AdminSettingsController@doUpdateSecurity']);

    Route::get('admin/settings/services', ['as' => 'admin.services', 'uses' => 'AdminServicesController@services']);
    Route::get('admin/settings/services/{service}/restart', ['as' => 'admin.service-restart', 'uses' => 'AdminServicesController@serviceRestart']);


    // Table
    Route::any('admin/table/users', ['as' => 'admin.table-users', 'uses' => 'DatatablesController@users']);
    Route::any('admin/table/resource-pools', ['as' => 'admin.table-resource-pools', 'uses' => 'DatatablesController@resource_pools']);
    Route::any('admin/table/vms', ['as' => 'admin.table-vms', 'uses' => 'DatatablesController@vms']);
    Route::any('admin/table/ips', ['as' => 'admin.table-ips', 'uses' => 'DatatablesController@ips']);
    Route::any('admin/table/packages', ['as' => 'admin.table-packages', 'uses' => 'DatatablesController@packages']);
    Route::any('admin/table/templates', ['as' => 'admin.table-templates', 'uses' => 'DatatablesController@templates']);
    Route::any('admin/table/servers', ['as' => 'admin.table-servers', 'uses' => 'DatatablesController@servers']);
});