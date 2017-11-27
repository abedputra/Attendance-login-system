    <div class="container">
        <h2>Users</h2>
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped">
              <tr>
                  <th>
                      Name
                  </th>
                  <th>
                      User Name
                  </th>
                  <th>
                      Last Login
                  </th>
                  <th>
                      Level Name
                  </th>
                  <th>
                      Status
                  </th>
                  <th colspan="2">
                      Edit
                  </th>
              </tr>
                    <?php
                        foreach($groups as $row)
                        { 
                        if($row->role == 1){
                            $rolename = "Admin";
                        }elseif($row->role == 2){
                            $rolename = "Author";
                        }elseif($row->role == 3){
                            $rolename = "Editor";
                        }elseif($row->role == 4){
                            $rolename = "Subscriber";
                        }
                        
                        echo '<tr>';
                        echo '<td>'.$row->first_name.'</td>';
                        echo '<td>'.$row->email.'</td>';
                        echo '<td>'.$row->last_login.'</td>';
                        echo '<td>'.$rolename.'</td>';
                        echo '<td>'.$row->status.'</td>';
                        echo '<td><a href="'.site_url().'main/changelevel"><button type="button" class="btn btn-primary">Role</button></a></td>';
                        echo '<td><a href="'.site_url().'main/deleteuser/'.$row->id.'"><button type="button" class="btn btn-danger">Delete</button></a></td>';
                        echo '</tr>';
                        }
                    ?>
            </table>
        </div>
    </div>