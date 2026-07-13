<?php
require_once __DIR__ . '/../config/config.php';

spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    $file = __DIR__ . '/../' . strtolower($class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH) ?: '/';
$basePath = parse_url(BASE_URL, PHP_URL_PATH);

if ($basePath && strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
    if ($path === '' || $path[0] !== '/') {
        $path = '/' . $path;
    }
} elseif (strpos($path, '/cabinet-medical-lezodiaque/public') === 0) {
    $path = str_replace('/cabinet-medical-lezodiaque/public', '', $path);
    if ($path === '' || $path[0] !== '/') {
        $path = '/' . $path;
    }
}

if ($path === '') {
    $path = '/';
}

$routes = [
    '/' => 'DashboardController@index',
    '/login' => 'AuthController@login',
    '/logout' => 'AuthController@logout',
    '/dashboard' => 'DashboardController@index',
    '/patients' => 'PatientController@index',
    '/patients/create' => 'PatientController@create',
    '/patients/store' => 'PatientController@store',
    '/patients/edit' => 'PatientController@edit',
    '/patients/update' => 'PatientController@update',
    '/patients/delete' => 'PatientController@delete',
    '/appointments' => 'AppointmentController@index',
    '/appointments/create' => 'AppointmentController@create',
    '/appointments/store' => 'AppointmentController@store',
    '/appointments/edit' => 'AppointmentController@edit',
    '/appointments/update' => 'AppointmentController@update',
    '/appointments/delete' => 'AppointmentController@delete',
    '/medical-files' => 'MedicalFileController@index',
    '/medical-files/view' => 'MedicalFileController@view',
    '/medical-files/export-pdf' => 'MedicalFileController@exportPDF',
    '/hospitalizations' => 'HospitalizationController@index',
    '/hospitalizations/create' => 'HospitalizationController@create',
    '/hospitalizations/store' => 'HospitalizationController@store',
    '/hospitalizations/discharge' => 'HospitalizationController@discharge',
    '/consultations/create' => 'ConsultationController@create',
    '/consultations/store' => 'ConsultationController@store',
];

function isAuthenticated() {
    return isset($_SESSION['user_id']);
}

if (!isAuthenticated() && $path !== '/login') {
    redirect('login');
}

if (isset($routes[$path])) {
    [$controllerName, $methodName] = explode('@', $routes[$path]);
    $controllerFile = __DIR__ . '/../app/controllers/' . $controllerName . '.php';

    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $controller = new $controllerName();
        $controller->$methodName();
    } else {
        http_response_code(404);
        echo 'Contrôleur non trouvé';
    }
} else {
    http_response_code(404);
    echo 'Page non trouvée';
}

