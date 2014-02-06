<ul class="breadcrumb">
    <li>
        <?php if( $this->uri->segment(3) == 'step1' ):?>
            <a>Проверка прав на файлы и папки</a> 
        <?php else:?>
            Проверка прав на файлы и папки
        <?php endif?>
        <span class="divider">»</span></li>
    <li>
        <?php if( $this->uri->segment(3) == 'step2' ):?>
            <a>Настройка базы данных</a> 
        <?php else:?>
            Настройка базы данных
        <?php endif?>
        <span class="divider">»</span>
    </li>
    <li>
        <?php if( $this->uri->segment(3) == 'step3' ):?>
            <a>Добавление администратора</a> 
        <?php else:?>
            Добавление администратора
        <?php endif?>
        <span class="divider">»</span></li>
    <li>
        <?php if( $this->uri->segment(3) == 'final' ):?>
            <a>Завершение установки</a> 
        <?php else:?>
            Завершение установки
        <?php endif?>
    </li>
</ul>

<?=$this->di->alert->render()?>

<?=$content?>