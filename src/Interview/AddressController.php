<?php

class AddressController
{
    public function show($id)
    {
        $addressId = 1;

        $address = new Address();
        $address->getById($addressId);

        if ($address->isEmpty())
        {
            echo 'address ID: '.$addressId.' not found';
        }

        $name = $address->getData('name');

        echo $name;

        // show some html code
    }

    public function delete($id)
    {
        $addressId = 1;
        $customerId = 11111;

        $address = new Address();
        $address->getById($addressId);
        // Customer wants to delete address from address book
        $address->delete($addressId, $customerId);

        // show some html code
    }

    public function update($request = ['name' => 'custname1', 'phone' => '112312'])
    {
        $addressId = 1;

        $address = new Address();
        $address->getById($addressId);

        // We want to update address from request
        $address->hydrate($request);
        $address->save();

        // show some html code
    }

    public function create($request = ['name' => 'custname1', 'phone' => '112312'])
    {
        // Customer wants to create new address
        $newAddress = new Address();
        $newAddress->hydrate($request);
        $newAddress->save();

        // show some html code
    }
}