<h1>Registrations by Event</h1>
<?php if (empty($regs_grouped)): ?>
  <p>No registrations yet.</p>
<?php else: ?>
  <?php foreach ($regs_grouped as $gid => $data): ?>
    <section class="event-group">
      <h2><?= htmlspecialchars($data['event']['title']) ?> <small>(<?= date('M j, Y g:ia', strtotime($data['event']['event_date'])) ?>)</small></h2>
      <?php if (empty($data['registrations'])): ?>
        <p>No registrations for this event.</p>
      <?php else: ?>
        <table class="table">
          <thead><tr><th>Name</th><th>Email</th><th>Registered</th></tr></thead>
          <tbody>
            <?php foreach ($data['registrations'] as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['name']) ?></td>
                <td><?= htmlspecialchars($r['email']) ?></td>
                <td><?= date('M j, Y g:ia', strtotime($r['registered_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </section>
  <?php endforeach; ?>
<?php endif; ?>
