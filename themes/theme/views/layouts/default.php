<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?=$this->template->view('::partials/metadata')?>
</head>
<body>
    <div class="wrap">
        <div class="container wrapper">
            <div class="row-fluid well header">
                <?=$this->template->view('::partials/header')?>
            </div>
            
            <div class="navbar">
                <?=$this->template->view('::partials/navbar')?>
            </div>
            
            <div class="container content-wrap">
                <div class="row-fluid">
                    <?=$content?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <?=$this->template->view('::partials/footer')?>
    </div>
    
    <?=Block::area('footer', false, false)?>
</body>
</html>