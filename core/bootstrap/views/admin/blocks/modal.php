<div <?=HTML\Tag::parse_attributes($attrs)?>>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3>
            <?=$header?>
        </h3>
    </div>
    <div class="modal-body">
        <?=$body?>
    </div>
    <div class="modal-footer">
        <?=$footer?> 
    </div>
</div>