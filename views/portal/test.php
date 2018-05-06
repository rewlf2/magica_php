<p>Testing SQL</p>
<?php foreach($users as $user) { ?>
  <p>
    <?php echo 'uid: '.$user->uid."<br/>"; ?>
    <?php echo 'ap_max: '.$user->ap_max."<br/>"; ?>
    <?php echo 'ap_current: '.$user->ap_current."<br/>"; ?>
    <?php echo 'ap_losstick: '.$user->ap_losstick."<br/>"; ?>
    <?php echo 'ap_extra: '.$user->ap_extra."<br/>"; ?>
    <?php echo 'hp_max: '.$user->hp_max."<br/>"; ?>
    <?php echo 'hp_current: '.$user->hp_current."<br/>"; ?>
    <?php echo 'hp_losstick: '.$user->hp_losstick."<br/>"; ?>
    <?php echo 'hp_extra: '.$user->hp_extra."<br/>"; ?>
  </p>
<?php } ?>
