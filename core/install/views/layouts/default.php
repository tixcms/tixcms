<!DOCTYPE html>
<html lang="en">
<head>
    <?=$this->template->view($this->module .'::partials/metadata')?>
</head>
<body>

<div class="wrap">
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container-fluid">
                <?=$this->template->view($this->module .'::partials/header')?>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid content">
            <div class="span12">
                <?=$content?>
            </div>
        </div>           
    </div>
    
    <footer>
        <?=$this->template->view($this->module .'::partials/footer')?>
    </footer>
</div>

</body>
</html>