<?php
include_once("models/game_config.php");
$gc = new GameConfig();
?>
<div class="table-responsive">
  <h2>Game setting</h2>          
  <table class="table">
    <tbody>
      <tr>
        <td>Purge cooldown</td>
        <td><?php echo $gc->gcPurgeInterval() ?>s</td>
      </tr>
      <tr>
        <td>AP restoration time</td>
        <td><?php echo $gc->gcApInterval() ?>s</td>
      </tr>
      <tr>
        <td>AP initial maximum</td>
        <td><?php echo $gc->gcApMaxInit() ?></td>
      </tr>
      <tr>
        <td>AP initial maximum</td>
        <td><?php echo $gc->gcHpInterval() ?></td>
      </tr>
      <tr>
        <td>Account expiry time</td>
        <td><?php echo $gc->gcAccExpire() ?> days</td>
      </tr>
    </tbody>
  </table>
</div>