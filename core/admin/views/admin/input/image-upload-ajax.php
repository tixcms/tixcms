<div class="control-group">
    <label class="control-label" for="">
        <?php if( $help ):?>
            <span class="input-help" data-content="<?=$help?>"><?=$label?></span>
        <?php else:?>
            <?=$label?>
        <?php endif?>
    </label>
    <div class="controls">
        <div id="upload_photos">

            <div class="image">
                <div id="loader" style="display: none; text-align: center;"><?php echo Theme::image('ajax-loader.gif')?></div>
                
                <?php if ($this->session->userdata('preview_image') || ($game && $game->picture != '')): ?>
                <?php $picture = ($this->session->userdata('preview_image')) ? $this->session->userdata('preview_image') 
                                                                             : $game->picture;?>
                    <label class="cabinet" style="display: none;"> 
                        <input type="file" class="file" name="userfile" id="userfile" />
                    </label>
                    <span class="img">
                        <img id="preview" src="<?php echo URL::site_url('uploads/games/medium/'.$picture).'?'.time()?>" />
                        <br />
                        <a class="delete" title="Удалить"       href="preview">Удалить</a> / 
                        <a class="edit"   title="Редактировать" href="preview">Редактировать</a>
                    </span>
                <?php else: ?>
                    <label class="cabinet"> 
                        <input type="file" class="file" name="userfile" id="userfile" />
                    </label>
                    <span class="img" style="display: none; position: relative;">
                    </span>                
                <?php endif; ?>
                
            </div>  
     
        </div>
    </div>
</div>