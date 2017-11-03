<?php

namespace LE\KonnektiveCrmApi\ApiCall\OrderFunctions;


use LE\KonnektiveCrmApi\ApiCall\AbstractApiCall;

class RefundOrder extends AbstractApiCall
{
    const API_URI = '/order/refund';
    const DTO_CLASS_FQN = '';
    const CALL_NAME = 'OrderFunctions\RefundOrder';
    const REQUEST_METHOD = "POST";
}