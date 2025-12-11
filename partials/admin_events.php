<h1>Manage Events</h1>
<p><a href="?view=admin_event_form">+ Create New Event</a></p>

<table class="table">
  <thead><tr><th>Title</th><th>Date</th><th>Location</th><th>Actions</th></tr></thead>
  <tbody>
    <?php foreach ($all_events as $ev): ?>
      <tr>
        <td><?= htmlspecialchars($ev['title']) ?></td>
        <td><?= date('M j, Y g:ia', strtotime($ev['event_date'])) ?></td>
        <td><?= htmlspecialchars($ev['location']) ?></td>
        <td>
          <a href="?view=admin_event_form&id=<?= (int)$ev['id'] ?>">Edit</a>
          <form method="post" style="display:inline" onsubmit="return confirm('Delete this event?');">
            <input type="hidden" name="action" value="admin_delete_event">
            <input type="hidden" name="id" value="<?= (int)$ev['id'] ?>">
            <button type="submit" class="link">Delete</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
