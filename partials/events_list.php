<h1>Upcoming Events</h1>
<?php if (empty($events)): ?>
    <p>No upcoming events at the moment.</p>
<?php else: ?>
  <div class="events-grid">
    <?php foreach ($events as $e): ?>
      <article class="event-card">
        <h2><?= htmlspecialchars($e['title']) ?></h2>
        <p class="meta"><?= date('M j, Y g:ia', strtotime($e['event_date'])) ?> — <?= htmlspecialchars($e['location']) ?></p>
        <p><?= ($e['description']) ?></p>
        <p><a href="?view=event_details&id=<?= (int)$e['id'] ?>">Details & Register →</a></p>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
