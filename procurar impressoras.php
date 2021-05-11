<?php 
function getPrinterProperty($key){ //a key é o valor retornado ao solicitar a propriedade
    $str = shell_exec('wmic printer get '.$key.' /value'); //executa no cmd o comando wmic que deseja

    $keyname = "$key=";
    $validValues = [];
    $fragments = explode(PHP_EOL,$str);
    foreach($fragments as $fragment){
        if($fragment == ""){
            continue;
        }
        if (preg_match('/('.$keyname.')/i', $fragment)) {
            array_push($validValues,str_replace($keyname,"",$fragment));
        }
    }
    return $validValues;
}

//o comando wmic pode aceitar varias outras entradas referentes a printer, como exemplo:
// wmic /node:SERVER1 printer list status --status das impressoras de um servidor remoto, no lugar do server vc pode colocar o ip do computador por exemplo
// wmic printer list status -- status das impressoras da maquina local
// wmic printer get -- todas as propriedades de todas as impressoras instaladas na maquina
// wmic printer get <propriedade> /value -- propriedade no formato chave=valor do servidor remoto
// wmic printer get <propriedade> /value -- propriedade no formato chave=valor do servidor local

//Solicitar propriedades
//é daqui que sai o $key ao chamar a função getPrinterProperty()
//aqui tem algumas porem vc pode pegar varias outras
$Name = getPrinterProperty("Name");
$Description =  getPrinterProperty("Description");
$Network = getPrinterProperty("Network");
$Local = getPrinterProperty("Local");
$PortName = getPrinterProperty("PortName");


//Agrupa os valores retornados
$Printers = [];
foreach($Name as $i => $n){
    $Printers[$i] = (object)[
        "name" => $n,
        "description" => $Description[$i],
        "Portname" => $PortName[$i],
        "isNetwork" => ($Network[$i] == "TRUE")? true : false,
        "isLocal" =>($Local[$i] == "TRUE")? true : false,
    ];
}

var_dump($Printers); // retorna uma string contendo os valores