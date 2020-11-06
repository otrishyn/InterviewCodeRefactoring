<?php

class Address
{
    protected $data = [];
    protected $prefix = '';
    protected $addressRules = [];

    public function __construct()
    {
        $this->database = new Database();
        $this->fillAddressRules();
    }

    public function getById($id)
    {
        $query = "
            SELECT
                *
            FROM
                address_book
            WHERE
                id = :id
        ";
        $this->data = $this->database->getRowWithBindings($query, [':id' => $id], false);

        return $this;
    }

    public function getByIdAndcustomerId($id, $customerId)
    {
        $query = "
            SELECT
                *
            FROM
                address_book
            WHERE
                id = :id
                AND
                customer_id = :customerId
        ";
        $this->data = $this->database->getRowWithBindings($query, [':id' => $id, ':customerId' => $customerId], false);

        return $this;
    }

    public function getDefaultBycustomerId($customerId, $address)
    {
        $query = "
            SELECT
                *
            FROM
                address_book
            WHERE
                customer_id = :customerId
            AND
                " . $this->database->sqlizeField($address) . " = TRUE
        ";
        $data = $this->database->getRowWithBindings($query, [':customerId' => $customerId], false);
        $this->setData($data);

        return $this;
    }

    public function getBycustomerId($customerId)
    {
        $query = "
            SELECT
                *
            FROM
                address_book
            WHERE
                customer_id = :customerId
            ORDER BY
                description
        ";
        $addresses = $this->database->getAllWithBindings($query, [':customerId' => $customerId], false);
        $temp = [];
        foreach ($addresses as $key => $address) {
            $temp[$key] = new Address();
            $temp[$key]->setData($address);
        }

        return $temp;
    }

    public function getData($key = '', $prefix = '')
    {
        if ($key) {
            if (isset($this->data[$key])) {
                return $this->data[$key];
            }

            return null;
        }

        if ($prefix) {
            $tmpAddressData = [];
            foreach ($this->data as $key => $value) {
                $tmpAddressData[$prefix . $key] = $value;
            }

            return $tmpAddressData;
        }

        return $this->data;
    }

    public function isEmpty()
    {
        return empty($this->getData());
    }

    public function setData($key, $value = '')
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);

            return;
        }
        if (! empty($key) || $key === 0) {
            $this->data[$key] = $value;
        }
    }

    public function hydrate(array $address)
    {
        foreach ($address as $key => $value) {
            if (! isset($this->addressRules[$key])) {
                continue;
            }
            if ($key === 'birthdate' && ($value === '0000-00-00' || $value === '')) {
                continue;
            }
            if ($key == 'zip') {
                $value = str_replace(' ', '', strtoupper($value));
            }
            $this->data[$key] = $value;
        }
    }

    public function save()
    {
        $valid = $this->validate();
        if (empty($valid)) {
            if ($this->getData('id')) {
                $addressData = $this->getData();

                $birthdate = $addressData['birthdate'] ?? '';
                if ($birthdate === '0000-00-00' || ! $birthdate) {
                    unset($addressData['birthdate']);
                }

                $this->database->update('address_book', $addressData, ['id' => $this->getData('id')]);
            } else {
                return $this->createAddress($this->getData());
            }
        }

        return $valid;
    }

    public function delete($id, $customerId)
    {
        $query = "
            SELECT
                COUNT(id)
            FROM
                address_book
            WHERE
                id = :id
            AND
                default_billing = 0
            AND
                default_shipping = 0
        ";

        $default = (int) $this->database->getOneWithBindings($query, [':id' => $id]);

        if ($default === 0) {
            throw new Exception('This address is a default address');
        }

        $query = "
            SELECT
                COUNT(id)
            FROM
                address_book
            WHERE
                customer_id = :customerId
        ";

        $totalAddresses = (int) $this->database->getOneWithBindings($query, [':customerId' => $customerId]);
        if ($totalAddresses <= 1) {
            throw new Exception('Not enough addresses to delete this');
        }

        return $this->database->delete('address_book', ['id' => $id, 'customer_id' => $customerId]);
    }

    protected function createAddress($address)
    {
        if (! isset($address['description'])) {
            $address['description'] = $address['name'];
        }

        return $this->database->insert('address_book', $address);
    }

    protected function fillAddressRules()
    {
        $this->addressRules = [
            "sex" => [
                "type" => 0,
                "name" => "sex",
            ],
            "name" => [
                "type" => 0,
                "require" => "1"
            ],
            "company" => [
                "type" => 0
            ],
            "country" => [
                "type" => 0,
                "name" => "country",
                "require" => "1"
            ],
        ];
    }

    private function validate(): bool
    {
        // ....
        return true;
    }
}
