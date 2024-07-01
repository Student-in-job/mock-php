<?php
require_once "DTOModels.php";
require_once "Loader.php";
require_once "Report.php";
require_once "../Storage.php";

$accounts = array(
    '12401' => '12401000199132657015',
    '12405' => '12405000199132657015',
    '12499' => '12499000199132657015'
);
//$contract_object = new ContractModel(3794010, 1861710, 247500.00);
//$contract_object->setAccounts($accounts);
$user_object = new UserModel(1861710, '99132657', 'ASQAROVA RUXSORA ABROR QIZI');
//$user_object->addContract($contract_object->getId());
//$user_object->addContract(898455);
//$user_object->addContract(1533965);
//$user_object->addContract(1565375);
//$user_object->addContract(1736174);
//$user_object->addContract(1985014);
//$user_object->addContract(2076666);
//$user_object->addContract(2185974);
//$user_object->addContract(2708393);
//$user_object->addContract(2710146);
//$user_object->addContract(3009446);
//$user_object->addContract(3021885);
//$user_object->addContract(3022137);
//$user_object->addContract(3057268);
//$user_object->addContract(3058403);
//$user_object->addContract(3796933);
//$json_user_object = json_encode($user_object);
//$json_contract_object = json_encode($contract_object);
//$myfile = fopen("models.json", "w");
//fwrite($myfile, $json_user_object);
//fwrite($myfile, PHP_EOL);
//fwrite($myfile, $json_contract_object);
//fclose($myfile);
$folder = dirname(__DIR__, 2);
$output_folder = $folder . '\\output\\';
$file_name = $folder. '\\tmp\\contract_3794010_2.csv';
print(sprintf("Loading records from file: \"%s\"", $file_name));
$data_loader = new Loader($file_name, 1);
$lines = $data_loader->getData();
$records = array();
foreach ($lines as $line)
{
    try {
        $records[] = new ContractModelRow($line);
    }
    catch (Exception $exp)
    {
        print($exp->getMessage());
        break;
    }
}

$out_file = $output_folder . sprintf("contract_015_%d.txt", 3794010);
$out_file2 = $output_folder . sprintf("contract_016_%d.txt", 3794010);
$out_file2_total = $output_folder . sprintf("contract_%d.txt", 3794010);
$file_storage = new FileStorage($out_file);
$file_storage2 = new FileStorage($out_file2);
$file_storage_total = new FileStorage($out_file2_total);

$shafof = new Organization();
$contract = null;
foreach ($records as $report_day)
{
    // Creates a new contract if not exist
    if (!$user_object->hasContract($report_day->contract_id))
    {
        $contract = new ContractModel($report_day->contract_id, $user_object->getId(), 0);
        $contract->setAccounts($accounts);
        $user_object->addContract($contract->getId());
    }

    // Creates 015 report
    $report_data = new ReportAccountBalance($shafof, $contract->getId());
    $report_data->GenerateReport($contract, $report_day);
    $report = ['security' => ['pLogin' => $shafof->login, 'pPassword' => $shafof->password], 'data' => $report_data];

    // Creates 016 report
    $report_data2 = new ReportPaymentDocuments($shafof, $contract->getId());
    $report_data2->setUser($user_object);
    $report_data2->printOut = true;
    $report_data2->GenerateReport($contract, $report_day);
    $report2 = ['security' => ['pLogin' => $shafof->login, 'pPassword' => $shafof->password], 'data' => $report_data2];

    // Creates single file for two reports report
    $report_total = [$report_day->date->format('d-m-Y') => ['015' => $report, '016' => $report2]];

    if (count($report_data2->pRepaymentDetArray) > 0)
    {
        $file_storage2->addLine(json_encode($report2), true);
        $file_storage->addLine(json_encode($report), true);
        $file_storage_total->addLine(json_encode($report_total), true);
    }

    unset($report);
    unset($report2);
    $contract->setNewData($report_day);
//    break;
}
$file_storage->flush();
$file_storage2->flush();
$file_storage_total->flush();