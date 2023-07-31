<?php

namespace Wladimir\ParserExcel\Repository;

interface VariableRepositoryInterface
{
    /**
    * Получение постоянного идентификатора переменной.
    * Используется когда мы формулу перегоняем в синтаксическое дерево
    * Сделано на тот случай, когда название переменной может поменяться. (как пример переключение языка)
    * Пример :
    *   Переменная "Пользователь" в бд храниться как  user, значит необходимо вернуть user
     * @param string $variableName
     * @return string
     */
    public function getIdentifierByName(string $variableName): string;


    /**
 * Возвращает название переменной по идентификатору.
 * Используется когда мы синтаксическое дерево конвертирем в формулу.
     * @param string $identificator
     * @return string
     */
    public function getNameByIdentifier(string $identificator): string;
}
