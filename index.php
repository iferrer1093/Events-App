<?php
require_once __DIR__ . '/data/db.php';
require_once __DIR__ . '/data/functions.php';
session_start();

$pdo = get_pdo();

function require_admin_login() {
    if (empty($_SESSION['admin_id'])) {
        header('Location: ?view=login');
        exit;
    }
}

$view = filter_input(INPUT_GET, 'view') ?: 'events_list';
$action = filter_input(INPUT_POST, 'action',);


switch ($action) {
    case 'register':
        $event_id = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);
        $name = trim((string)filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW));
        $email = trim((string)filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));

        if (!$event_id || !$name || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $register_error = "Please complete the form correctly.";
            $view = 'event_details';
            break;
        }
        registration_create($pdo, $event_id, $name, $email);
        $event = event_get($pdo, $event_id);
        $registered_name = $name;
        $registered_event_title = $event ? $event['title'] : '';
        $view = 'register_success';
        break;
}

// These are all of the admin actions.
if ($action && in_array($action, ['login','admin_logout','admin_create_event','admin_update_event','admin_delete_event'], true)) {
    switch ($action) {
        case 'login':
            $username = trim((string)filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW));
            $password = (string)filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

            if ($username && $password) {
                $admin = admin_find_by_username($pdo, $username);
                if ($admin && password_verify($password, $admin['password_hash'])) {
                    $_SESSION['admin_id'] = (int)$admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    $view = 'admin_events';
                } else {
                    $admin_login_error = "Invalid username or password.";
                    $view = 'login';
                }
            } else {
                $admin_login_error = "Enter both fields.";
                $view = 'login';
            }
            break;

        case 'admin_logout':
            $_SESSION = [];
            session_destroy();
            session_start();
            $view = 'events_list';
            break;

        case 'admin_create_event':
            require_admin_login();
            $title = trim((string)filter_input(INPUT_POST, 'title', FILTER_UNSAFE_RAW));
            $event_date = trim((string)filter_input(INPUT_POST, 'event_date', FILTER_UNSAFE_RAW));
            $location = trim((string)filter_input(INPUT_POST, 'location', FILTER_UNSAFE_RAW));
            $description = trim((string)filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW));
            if ($title && $event_date && $location && $description && strtotime($event_date) !== false) {
                events_create($pdo, $title, $event_date, $location, $description);
                $view = 'admin_events';
            } else {
                $admin_event_error = "Complete all fields and use a valid date/time.";
                $view = 'admin_event_form';
            }
            break;

        case 'admin_update_event':
            require_admin_login();
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $title = trim((string)filter_input(INPUT_POST, 'title', FILTER_UNSAFE_RAW));
            $event_date = trim((string)filter_input(INPUT_POST, 'event_date', FILTER_UNSAFE_RAW));
            $location = trim((string)filter_input(INPUT_POST, 'location', FILTER_UNSAFE_RAW));
            $description = trim((string)filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW));
            if ($id && $title && $event_date && $location && $description && strtotime($event_date) !== false) {
                events_update($pdo, $id, $title, $event_date, $location, $description);
                $view = 'admin_events';
            } else {
                $admin_event_error = "Complete all fields and use a valid date/time.";
                $view = 'admin_event_form';
            }
            break;

        case 'admin_delete_event':
            require_admin_login();
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if ($id) {
                events_delete($pdo, $id);
            }
            $view = 'admin_events';
            break;
    }
}

// This whole chunk loads the data for the events. 
if ($view === 'events_list') {
    $events = events_get_upcoming($pdo);
} elseif ($view === 'event_details') {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        $event = event_get($pdo, $id);
        if (!$event) {
            $view = 'events_list';
            $events = events_get_upcoming($pdo);
        }
    } else {
        header('Location: ?view=events_list');
        exit;
    }
} elseif ($view === 'admin_events') {
    require_admin_login();
    $all_events = events_all($pdo);
} elseif ($view === 'admin_event_form') {
    require_admin_login();
    $edit_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $editing_event = $edit_id ? event_get($pdo, $edit_id) : null;
} elseif ($view === 'admin_registrations') {
    require_admin_login();
    $regs_grouped = registrations_grouped_by_event($pdo);
}

?>


<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Event Planner</title>
<link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<?php include __DIR__ . '/components/nav.php'; ?>
<main class="container">
    <?php
    if ($view === 'events_list') {
        include __DIR__ . '/partials/events_list.php';
    } elseif ($view === 'event_details') {
        include __DIR__ . '/partials/event_details.php';
    } elseif ($view === 'register_success') {
        include __DIR__ . '/partials/register_success.php';
    } elseif ($view === 'login') {
        include __DIR__ . '/partials/login.php';
    } elseif ($view === 'admin_events') {
        include __DIR__ . '/partials/admin_events.php';
    } elseif ($view === 'admin_event_form') {
        include __DIR__ . '/partials/admin_event_form.php';
    } elseif ($view === 'admin_registrations') {
        include __DIR__ . '/partials/admin_registrations.php';
    } else {
        echo "<p>Page not found.</p>";
    }
    ?>
</main>
</body>
</html>
