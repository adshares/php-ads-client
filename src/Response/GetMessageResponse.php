<?php

namespace Adshares\Ads\Response;

use Adshares\Ads\Entity\EntityFactory;

class GetMessageResponse extends AbstractResponse
{
    /**
     * @var array[AbstractTransaction]
     */
    protected $transactions = [];

    /**
     * @param array $data
     */
    protected function loadData(array $data): void
    {
        parent::loadData($data);

        if (array_key_exists('transactions', $data)) {
            foreach ($data['transactions'] as $value) {
                $this->transactions[] = EntityFactory::createTransaction($value);
            }
        }
    }

    /**
     * @return array
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }
}