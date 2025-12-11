<?php
$editing = !empty($editing_event);
?>
<h1><?= $editing ? 'Edit Event' : 'Create Event' ?></h1>
<?php if (!empty($admin_event_error)): ?>
  <p class="error"><?= htmlspecialchars($admin_event_error) ?></p>
<?php endif; ?>

<form method="post" action="">
  <input type="hidden" name="action" value="<?= $editing ? 'admin_update_event' : 'admin_create_event' ?>">
  <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int)$editing_event['id'] ?>"><?php endif; ?>
  <div>
    <label>Title<input type="text" name="title" required value="<?= $editing ? htmlspecialchars($editing_event['title']) : '' ?>"></label>
  </div>
  <div>
    <label>Date & Time<input type="datetime-local" name="event_date" required value="<?= $editing ? date('Y-m-d\TH:i', strtotime($editing_event['event_date'])) : '' ?>"></label>
  </div>
  <div>
    <label>Location<input type="text" name="location" required value="<?= $editing ? htmlspecialchars($editing_event['location']) : '' ?>"></label>
  </div>
  <div>
    <label>Description<textarea name="description" required><?= $editing ? htmlspecialchars($editing_event['description']) : '' ?></textarea></label>
  </div>
  <div><button type="submit"><?= $editing ? 'Save' : 'Create' ?></button></div>
</form>
