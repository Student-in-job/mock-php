<?php
const mask12401 = '12401';
const mask12405 = '12405';
const mask12499 = '12499';
const mask10509 = '10509';
const mask10503 = '10503';
const mask56802 = '56802';
interface KATMReports
{
    public function GenerateReport(ContractModel $contract, ContractModelRow $nextRecord);
}

class AccountRecord
{
    public $account;
    public $date;
    public $startBalance;
    public $debit;
    public $credit;
    public $endBalance;
}

class PaymentRecord
{
    public $accountA;
    public $accountB;
    public $branchA;
    public $branchB;
    public $coaA;
    public $coaB;
    public $currency = '000';
    public $destination;
    public $docDate;
    public $docNum;
    public $docType = '06';
    public $nameA;
    public $nameB;
    public $payType = '0';
    public $paymentId = '0000000000123456789';
    public $purpose;
    public $summa;
}

class Report
{
    public $pHead;
    public $pCode;
    public $pContractId;
    public $pDate;
    protected $_mfo;
    protected UserModel $_user;

    public function __construct(Organization $mko, $contract_id)
    {
        $this->pCode = $mko->nko;
        $this->pHead = $mko->type;
        $this->pDate = str_replace('%', 'T', (new DateTime())->format('Y-m-d%H:i:s')) . ".000Z";
        $this->pContractId = strval($contract_id);
        $this->_mfo = $mko;
    }
    public function setUser(UserModel $user)
    {
        $this->_user = $user;
    }

    protected function printState($delta_total, $delta_total_debt, $delta_acc12401, $delta_acc12405, $delta_acc12499)
    {
        print ("\r\n");
        print ('delta_total: '. $delta_total . ' - delta_total_debt: ' . $delta_total_debt . ' - delta_12401: ' . $delta_acc12401 . ' - delta_12405: ' . $delta_acc12405 . ' - delta_12499 :' . $delta_acc12499);
    }
}

class ReportAccountBalance extends Report implements KATMReports
{
    public $pLoanStatus = '1';
    public $pRepaymentArray = array();

    private function AddAccount(AccountRecord $accountRecord)
    {
        $this->pRepaymentArray[] = $accountRecord;
    }

    public function GenerateReport(ContractModel $contract, ContractModelRow $nextRecord)
    {
        $delta_acc12401 = $nextRecord->acc_12401 - $contract->acc_12401;
        $delta_acc12405 = $nextRecord->acc_12405 - $contract->acc_12405;
        $delta_acc12499 = $nextRecord->acc_12499 - $contract->acc_12499;
        if(($contract->acc_12401 != 0) || ($delta_acc12401 != 0))
        {
            $account = new AccountRecord();
            $account->account = $contract->accounts["12401"];
            $account->date = str_replace('%', 'T', $nextRecord->date->format('Y-m-d%H:i:s')) . ".000Z";
            $account->startBalance = round($contract->acc_12401 * 100);
            $account->debit = 0;
            if($delta_acc12401 > 0)
            {
                $account->debit = round($delta_acc12401 * 100);
            }
            $account->credit = 0;
            if($delta_acc12401 < 0)
            {
                $account->credit = round(-$delta_acc12401 * 100);
            }
            $account->endBalance = round(($contract->acc_12401 + $delta_acc12401) * 100);
            $this->AddAccount($account);
        }
        if(($contract->acc_12405 != 0) || ($delta_acc12405 != 0))
        {
            $account = new AccountRecord();
            $account->account = $contract->accounts["12405"];
            $account->date = str_replace('%', 'T', $nextRecord->date->format('Y-m-d%H:i:s')) . ".000Z";
            $account->startBalance = round($contract->acc_12405 * 100);
            $account->debit = 0;
            if($delta_acc12405 > 0)
            {
                $account->debit = round($delta_acc12405 * 100);
            }
            $account->credit = 0;
            if($delta_acc12405 < 0)
            {
                $account->credit = round(-$delta_acc12405 * 100);
            }
            $account->endBalance = round(($contract->acc_12405 + $delta_acc12405) * 100);
            $this->AddAccount($account);
        }
        if(($contract->acc_12499 != 0) || ($delta_acc12499 != 0))
        {
            $account = new AccountRecord();
            $account->account = $contract->accounts["12499"];
            $account->date = str_replace('%', 'T', $nextRecord->date->format('Y-m-d%H:i:s')) . ".000Z";
            $account->startBalance = round($contract->acc_12499 * 100);
            $account->debit = 0;
            if($delta_acc12499 < 0)
            {
                $account->debit = round(-$delta_acc12499 * 100);
            }
            $account->credit = 0;
            if($delta_acc12499 > 0)
            {
                $account->credit = round($delta_acc12499 * 100);
            }
            $account->endBalance = round(($contract->acc_12499 + $delta_acc12499) * 100);
            $this->AddAccount($account);
        }
    }
}

