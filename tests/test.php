<?php

include_once '../phrontend.php';
$p = new phrontend();
$p->js->load('test.js');
//echo $p->js->script_html('body_end');
echo $p->render();