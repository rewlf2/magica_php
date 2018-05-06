<p>Best players in the game</p>

<?php foreach($users as $user) { ?>
  <p>
    <?php echo $user->nickname; ?>
    <!-- <a href='?controller=posts&action=show&id=<?php echo $user->uid; ?>'>See content</a> -->
  </p>
<?php } ?>