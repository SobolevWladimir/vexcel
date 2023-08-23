<?php

namespace SobolevWladimir\Vexcel\Repository;

interface ValueRepositoryInterface
{
    /**
     * Возвращает значение по идентификатору переменной;.
     *
     * @param string $identificator
     *
     * @return mixed
     */
    public function getValueByIdentifier(string $identificator): mixed;
}
