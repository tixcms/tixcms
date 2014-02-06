<tr>
    <td style="width: 20%;">
        <?=$item->name?>
    </td>
    <?php foreach($groups as $group):?>
        <td style="text-align: center; width: <?=(int)80/count($groups)?>%">
            <input 
                type="checkbox" 
                style="vertical-align: top;"
                name="groups[<?=$group->alias?>][modules][<?=$item->url?>]"
                <?=(
                        (isset($permissions[$group->alias]->permissions[$item->url]) 
                        AND $permissions[$group->alias]->permissions[$item->url])
                        OR $group->alias == 'admins'
                    ) 
                        ? ' checked="checked"' 
                        : ''
                ?> 
                <?php if( $group->default ):?>
                    disabled="disabled"
                <?php endif?>
            />
            <span>Общий доступ</span>
            
            <?php if( isset($sub_permissions[$item->url]) ):?>
            
                <?php foreach($sub_permissions[$item->url] as $key=>$value):?>
                    <div>
                        <input 
                            type="checkbox" 
                            style="vertical-align: top;"
                            name="groups[<?=$group->alias?>][modules][<?=$item->url .'_'. $key?>]"
                            <?=(
                                    (isset($permissions[$group->alias]->permissions[$item->url .'_'. $key]) 
                                    AND $permissions[$group->alias]->permissions[$item->url .'_'. $key])
                                    OR $group->alias == 'admins'
                                )
                                    ? ' checked="checked"' 
                                    : ''
                            ?> 
                            <?php if( $group->default ):?>
                                disabled="disabled"
                            <?php endif?>
                        />
                        <?=$value?>
                    </div>
                    
                <?php endforeach?>
            
            <?php endif?>
        </td>
    <?php endforeach?>
</tr>