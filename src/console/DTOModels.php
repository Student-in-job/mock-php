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
}
