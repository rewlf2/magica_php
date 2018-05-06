<h5><div class="error" name="error" id="error" style="font-size: 12px; color:#f1c40f;"></div></h5>
<div class="table-responsive-md content-block">
  <table class="table">
    <thead>
        <tr>
            <td></td>
            <td><input type="text" class="form-control" id="cred" placeholder="Username or Email" value=<?php echo isset($_GET['cred']) ? $_GET['cred'] : ""; ?>></td>
            <td><input type="text" class="form-control" id="type" placeholder="Type" value=<?php echo isset($_GET['type']) ? $_GET['type'] : ""; ?>></td>
            <td><input type="text" class="form-control" id="ip" placeholder="IP" value=<?php echo isset($_GET['ip']) ? $_GET['ip'] : ""; ?>></td>
            <td><a class="btn btn-default" onclick='clearForm();'>Reset</a></td>
            <td><div hidden id="offset"><?php echo isset($_GET['offset']) ? $_GET['offset'] : "0"; ?></div>Records per page:</td>
        </tr>
        <tr>
            <td colspan=2></td>
            <td><input type="number" class="form-control" id="min_importance" value="1" min="1" max="5"/></td>
            <td>
                <div>
                    <input type="text" class="form-control" id="min_date" value="<?php echo isset($_GET['min_date']) ? $_GET['min_date'] : date("Y-m-d", time()-86400);?>"/>
                    <input type="text" class="form-control" id="max_date" value="<?php echo isset($_GET['max_date']) ? $_GET['max_date'] :date("Y-m-d");?>"/>
                </div>
                <script type="text/javascript">
                    $(function () {
                        $("#min_date").datepicker({
                            format: "yyyy-mm-dd"
                            });
                    });
                    $(function () {
                        $("#max_date").datepicker({
                            format: "yyyy-mm-dd"
                            });
                    });
                    function clearForm() {
                        document.getElementById('cred').value = "";
                        document.getElementById('type').value = "";
                        document.getElementById('ip').value = "";
                        document.getElementById('offset').value = "0";
                        document.getElementById('min_importance').value = "1";
                        document.getElementById('min_date').value = "";
                        document.getElementById('max_date').value = "";
                        document.getElementById('limit').value = "100";
                    };
                </script>
            </td>
            <td><a href="" class="btn btn-default" 
                    onclick="this.href='?controller=admin&action=user_log&cred='+document.getElementById('cred').value+'&type='+document.getElementById('type').value+'&ip='+document.getElementById('ip').value+'&min_importance='+document.getElementById('min_importance').value+'&min_date='+document.getElementById('min_date').value+'&max_date='+document.getElementById('max_date').value+'&limit='+document.getElementById('limit').value+'&offset='+document.getElementById('offset').innerHTML">Search</a></td>
            <td><input type="text" class="form-control" id="limit" placeholder="Record per page" value=<?php echo isset($_GET['limit']) ? $_GET['limit'] : "100"; ?>></td>
        </tr>
        <tr>
            <td>UID</td><td>Type</td><td>IP</td><td>Description</td><td>Date</td><td>Importance</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $count = 0;
        foreach($user_logs as $user_log) {
            $count ++;
            echo '
                <tr>
                    <td>'.$user_log->uid.'</td>
                    <td>'.$user_log->type.'</td>
                    <td>'.$user_log->ip.'</td>
                    <td>'.$user_log->description.'</td>
                    <td>'.$user_log->date.'</td>
                    <td>'.$user_log->importance.'</td>
                </tr>
            ';
        }
        echo "<tr>";

        $offset = isset($_GET['offset']) ? $_GET['offset'] : "0";
        $offset = intval($offset);
        $min = $offset+1;
        $max = $offset+$count;
        echo "<td colspan=6 class='center-cell'>Showing records ".$min."-".$max."<br/>".$pagination->getPaginationHtml()."</td></tr>";
        ?>
    </tbody>
  </table>
</div>