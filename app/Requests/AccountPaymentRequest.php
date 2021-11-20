<?php

namespace App\Requests;

/**
 * @property int $account_id
 * @property int $amount
 */
class AccountPaymentRequest extends Request
{
    protected array $validator = ['account_id', 'amount'];
}
