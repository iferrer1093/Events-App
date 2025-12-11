<?php
if (empty($event)) {
    echo "<p>Event not found.</p>";
    return;
}
?>
<article class="event-detail">
  <h1><?= htmlspecialchars($event['title']) ?></h1>
  <p class="meta"><?= date('M j, Y g:ia', strtotime($event['event_date'])) ?> — <?= htmlspecialchars($event['location']) ?></p>
  <div class="description"><?=(htmlspecialchars($event['description'])) ?></div>

  <hr>

  <h2>Register for this event</h2>
  <?php if (!empty($register_error)): ?>
    <p class="error"><?= htmlspecialchars($register_error) ?></p>
  <?php endif; ?>
  <form method="post" action="">
    <input type="hidden" name="action" value="register">
    <input type="hidden" name="event_id" value="<?= (int)$event['id'] ?>">
    <div>
      <label>Name <input type="text" name="name" required></label>
    </div>
    <div>
      <label>Email <input type="email" name="email" required></label>
    </div>
    <div><button type="submit">Register</button></div>
  </form>
</article>
