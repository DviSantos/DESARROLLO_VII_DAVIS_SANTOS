<?php
function contar_palabras($texto){
    $contador=0;
        do{
        $contador=count($texto);
        }while($texto!='');
        return $contador;
}

function contar_vocales($texto){
    $contador=0;
    do{
        if($texto=='a'||'A'){
            $contador+=1;
        }elseif($texto=='e'||'E'){
            $contador+=1;
        }elseif($texto=='i'||'I'){
            $contador+=1;
        }elseif($texto=='o'||'O'){
            $contador+=1;
        }elseif($texto=='u'||'U'){
            $contador+=1;
        }
    }while($texto!='');
    return $contador;
}

function invertir_palabra($texto){

}
//contar_vocales("hola mundo");
contar_palabras("hola mundo");
?>