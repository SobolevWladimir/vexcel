<h1 align="center">
  <img src="./logo.png" alt="vexcel" width="300px">
</h1>
 Позволяет  использовать формулы excel в вашем приложении. 
 
## Описание:  
Формулы в эксель - это прекрасное изобретение которое позволяет пользователям производить расчеты без помощи программистов. Нередко возникают ситуации когда такой функционал нужен в вашем приложении! Эта библиотека позволит вам использовать выражения синтаксически похожие на формулы из excel, с той лишь разницей, что в место координат ячеек вы сможете использовать свои переменные. 

 ## Особенности: 
  * Excel like  синтаксис. 
  * Поддержка переменных задаваемых извне. 
  * Математический порядок действий (Пример: 2+2\*3 получим 8) 
  * Поддерживает функции  (ЕСЛИ, НЕ, ОКРУГЛИТЬ и т.д)
  * Возможность добавлять свои функции
  * Преобразование синтаксического дерева в  json  и обратно.
  * Хранение переменных в синтаксическом дереве в виде идентификаторов. 
  * Возможность преобразовать синтаксическое дерево обратно в формулу. (Необходимо если возможно изменение названий переменных со временем) 

## Установка 

## Начало работы    

Подключение: 
```php
<?php
...
use Wladimir\ParserExcel\Parser\Parser;
```
Далее нам можно преобразовывать нашу формулу в абстрактное синтаксическое дерево и при необходимости произвести расчет. 

 ### Простое использование: 
```php
$parser = new Parser();
$ast = $parser->parse('3+3');// Получили синтаксическое дерево
$answer = $ast->calculate(); // $answer = 6
````

### Использование переменных в формулах: 
Для использования переменных, нам необходимо объяснить системе от куда брать значения переменных. Для этого создайте класс реализующий интерфейс ValueRepository.
Например, давайте представим что у нас есть переменные, названия которых соответсвуют числам ("ОДИН"=1, "ДВА"=2, "ТРИ"=3 и тд). Тогда класс будет иметь следующий вид: 
```php
use Wladimir\ParserExcel\Repository\ValueRepositoryInterface;

class ValueRepositoryFake implements ValueRepositoryInterface
{
    /** @var array<string, int> */
    private array $variables = [
        'ОДИН'   => 1,
        'ДВА'    => 2,
        'ТРИ'    => 3,
        'ЧЕТЫРЕ' => 4,
        'ПЯТЬ'   => 5,
        'ШЕСТЬ'  => 6,
    ];

    public function getValueByIdentifier(string $identificator): mixed
    {
        return $this->variables[$identificator];
    }
}
```
Далее мы передаем экземпляр этого класса для подсчета: 
```php
$parser = new Parser();
$ast = $parser->parse('ТРИ+3');// Получили синтаксическое дерево

$repository = new ValueRepositoryFake();// Наш репозиторий

$answer = $ast->calculate($repository); // $answer = 6
```
### Использование функций:  

```php
$parser = new Parser();
$ast = $parser->parse('ЕСЛИ(НЕ(3<2);"ДА";"НЕТ")');// Получили синтаксическое дерево
$answer = $ast->calculate(); // $answer = 'ДА'
```
На данные момент реализованы следующие функции по умолчанию 'ЕСЛИ', 'НЕ', 'ОКРУГЛВЕРХ', 'ОКРУГЛНИЗ'. Если вам необходимо реализовать свои функции, то создайте класс унаследованный от FunctionBuilder и переопределите метод  "build"; 
```php
$parser = new Parser(functionBuilder: new YouFunctionBuilder());
$ast = $parser->parse('МОЯФУНЦИЯ(3<2)');// Получили синтаксическое дерево
$answer = $ast->calculate(); 
```

## Хранение формул в БД. 
Если в вашей формуле есть переменные, название которых берется из базы данных то, имеет смысл хранить идентификаторы на эти переменные. 
Для этого: 
1) Реализуйте класс реализующий интерфейс VariableRepositoryInterface  и при  передайте его в конструктор Parser().
2) Преобразуйте формулу в синтаксическое дерево путем вызова $parser->parse();
3) Это дерево сохраните в виде json в бд (json_encode($ast)).

Что бы преобразовать json обратно в синтаксическое дерево, выполните следующею процедуру: 
```php
$ast = FormulaAST::fromJson(json_decode((string)$formulaJson, true)); 
```
Далее можно преобразовать это дерево обратно в формулу: 
 ```php
$encoder = new VexcelEncoder($youVariableRepository); 
$code = $ast->toCode($encoder); 
```
