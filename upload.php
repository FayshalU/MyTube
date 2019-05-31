<?php 
    require_once("header.php"); 
    require_once("classes/videoProvider.php"); 
?>

<div class="column">

    <?php
        $formProvider = new VideoDetails($con);
        echo $formProvider->createUploadForm();
    ?>

</div>

<script>
    $("form").submit(function(){
        $("#loadingModal").modal("show");
    });
</script>

<!-- Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
            <img src="assets/images/loading.gif" alt="Loading">
            <h4>Please wait...This might take a while.</h4>
        </center>
      </div>
      
    </div>
  </div>
</div>

<?php require_once("footer.php"); ?>
