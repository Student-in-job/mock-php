<?php
class UserModel
{
    public $user_id;
    public $nibbd;
    public $fio;
    public $contracts;

    public function __construct($user_id, $nibbd, $fio)
    {
        $this->user_id = $user_id;
        $this->nibbd = $nibbd;
        $this->fio = $fio;
        $this->contracts = array();
    }

    public function addContract($contract_id)
    {
        array_push($this->contracts, $contract_id);
    }

    public function ContractsCount(): int
    {
        return count($this->contracts);
    }

    public function getId(): int
    {
        return $this->user_id;
    }

    public function getContracts(): array
    {
        return $this->contracts;
    }

    public function hasContract($contract_id): bool
    {
        return in_array($contract_id, $this->contracts);
    }
}

class ContractModel
{
    public $contract_id;
    public $accounts;
    public $total_debt;
    public $acc_12401;
    public $acc_12405;
    public $acc_12499;
    public $user_id;
    public $total;
    public function __construct($contract_id, $user_id, $total)
    {
        $this->contract_id = $contract_id;
        $this->user_id = $user_id;
        $this->total = $total;

        $this->total_debt = $total;
        $this->acc_12401 = 0;
        $this->acc_12405 = 0;
        $this->acc_12499 = 0;
    }

    public function setAccounts($accounts)
    {
        foreach($accounts as $key => $value)
        {
            $this->accounts[$key] = $value;
        }
    }

    public function getId(): int
    {
        return $this->contract_id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setNewData(ContractModelRow $record): void
    {
        $this->total_debt = $record->total_debt;
        $this->total = $record->total;
        $this->acc_12401 = $record->acc_12401;
        $this->acc_12405 = $record->acc_12405;
        $this->acc_12499 = $record->acc_12499;
    }
}

class ContractModelRow extends ContractModel
{
    const _length = 10;
    public $date;

    /**
     * @throws Exception
     */
    public function __construct($array_data)
    {
        if (!is_array($array_data))
            throw new Exception('Input data should be array of length 10');
        if (count($array_data) != self::_length)
            throw new Exception('Input data should be array of length 10');
        $this->contract_id = intval($array_data[1]);
        $this->total = Loader::makeFloatFromString($array_data[3]);
        $this->total_debt = Loader::makeFloatFromString($array_data[5]);
        $this->acc_12401 = Loader::makeFloatFromString($array_data[6]);
        $this->acc_12405 = Loader::makeFloatFromString($array_data[7]);
        $this->acc_12499 = Loader::makeFloatFromString($array_data[8]);
        $format = 'd.m.Y H:i:s';
        $this->date = DateTime::createFromFormat($format, $array_data[0] . '00:00:00');
    }
}

class Organization
{
    public $login = 'mko_shaffofmoliya';
    public $password = 'B&ru4D61)flR36!';
    public $type = 'MKO';
    public $nko = '06098';
    public $name = 'ООО МФО «SHAFFOF-MOLIYA»';
    public $bank = '00974';
    public $payment_account = '10509000605570410001';
    public $issue_account = '10503000905570410003';
    public $purposeTypes = [
        '12401-10503' => 'Выдача микрозайма',
        '12405-10509' => 'Отмена платежа',
        '12401-10509' => 'Отмена платежа',
        '12405-12401' => 'Просрочка микрозайма',
        '10509-12401' => 'Погашение микрозайма',
        '10509-12405' => 'Погашение микрозайма',
        '12401-12405' => 'Выход из просрочки',
        '56802-12499' => 'Создание резервов микрозайма',
        '12499-56802' => 'Уменьшение резервов микрозайма'
    ];
}

class JSONReportFormat
{
    public $security;
    public $data;

    public function __construct(Organization $mko, Report $data)
    {
        $this->security['pLogin'] = $mko->login;
        $this->security['pPassword'] = $mko->password;
        $this->data = $data;
    }
}