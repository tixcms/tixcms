<?php foreach($plugins as $plugin):?>
    <h4><?=str_replace(array("/", '*'), array('', ''), $plugin->getDocComment())?></h4>
    
    <p>
        <code>{{<?=$this->module->url?>:<?=$plugin->name?><?php foreach($plugin->getParameters() as $param):?>
                <?=$param->name?>=""<?php endforeach?>}}</code>
    </p>
    
    <p>
        <?php foreach($plugin->getParameters() as $param):?>
            <strong><?=$param->name?></strong> - <?=$param->getDefaultValue()?><br />
        <?php endforeach?>
    </p>
    <br />
<?php endforeach?>