<?php
// These are all of the public functions. 

function events_get_upcoming(PDO $pdo) : array {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE event_date >= NOW() ORDER BY event_date ASC");
    $stmt->execute();
    return $stmt->fetchAll();
}

function event_get(PDO $pdo, int $id) {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}

function registration_create(PDO $pdo, int $event_id, string $name, string $email) {
    $stmt = $pdo->prepare("INSERT INTO registrations (event_id, name, email) VALUES (:event_id, :name, :email)");
    return $stmt->execute([
        ':event_id' => $event_id,
        ':name' => $name,
        ':email' => $email
    ]);
}

// These are all of the admin functions. 

function admin_find_by_username(PDO $pdo, string $username) {
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username");
    $stmt->execute([':username' => $username]);
    return $stmt->fetch();
}

function events_create(PDO $pdo, string $title, string $event_date, string $location, string $description) {
    $stmt = $pdo->prepare("INSERT INTO events (title, event_date, location, description) VALUES (:title, :event_date, :location, :description)");
    return $stmt->execute([
        ':title' => $title,
        ':event_date' => $event_date,
        ':location' => $location,
        ':description' => $description
    ]);
}

function events_update(PDO $pdo, int $id, string $title, string $event_date, string $location, string $description) {
    $stmt = $pdo->prepare("UPDATE events SET title = :title, event_date = :event_date, location = :location, description = :description WHERE id = :id");
    return $stmt->execute([
        ':title'=>$title,
        ':event_date'=>$event_date,
        ':location'=>$location,
        ':description'=>$description,
        ':id'=>$id
    ]);
}

function events_delete(PDO $pdo, int $id) {
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = :id");
    return $stmt->execute([':id' => $id]);
}

function events_all(PDO $pdo) : array {
    $stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC");
    return $stmt->fetchAll();
}

function registrations_grouped_by_event(PDO $pdo) : array {
    $stmt = $pdo->query("
        SELECT r.*, e.title, e.event_date
        FROM registrations r
        JOIN events e ON e.id = r.event_id
        ORDER BY e.event_date ASC, r.registered_at ASC
    ");
    $rows = $stmt->fetchAll();
    $grouped = [];
    foreach ($rows as $r) {
        $grouped[$r['event_id']]['event'] = [
            'id' => $r['event_id'],
            'title' => $r['title'],
            'event_date' => $r['event_date']
        ];
        $grouped[$r['event_id']]['registrations'][] = $r;
    }
    return $grouped;
}
