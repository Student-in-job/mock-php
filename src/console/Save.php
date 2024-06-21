<?php
require_once "DTOModels.php";

$accounts = array(
    '12401' => '12401000199132657015',
    '12405' => '12405000199132657015',
    '12499' => '12499000199132657015'
);
$contract_object = new ContractModel(3794010, 1861710, 247500.00);
$contract_object->setAccounts($accounts);
$user_object = new UserModel(1861710, '99132657', 'ASQAROVA RUXSORA ABROR QIZI');
$user_object->addContract($contract_object->getId());
$user_object->addContract(898455);
$user_object->addContract(1533965);
$user_object->addContract(1565375);
$user_object->addContract(1736174);
$user_object->addContract(1985014);
$user_object->addContract(2076666);
$user_object->addContract(2185974);
$user_object->addContract(2708393);
$user_object->addContract(2710146);
$user_object->addContract(3009446);
$user_object->addContract(3021885);
$user_object->addContract(3022137);
$user_object->addContract(3057268);
$user_object->addContract(3058403);
$user_object->addContract(3796933);
$json_user_object = json_encode($user_object);
$json_contract_object = json_encode($contract_object);
$myfile = fopen("models.json", "w");
fwrite($myfile, $json_user_object);
fwrite($myfile, PHP_EOL);
fwrite($myfile, $json_contract_object);
fclose($myfile);