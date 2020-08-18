<?php
$pageTitle = "Admin";

require_once 'includes/bootstrap.php';
requireAuth();
include_once('includes/header.php');
?>
<div class="container">
    <div class="well">
        <h2>Admin</h2>
        <div class="panel">
            <h4>Users</h4>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Registered</th>
                    <th>Promote/Demote</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach (getAllUsers() as $user): ?>
                    <tr>
                      <td><?php echo $user['username']; ?></td>
                      <td><?php echo $user['email']; ?></td>
                      <td><?php echo date('m/d/Y H:i:s', $user['datecreated']); ?></td>
                      <td>
                      <?php if ($user['role_id'] == 1): ?>
                      <a href="procedures/adjustRole.php?role=demote&userId=<?php echo $user['id']; ?>" class="bt btn-xs btn-warning">Demote from Admin</a>
                      <?php elseif ($user['role_id'] == 2): ?>
                      <a href="procedures/adjustRole.php?role=promote&userId=<?php echo $user['id']; ?>" class="bt btn-xs btn-success">Promote to Admin</a>
                      <?php endif; ?>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
<?php include("includes/footer.php");?>
