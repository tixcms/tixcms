<form <?=$form->render('attrs')?>>
    <?=$form->legend ? '<legend>'. $form->legend .'</legend>' : ''?>

    <?=$form->render('inputs', array(
        'subject',
        'from'
    ))?>
    
    <?=$form->render('input', 'text')?>
</form>

<style>
    form label {text-align: left !important;}
</style>