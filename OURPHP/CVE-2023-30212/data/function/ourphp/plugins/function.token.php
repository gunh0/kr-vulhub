<?php

function smarty_function_token($params, &$smarty)
{

    global $db,$ourphp;
    extract($params);
    $info = isset($params['info']) ? $params['info'] : "ourphp";

    $md = MD5($info.$ourphp['safecode']);
    return $md;
    
}

?>
