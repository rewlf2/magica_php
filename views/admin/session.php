<h5><div class="error" name="error" id="error" style="font-size: 12px; color:#f1c40f;"></div></h5>

<div class="table-responsive-md content-block">
  <table class="table">
    <thead>
        <tr>
        <td></td>
        <td colspan=2>
            <div class="input-group btn-group-justified">
            <input type="text" class="form-control" id="cred" placeholder="Enter Username or Email">
                <div class="input-group-append">
                    <a class="btn btn-warning" onclick="ajaxPost('search')">Search</a>
                    <a class="btn btn-default" onclick="this.href='?controller=admin&action=session'">Reset</a>
                </div>
            </div>
        </td>

        </tr>
        <tr><?php
        if(isset($_GET['uid'])) {
            if (strcmp($user->username, "")!=0) {
                echo "Record for ".$user->username." (Email: ".$user->email.")";
                echo "<a class='btn btn-danger' onclick='ajaxPost(".'"'."destroyall".'"'.")'>Destroy all sessions</a>";
                echo "<div hidden id='username'>".$user->username."</div>";
                echo "<div hidden id='uid'>".$user->uid."</div>";
            }
            else {
                echo "No user with this username or email exists";
            }
        }
        ?>
        </tr>
        <tr>
            <td>UID</td><td>IP</td><td>Date</td><td>Age (Hour)</td>
        </tr>
    </thead>
    <tbody> <div id="session-content">
        <?php

        $count = 0;
        foreach($sessions as $session) {
            $count ++;
            echo '
                <tr>
                    <td><div hidden id="sid'.$count.'">'.$session->session_id.'</div><div id="uid'.$count.'">'.$session->uid.'</div></td>
                    <td>'.$session->ip.'</td>
                    <td>'.$session->date.'</td>
                    <td>'.$session->hour.'</td>
                    <td><a class="btn btn-warning" onclick="ajaxPost('."'".'destroy'."'".',
                    '."'".$count."'".')">Destroy</a></td>
                </tr>
            ';
        }
        $offset = isset($_GET['offset']) ? $_GET['offset'] : "0";
        $offset = intval($offset);
        $min = $offset+1;
        $max = $offset+$count;
        echo "<tr><td colspan=4 class='center-cell'>Showing records ".$min."-".$max."<br/>".$pagination->getPaginationHtml()."<br/>";
        ?>
        <div hidden id="offset"><?php echo isset($_GET['offset']) ? $_GET['offset'] : "0"; ?></div>Records per page:
        <input type="text" class="btn-default" id="limit" placeholder="Record per page" value=<?php echo isset($_GET['limit']) ? $_GET['limit'] : "10"; ?>>
        <a class="btn btn-default" 
                    onclick="ajaxPost('search')">Change</a></td></tr>
    </div></tbody>
  </table>
</div>