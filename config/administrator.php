<?php

return array(

    /*
     * Package URI
     *
     * @type string
     */
    'uri' => 'admin',

    /*
     *  Domain for routing.
     *
     *  @type string
     */
    'domain' => '',

    /*
     * Page title
     *
     * @type string
     */
    'title' => env('APP_NAME', 'EasyBBS'),

    /*
     * The path to your model config directory
     *
     * @type string
     */
    'model_config_path' => config_path('administrator'),

    /*
     * The path to your settings config directory
     *
     * @type string
     */
    'settings_config_path' => config_path('administrator/settings'),

    /*
     * 后台菜单数组，多维数组渲染结果为多级嵌套菜单。
     *
     * 数组里的值有三种类型：
     * 1. 字符串 —— 子菜单的入口，不可访问；
     * 2. 模型配置文件 —— 访问 `model_config_path` 目录下的模型文件，如 `users` 访问的是 `users.php` 模型配置文件；
     * 3. 配置信息 —— 必须使用前缀 `settings.`，对应 `settings_config_path` 目录下的文件，如：默认设置下，
     *              `settings.site` 访问的是 `administrator/settings/site.php` 文件
     * 4. 页面文件 —— 必须使用前缀 `page.`，如：`page.pages.analytics` 对应 `administrator/pages/analytics.php`
     *               或者是 `administrator/pages/analytics.blade.php` ，两种后缀名皆可
     *
     * 示例：
     *  [
     *      'users',
     *      'E-Commerce' => ['collections', 'products', 'product_images', 'orders'],
     *      'Settings'  => ['settings.site', 'settings.ecommerce', 'settings.social'],
     *      'Analytics' => ['E-Commerce' => 'page.pages.analytics'],
     *  ]
     */
    'menu' => [
        '用户与权限' => ['users', 'roles', 'permissions'],
        '内容管理' => ['categories', 'topics', 'replies'],
        '站点管理' => ['settings.site'],
    ],

    /*
     * The permission option is the highest-level authentication check that lets you define a closure that should return true if the current user
     * is allowed to view the admin section. Any "falsey" response will send the user back to the 'login_path' defined below.
     *
     * @type closure
     */
    'permission' => function () {
        return Auth::check() && Auth::user()->can('manage_contents');
    },

    /*
     * 如值为 `true`，将使用 `dashboard_view` 定义的视图文件渲染页面；
     * 如值为 `false`，将使用 `home_page` 定义的菜单条目来作为后台主页。
     *
     * @type bool
     */
    'use_dashboard' => false,

    /*
     * 设置后台主页视图文件，由 `use_dashboard` 选项决定
     *
     * @type string
     */
    'dashboard_view' => '',

    /*
     * 用来作为后台主页的菜单条目，由 `use_dashboard` 选项决定，菜单指的是 `menu` 选项
     *
     * @type string
     */
    'home_page' => 'topics',

    /*
     * The route to which the user will be taken when they click the "back to site" button
     *
     * @type string
     */
    'back_to_site_path' => '/',

    /*
     * The login path is the path where Administrator will send the user if they fail a permission check
     *
     * @type string
     */
    'login_path' => 'permission-denied',

    /*
     * The logout path is the path where Administrator will send the user when they click the logout link
     *
     * @type string
     */
    'logout_path' => false,

    /*
     * 允许在登录成功后使用 Session::get('redirect') 将用户重定向到原本想要访问的后台页面
     *
     * @type string
     */
    'login_redirect_key' => 'redirect',

    /*
     * Global default rows per page
     *
     * @type int
     */
    'global_rows_per_page' => 20,

    /*
     * An array of available locale strings. This determines which locales are available in the languages menu at the top right of the Administrator
     * interface.
     *
     * @type array
     */
    'locales' => [],

    //'custom_routes_file' => app_path('Http/routes/administrator.php'),
);
