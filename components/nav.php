<header class="site-header">
  <nav class="nav">
    <a href="?view=events_list" class="brand">Event Planner</a>
    <div class="nav-links">
      <a href="?view=events_list">Events</a>
      <?php if (!empty($_SESSION['admin_id'])): ?>
        <a href="?view=admin_events">Manage Events</a>
        <a href="?view=admin_registrations">Registrations</a>
        <form method="post" style="display:inline">
            <input type="hidden" name="action" value="admin_logout">
            <button type="submit" class="link">Logout</button>
        </form>
      <?php else: ?>
        <a href="?view=login">Admin</a>
      <?php endif; ?>
    </div>
  </nav>
</header>