class ReportPaymentDocuments extends Report implements KATMReports
{
    public $pContractType = '1';
    public $pRepaymentDetArray = array();
    public $printOut = false;
    private function AddPaymentRecord(PaymentRecord $paymentRecord)
    {
        $this->pRepaymentDetArray[] = $paymentRecord;
    }

    private function preparePaymentRecord($date): PaymentRecord
    {
        $record = new PaymentRecord();
        $record->branchA = $this->_mfo->bank;
        $record->branchB = $this->_mfo->bank;
        $record->docDate = str_replace('%', 'T', $date->format('Y-m-d%H:i:s')) . ".000Z";
        return $record;
    }

    public function GenerateReport(ContractModel $contract, ContractModelRow $nextRecord)
    {
        $this->pContractId = strval($contract->getId());
        $this->pDate = str_replace('%', 'T', (new DateTime())->format('Y-m-d%H:i:s')) . ".000Z";
        $delta_total = $nextRecord->total - $contract->total;
        $delta_total_debt = $nextRecord->total_debt - $contract->total_debt;
        $delta_acc12401 = $nextRecord->acc_12401 - $contract->acc_12401;
        $delta_acc12405 = $nextRecord->acc_12405 - $contract->acc_12405;
        $delta_acc12499 = $nextRecord->acc_12499 - $contract->acc_12499;
        if ($this->printOut)
        {
            $this->printState($delta_total, $delta_total_debt, $delta_acc12401, $delta_acc12405, $delta_acc12499);
        }
        if($delta_total > 0)
        {
            $record = $this->preparePaymentRecord($nextRecord->date);
            $record->accountA = $contract->accounts[mask12401];
            $record->accountB = $this->_mfo->issue_account;
            $record->coaA = mask12401;
            $record->coaB = mask10503;
            $record->destination = '1007';
            $record->nameA = $this->_mfo->name;
            $record->nameB = $this->_user->fio;
            $record->purpose = $this->_mfo->purposeTypes[ sprintf('%s-%s', $record->coaA, $record->coaB)];
            $record->summa = round($delta_total * 100);
            $this->AddPaymentRecord($record);
        }
        if($delta_acc12401 != 0)
        {
            if(($delta_acc12401 < 0) and ($delta_total_debt != 0))
            {
                if($delta_total_debt > 0)
                {
                    $record = $this->preparePaymentRecord($nextRecord->date);
                    $record->accountA = $contract->accounts[mask12401];
                    $record->accountB = $this->_mfo->payment_account;
                    $record->coaA = mask12401;
                    $record->coaB = mask10509;
                    $record->destination = '1007';
                    $record->nameA = $this->_mfo->name;
                    $record->nameB = $this->_user->fio;
                    $record->purpose = $this->_mfo->purposeTypes[ sprintf('%s-%s', $record->coaA, $record->coaB)];
                    $record->summa = round( $delta_acc12401 * 100);
                    $this->AddPaymentRecord($record);
                    unset($record);
                }
                else
                {
                    $record = $this->preparePaymentRecord($nextRecord->date);
                    $record->accountA = $this->_mfo->payment_account;
                    $record->accountB = $contract->accounts[mask12401];
                    $record->coaA = mask10509;
                    $record->coaB = mask12401;
                    $record->destination = '1008';
                    $record->nameA = $this->_user->fio;
                    $record->nameB = $this->_mfo->name;
                    $record->purpose = $this->_mfo->purposeTypes[ sprintf('%s-%s', $record->coaA, $record->coaB)];
                    $record->summa = round(-($delta_acc12405 + $delta_acc12401) * 100);
                    $this->AddPaymentRecord($record);
                    unset($record);
                }
            }
            else
            {
                if(($delta_total_debt != 0)&&($delta_total == 0))
                {
                    $record = $this->preparePaymentRecord($nextRecord->date);
                    $record->accountA = $this->_mfo->payment_account;
                    $record->accountB = $contract->accounts[mask12401];
                    $record->coaA = mask10509;
                    $record->coaB = mask12401;
                    $record->destination = '1008';
                    $record->nameA = $this->_user->fio;
                    $record->nameB = $this->_mfo->name;
                    $record->purpose = $this->_mfo->purposeTypes[ sprintf('%s-%s', $record->coaA, $record->coaB)];
                    $record->summa = round(($delta_total - $delta_total_debt) * 100);
                    $this->AddPaymentRecord($record);
                    unset($record);
                }
            }
        }
        if($delta_acc12405 != 0)
        {
            if($delta_acc12405 > 0)
            {
                if($delta_total_debt > 0)
                {
                    $record = $this->preparePaymentRecord($nextRecord->date);
                    $record->accountA = $contract->accounts[mask12405];
                    $record->accountB = $this->_mfo->payment_account;
                    $record->coaA = mask12405;
                    $record->coaB = mask10509;
                    $record->destination = '1008';
                    $record->nameA = $this->_user->fio;
                    $record->nameB = $this->_mfo->name;
                    $record->purpose = $this->_mfo->purposeTypes[ sprintf('%s-%s', $record->coaA, $record->coaB)];
                    $record->summa = round($delta_acc12405 * 100);
                    $this->AddPaymentRecord($record);
                    unset($record);
                }
                else
                {
                    $record = $this->preparePaymentRecord($nextRecord->date);
                    $record->accountA = $contract->accounts[mask12405];
                    $record->accountB = $contract->accounts[mask12401];
                    $record->coaA = mask12405;
                    $record->coaB = mask12401;
                    $record->destination = '1009';
                    $record->nameA = $this->_mfo->name;
                    $record->nameB = $this->_mfo->name;
                    $record->purpose = $this->_mfo->purposeTypes[ sprintf('%s-%s', $record->coaA, $record->coaB)];
                    $record->summa = round($delta_acc12405 * 100);
                    $this->AddPaymentRecord($record);
                    unset($record);
                }
            }
            else
            {
                if($delta_total_debt == 0)
                {
                    $record = $this->preparePaymentRecord($nextRecord->date);
                    $record->accountA = $contract->accounts[mask12401];
                    $record->accountB = $contract->accounts[mask12405];
                    $record->coaA = mask12401;
                    $record->coaB = mask12405;
                    $record->destination = '1009';
                    $record->nameA = $this->_mfo->name;
                    $record->nameB = $this->_mfo->name;
                    $record->purpose = $this->_mfo->purposeTypes[ sprintf('%s-%s', $record->coaA, $record->coaB)];
                    $record->summa = round($delta_acc12405 * 100);
                    $this->AddPaymentRecord($record);
                    unset($record);
                }
                if($delta_total_debt > 0)
                {
                    $record = $this->preparePaymentRecord($nextRecord->date);
                    $record->accountA = $contract->accounts[mask12405];
                    $record->accountB = $this->_mfo->payment_account;
                    $record->coaA = mask12405;
                    $record->coaB = mask10509;
                    $record->destination = '1007';
                    $record->nameA = $this->_user->fio;
                    $record->nameB = $this->_mfo->name;
                    $record->purpose = $this->_mfo->purposeTypes[ sprintf('%s-%s', $record->coaA, $record->coaB)];
                    $record->summa = round($delta_acc12405 * 100);
                    $this->AddPaymentRecord($record);
                    unset($record);
                }
                else
                {
                    $record = $this->preparePaymentRecord($nextRecord->date);
                    $record->accountA = $this->_mfo->payment_account;
                    $record->accountB = $contract->accounts[mask12405];
                    $record->coaA = mask10509;
                    $record->coaB = mask12405;
                    $record->destination = '1008';
                    $record->nameA = $this->_user->fio;
                    $record->nameB = $this->_mfo->name;
                    $record->purpose = $this->_mfo->purposeTypes[ sprintf('%s-%s', $record->coaA, $record->coaB)];
                    $record->summa = round(-$delta_acc12405 * 100);
                    $this->AddPaymentRecord($record);
                    unset($record);
                }
            }
        }
        if ($delta_acc12499 != 0)
        {
            if ($delta_acc12499 > 0)
            {
                $record = $this->preparePaymentRecord($nextRecord->date);
                $record->accountA = $this->_mfo->reserve_account;
                $record->accountB = $contract->accounts[mask12499];
                $record->coaA = mask56802;
                $record->coaB = mask12499;
                $record->destination = '1012';
                $record->nameA = $this->_mfo->name;
                $record->nameB = $this->_user->fio;
                $record->purpose = $this->_mfo->purposeTypes[ sprintf('%s-%s', $record->coaA, $record->coaB)];
                $record->summa = round($delta_acc12499 * 100);
                $this->AddPaymentRecord($record);
                unset($record);
            }
            else
            {
                $record = $this->preparePaymentRecord($nextRecord->date);
                $record->accountA = $contract->accounts[mask12499];
                $record->accountB = $this->_mfo->reserve_account;
                $record->coaA = mask12499;
                $record->coaB = mask56802;
                $record->destination = '1008';
                $record->nameA = $this->_user->fio;
                $record->nameB = $this->_mfo->name;
                $record->purpose = $this->_mfo->purposeTypes[ sprintf('%s-%s', $record->coaA, $record->coaB)];
                $record->summa = round(-$delta_acc12499 * 100);
                $this->AddPaymentRecord($record);
                unset($record);
            }
        }
    }
}

