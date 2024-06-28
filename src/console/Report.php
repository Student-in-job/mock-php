<?php
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

    public function __construct(Organization $mko)
    {
        $this->pCode = $mko->nko;
        $this->pHead = $mko->type;
    }
}

class ReportAccountBalance extends Report
{
    public $pLoanStatus = '1';
    public $pRepaymentArray = array();

    public function __construct(Organization $mko)
    {
        parent::__construct($mko);
    }

    private function AddAccount(AccountRecord $accountRecord)
    {
        $this->pRepaymentArray[] = $accountRecord;
    }

    public function GenerateAccounts(ContractModel $contract, ContractModelRow $nextRecord)
    {
        $this->pContractId = strval($contract->getId());
        $this->pDate = str_replace('%', 'T', (new DateTime())->format('Y-m-d%H:i:s')) . ".000Z";;
        $delta_total = $nextRecord->total - $contract->total;
        $delta_acc12401 = $nextRecord->acc_12401 - $contract->acc_12401;
        $delta_acc12405 = $nextRecord->acc_12405 - $contract->acc_12405;
        $delta_acc12499 = $nextRecord->acc_12499 - $contract->acc_12499;
        print ("\r\n");
        print ('delta_total: '. $delta_total . ' - delta_12401: ' . $delta_acc12401 . ' - delta_12405: ' . $delta_acc12405 . ' - delta_12499 :' . $delta_acc12499);
        if (($contract->acc_12401 != 0) || ($delta_acc12401 != 0))
        {
            $account = new AccountRecord();
            $account->account = $contract->accounts["12401"];
            $account->date = str_replace('%', 'T', $nextRecord->date->format('Y-m-d%H:i:s')) . ".000Z";
            $account->startBalance = round($contract->acc_12401 * 100);
            $account->debit = 0;
            if ($delta_acc12401 > 0)
            {
                $account->debit = round($delta_acc12401 * 100);
            }
            $account->credit = 0;
            if ($delta_acc12401 < 0)
            {
                $account->credit = round(-$delta_acc12401 * 100);
            }
            $account->endBalance = round(($contract->acc_12401 + $delta_acc12401) * 100);
            $this->AddAccount($account);
        }
        if (($contract->acc_12405 != 0) || ($delta_acc12405 != 0))
        {
            $account = new AccountRecord();
            $account->account = $contract->accounts["12405"];
            $account->date = str_replace('%', 'T', $nextRecord->date->format('Y-m-d%H:i:s')) . ".000Z";
            $account->startBalance = round($contract->acc_12405 * 100);
            $account->debit = 0;
            if ($delta_acc12405 > 0)
            {
                $account->debit = round($delta_acc12405 * 100);
            }
            $account->credit = 0;
            if ($delta_acc12405 < 0)
            {
                $account->credit = round(-$delta_acc12405 * 100);
            }
            $account->endBalance = round(($contract->acc_12405 + $delta_acc12405) * 100);
            $this->AddAccount($account);
        }
        if (($contract->acc_12499 != 0) || ($delta_acc12499 != 0))
        {
            $account = new AccountRecord();
            $account->account = $contract->accounts["12499"];
            $account->date = str_replace('%', 'T', $nextRecord->date->format('Y-m-d%H:i:s')) . ".000Z";
            $account->startBalance = round($contract->acc_12499 * 100);
            $account->debit = 0;
            if ($delta_acc12499 < 0)
            {
                $account->debit = round(-$delta_acc12499 * 100);
            }
            $account->credit = 0;
            if ($delta_acc12499 > 0)
            {
                $account->credit = round($delta_acc12499 * 100);
            }
            $account->endBalance = round(($contract->acc_12499 + $delta_acc12499) * 100);
            $this->AddAccount($account);
        }
    }
}

class ReportPaymentDocuments extends Report
{
    public $pContractType = '1';
    public $pRepaymentDetArray = array();

    public function __construct(Organization $mko)
    {
        parent::__construct($mko);
    }

    public function AddPaymentRecord(PaymentRecord $paymentRecord)
    {
        $this->pRepaymentDetArray[] = $paymentRecord;
    }
}

