<?php
date_default_timezone_set('America/Sao_Paulo');

$hora = (int) date('H');

if ($hora >=5 && $hora <=12){
    echo'
        <h1 class="saudacao">
        Bom dia!
        </h1>
        '
;}
elseif ($hora >= 13 && $hora <=18){
    echo'
        <h1 class="saudacao">
        Boa tarde!
        </h1>
        '    
;}
else{
    echo'
        <h1 class="saudacao">
        Boa noite!
        </h1>
        '        
;}
?>