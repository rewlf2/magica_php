<div class="table-responsive-md">
  <table class="table">
    <tr>
        <td>
            <b><a href="?controller=admin&action=ip_block">Manage IP blocks</a></b><br/><br/>
            <?php echo $number_of_block; ?> IP blocks in effect.
        </td>
        <td>
            <b><a href="?controller=admin&action=user">Manage Users</a></b><br/><br/>
            <?php echo $number_of_user['player']; ?> players and <?php echo $number_of_user['admin']; ?> admins
        </td>
    </tr>
    <tr>
        <td>
            <b><a href="?controller=admin&action=session">Manage Login records</a></b><br/><br/>
            <?php echo $number_of_session['sessions']; ?> login records from <?php echo $number_of_session['uids']; ?> users
        </td>
        <td>
            <b><a href="?controller=admin&action=user_log">Manage User logs</a></b>
        </td>
    </tr>
  </table>
</div>