<?php
if($count > 0){
    foreach($groups as $row)
    {
        $qr = "{'name':'".$row->name."'}";
    ?>
    
      <div class="col-sm-4 col-md-2">
        <div class="thumbnail" style="border: 0">
          <?php echo '<img class="img-responsive img-thumbnail" src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl='.$qr.'&choe=UTF-8" style="margin: 0 auto;display: block;">'; ?>
          <div class="caption">
            <h3 style="text-align: center;" class="ellipsis"><?php echo $row->name; ?></h3>
            <?php echo '<div style="display: flex;align-items: center;justify-content: center;"><a href="https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl='.$qr.'&choe=UTF-8" target="_blank"><button class="btn btn-primary" style="margin-right: 5px;display: block;">Save</button></a>'; ?>
            <?php echo '<a href="'.site_url().'main/deletehistoryqr/'.$row->id.'""><button class="btn btn-primary" style="margin-left: 5px;display: block;">Delete</button></a></div>'; ?>
          </div>
        </div>
      </div>

    <?php
    }
}else{
    echo '<div class="alert alert-warning" role="alert">There\'s no data.</div>';
}
?>