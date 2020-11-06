<?php

/**
 * Class Database
 */
class Database
{

    /**
     * @param string $query
     * @param array $array
     * @param bool $false
     * @return array
     */
    public function getRowWithBindings(string $query, array $array, bool $false): array
    {
        return [];
    }

    /**
     * @param $address
     * @return string
     */
    public function sqlizeField($address): string
    {
        return '';
    }

    /**
     * @param string $query
     * @param array $array
     * @param bool $false
     * @return array
     */
    public function getAllWithBindings(string $query, array $array, bool $false): array
    {
        return [];
    }

    /**
     * @param string $string
     * @param array $addressData
     * @param array $array
     * @return array
     */
    public function update(string $string, array $addressData, array $array): array
    {
        return [];
    }

    /**
     * @param string $string
     * @param $address
     * @return array
     */
    public function insert(string $string, $address): array
    {
        return [];
    }

    /**
     * @param string $query
     * @param array $array
     * @return array
     */
    public function getOneWithBindings(string $query, array $array): array
    {
        return [];
    }

    /**
     * @param string $string
     * @param array $array
     * @return bool
     */
    public function delete(string $string, array $array): bool
    {
        return true;
    }
}
