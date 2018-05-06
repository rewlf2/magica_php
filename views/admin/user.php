<h5><div class="error" name="error" id="error" style="font-size: 12px; color:#f1c40f;"></div></h5>
<div class="table-responsive-md content-block">
  <table class="table">
    <thead>
        <tr>
            <td></td>
            <td colspan=4>
            
            <div class="btn-group-justified">
                <div class="btn-group">
                    <input type="text" class="form-control" id="cred" placeholder="Username or Email" value=<?php echo isset($_GET['cred']) ? $_GET['cred'] : ""; ?>>
                </div>
                <div class="btn-group">
                    <a class="btn btn-default" onclick="this.href='?controller=admin&action=user&cred='+document.getElementById('cred').value">Search</a>
                </div>
                <div class="btn-group">
                    <a class="btn btn-default" onclick="this.href='?controller=admin&action=user'">Reset</a>
                </div>
            </div>
            </td>
        </tr>
        <tr>
            <td>UID</td><td>User name</td><td>Email</td><td>Nickname</td><td>Role</td><td>AP</td><td>HP</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $count = 0;
        foreach($users as $user) {
            $count ++;
            echo '<tr>
                    <td><div id="uid'.$count.'">'.$user->uid.'</div></td>
                    <td><input type="text" class="form-control" id="username'.$count.'" value="'.$user->username.'"></td>
                    <td><input type="text" class="form-control" id="email'.$count.'" value="'.$user->email.'"></td>
                    <td><input type="text" class="form-control" id="nickname'.$count.'" value="'.$user->nickname.'"></td>
                    <td>
                        <div class="form-group">
                            <select class="form-control" id="role'.$count.'">';
                            switch ($user->role) {
                                case 'admin':
                                    echo '<option selected>admin</option>
                                          <option>player</option>';
                                break;
                                case 'player':
                                    echo '<option>admin</option>
                                          <option selected>player</option>';
                                break;
                                default:
                                    echo '<option>admin</option>
                                          <option selected>player</option>';
                                break;
                            }
                        echo '</select>
                        </div> 
                    </td>
                    <td>'.$user->ap_current.'/'.$user->ap_max.'</td>
                    <td>'.$user->hp_current.'/'.$user->hp_max.'</td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan=2>
                        <div class="btn-group-justified">
                            <div class="btn-group">
                                <input type="text" class="form-control" id="datepicker'.$count.'" value="'.$user->ban_time.'"/>
                            </div>
                            <script type="text/javascript">
                                $(function () {
                                    $("#datepicker'.$count.'").datepicker({
                                        format: "yyyy-mm-dd 23:59:59"
                                        });
                                });
                            </script>
                            <div class="btn-group">
                            <a class="btn btn-warning" onclick="ajaxPost('."'".'ban'."'".',
                            '."'".$count."'".')">Issue/Lift Ban</a>
                            </div>
                        </div>
                    </td>
                    <td><a class="btn btn-warning" onclick="ajaxPost('."'".'update'."'".',
                    '."'".$count."'".')">Update</a></td>
                    <td><a class="btn btn-danger" onclick="ajaxPost('."'".'role'."'".',
                    '."'".$count."'".')">Change Role</a></td>
                    <td colspan=2><a class="btn btn-warning" onclick="ajaxPost('."'".'resetticks'."'".',
                    '."'".$count."'".')">Reset ticks</a></td>
                </tr>
            ';
        }
        $offset = isset($_GET['offset']) ? $_GET['offset'] : "0";
        $offset = intval($offset);
        $min = $offset+1;
        $max = $offset+$count;
        echo "<tr><td colspan=6 class='center-cell'>Showing records ".$min."-".$max."<br/>".$pagination->getPaginationHtml()."<br/>";
        ?>
        <div hidden id="offset"><?php echo isset($_GET['offset']) ? $_GET['offset'] : "0"; ?></div>Records per page:
        <input type="text" class="btn-default" id="limit" placeholder="Record per page" value=<?php echo isset($_GET['limit']) ? $_GET['limit'] : "10"; ?>>
        <a href="" class="btn btn-default" 
                    onclick="this.href='?controller=admin&action=user&limit='+document.getElementById('limit').value+'&offset='+document.getElementById('offset').innerHTML">Change</a></td></tr>
    </tbody>
  </table>
</div>