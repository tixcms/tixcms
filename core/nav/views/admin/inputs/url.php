<div class="control-group">
    <label class="control-label" for="">
        <?=$label?>
    </label>
    <div class="controls">
        <div class="input-prepend" <?=($url_type AND $url_type != \Nav_m::TYPE_URL) ? ' style="display: none;"' : ''?>>
            <span class="add-on">
                <?=URL::base_url()?>
            </span>

            <input type="text" value="<?=$value?>" class="input_url_text" name="url" style="width: 150%;"  />
        </div>
        
        <select <?=$url_type != \Nav_m::TYPE_PAGE ? ' style="display: none;"' : ''?> class="input_url_page input_url">
            <?php if( $data['pages'] ):?>
                <?php foreach($data['pages'] as $page):?>
                    <option value="<?=$page->full_url?>" <?=$page->full_url == $value ? ' selected="selected"' : ''?>>
                        <?=str_repeat('&nbsp;&nbsp;', $page->level - 1) . $page->title?>
                    </option>
                <?php endforeach;?>
            <?php else:?>
                <option>Нет страниц</option>
            <?php endif?>
        </select>
    </div>
</div>