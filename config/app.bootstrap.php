<?php

app\app::dbconnect();


Phunder\Core\Messager::setOutPutHandler(function ($type, $contenu){
    return '<div class="alert alert-' . $type . '" role="alert">' . $contenu . '</div>';
});










 ?>
