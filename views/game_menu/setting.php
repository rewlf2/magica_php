<h5><div class="error" name="error" id="error" style="font-size: 12px; color:#f1c40f;"></div></h5>
<div class="table-responsive-md content-block">
  <table class="table">
    <thead>
        <tr>
            <td>UID</td><td>User name</td><td>Email</td><td>Nickname</td><td>Role</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php
                echo '
                    <td><div id="uid">'.$user->uid.'</div></td>
                    <td><div id="username">'.$user->username.'</div></td>
                    <td><input type="text" class="form-control" id="email" value="'.$user->email.'"></td>
                    <td><input type="text" class="form-control" id="nickname" value="'.$user->nickname.'"></td>
                    <td><div id="role">'.$user->role.'</div></td>
                        
                    <td><a class="btn btn-warning" onclick="ajaxPost('."'".'update'."'".')">Update</a></td>
                    </td>
                    </tr>
                ';
            ?>
        </tr>
        <tr>
            <td colspan=6><h6>Contact administrator if you wish to change User name or role</h6></td>
        </tr>
        <tr>
            <td colspan=3><b><a href="?controller=game_menu&action=setting_session">View <?php echo $number_of_session['sessions']; ?> sessions</a></b></td>
        </tr>
        </tbody>
    </table>
</div>