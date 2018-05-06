<h5><div class="error" name="error" id="error" style="font-size: 12px; color:#f1c40f;"></div></h5>
<div class="table-responsive-md content-block">
  <table class="table">
    <thead>
        <tr>
            <td>IP</td><td>Date</td><td>Age (Hour)</td>
        </tr>
    </thead>
    <tbody>
        <tr>
        <?php
        $count = 0;
        foreach($sessions as $session) {
            $count ++;
            echo '
                <tr>
                    <td><div hidden id="sid'.$count.'">'.$session->session_id.'</div>'.$session->ip.'</td>
                    <td>'.$session->date.'</td>
                    <td>'.$session->hour.'</td>
                    <td>';

                    if ($session->hour ==0 && $session->minute ==0) {
                        echo "Current";
                    } else {
                        echo '<a class="btn btn-warning" onclick="ajaxPost('."'".'destroy'."'".',
                    '."'".$count."'".')">Destroy</a>';
                    }
            echo '</td></tr>';
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
        
        </tbody>
    </table>
</div>
<div hidden id="uid"><?php echo $session->uid;?></div>