<div <?php echo HTML::parse_attributes($attr)?>>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo $header?></h3>
    </div>
    <div class="modal-body">
        <?php echo $body?>
    </div>
    <div class="modal-footer">
        <?php echo $footer?>
    </div>
</div>