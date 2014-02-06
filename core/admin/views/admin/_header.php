<div class="page-header">
    <?php if( $has_help ):?>
        <div class="pull-right module-help-link-wrap">
            <small>
                <?=URL::anchor(
                    'admin/'. $this->module->url() .'/help',
                    '<i class="icon-question-sign"></i>',
                    array(
                        'class'=>'module-help-link',
                        'data-placement'=>'left',
                        'title'=>lang('admin:help-title')
                    )
                )?>
            </small>
        </div>
    <?php endif?>
    <h2>
        <?=$this->module->name()?>
        
        <?php $permissions_class = ucfirst($this->module->url()) .'\Permissions'?>
        <?php $permissions = class_exists($permissions_class) ? new $permissions_class : false?>
        <?php $permissions = $permissions ? $permissions->get() : false?>
        
        <?php if( 
                $has_settings 
                AND $this->module->url() != 'settings' 
                AND !$only_settings 
                AND (
                    !isset($permissions['settings'])
                    OR
                    $this->user->can_access($this->module->url() .'_settings')
                )
        ):?>
        
            <small>
                <?=URL::anchor(
                    'admin/'. $this->module->url() .'/settings',
                    lang('admin:settings'),
                    array(
                        'class'=>'muted'
                    )
                )?>
            </small>
            
        <?php endif?>
        
        <?php if( $has_categories ):?>
            <small>
                <?=URL::anchor(
                    'admin/'. $this->module->url() .'/categories',
                    lang('admin:categories'),
                    array(
                        'class'=>'muted'
                    )
                )?>
            </small>
        <?php endif?>
        <?php if( $has_emails ):?>
            <small>
                <?=URL::anchor(
                    'admin/'. $this->module->url() .'/emails',
                    'Шаблоны писем',
                    array(
                        'class'=>'muted'
                    )
                )?>
            </small>
        <?php endif?>
    </h2>
</div>

<?=$content?>