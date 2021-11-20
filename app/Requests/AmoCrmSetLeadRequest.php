<?php

namespace App\Requests;

/**
 * @property  int $lead_id
 */
class AmoCrmSetLeadRequest extends Request
{
    protected array $validator = ['lead_id'];
}
