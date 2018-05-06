<div class="content-block">
    <h4>IP blocks with remarks are not erased.<br/>
    Otherwise, they are erased whenever a successful login is recorded at that IP.<br/>
    IP block may be issued from a potential IP by setting its expiry time later than current time.</h4>

    <h5><div class="error" name="error" id="error" style="font-size: 12px; color:#f1c40f;"></div></h5>
    <div class="table-responsive-md">
    <table class="table">
        <thead>
            <tr>
                <td colspan='4'><b>Issue IP ban</b></td>
            </tr>
            <tr>
                <td>IP</td><td>Expiry time</td><td>Remarks</td><td></td>
            </tr>
            <tr>
                <td><input type="text" class="form-control" id="ipname0"/></td>
                <td>
                    <div>
                        <input type="text" class="form-control" id="datepicker0"/>
                    </div>
                    <script type="text/javascript">
                        $(function () {
                            $("#datepicker0").datepicker({
                                format: "yyyy-mm-dd 23:59:59"
                                });
                        });
                    </script>
                </td>
                <td><input type="text" class="form-control" id="remarks0"/></td>
                <td><a class="btn btn-warning" onclick="ajaxPost('create')">Issue</a></td> <!-- Parameters got in ajax -->
            </tr>
        </thead>
        <thead>
            <tr>
                <td><b><?php
                if (isset($_GET['type'])) {
                    if (strcmp($_GET['type'], 'active') ==0)
                        echo "Active blocks";
                    else
                        echo "Expired blocks";
                }
                else {
                    echo "IP blocks";
                }
                ?></b></td>
                <td colspan=3>
                    <div class="btn-group">
                        <a class="btn" href="?controller=admin&action=ip_block">Show All</a><a class="btn" href="?controller=admin&action=ip_block&type=active">Active only</a><a class="btn" href="?controller=admin&action=ip_block&type=expired">Expired only</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>IP</td><td>Expiry time</td>
                <?php
                $need_active = true;
                $need_expired = true;

                if (isset($_GET['type'])) {
                    if (strcmp($_GET['type'], 'expired') ==0)
                        $need_active = false;
                }
                if (isset($_GET['type'])) {
                    if (strcmp($_GET['type'], 'active') ==0)
                        $need_expired = false;
                }

                if ($need_active)
                    echo '<td>Remaining time</td>';

                if ($need_expired)
                    echo '<td>Attack count</td>';
                ?>
                <td>Remarks</td>
            </tr>
        </thead>
        <tbody>
        <?php
            $count = 0;
            foreach($ip_blocks as $ip_block) {
                $count ++;
                echo '
                    <tr>
                        <td><div id="ipname'.$count.'">'.$ip_block->ip.'</div></td>
                        <td>
                            <div>
                                <input type="text" class="form-control" id="datepicker'.$count.'" value="'.$ip_block->ban_time.'"/>
                            </div>
                            <script type="text/javascript">
                                $(function () {
                                    $("#datepicker'.$count.'").datepicker({
                                        format: "yyyy-mm-dd 23:59:59"
                                        });
                                });
                            </script>
                        </td>';

                        // This function determines if a ip ban has expired by changing its time_diff to either 1 or -1
                        if ($need_active) {
                            if (preg_replace('/^(-{0,1})\d+:\d+:\d+/','${1}1',$ip_block->ban_remain >0))
                                echo "<td>".$ip_block->ban_remain."</td>";
                            else
                                echo "<td>Expired</td>";
                        }
                        if ($need_expired) {
                            echo "<td>".$ip_block->attack_count."</td>";
                        }

                        echo '</td>
                        <td>
                            <div class="input-group">
                                <input type="text" class="form-control" id="remarks'.$count.'" value="'.$ip_block->remarks.'">
                                <div class="input-group-append">
                                    <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item btn-default" onclick="ajaxPost('."'".'update'."'".',
                                            '."'".$count."'".')">Update</a><br/>
                                            <a class="dropdown-item btn-default" onclick="ajaxPost('."'".'delete'."'".',
                                            '."'".$count."'".')">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </td>
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
                        onclick="this.href='?controller=admin&action=ip_block&limit='+document.getElementById('limit').value+'&offset='+document.getElementById('offset').innerHTML">Change</a></td></tr>
        </tbody>
    </table>
    </div>
</div>