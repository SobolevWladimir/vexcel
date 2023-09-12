<h1 align="center">
  <img src="./logo.png" alt="vexcel" width="300px">
</h1>
 Позволяет  использовать формулы excel в вашем приложении. 
 
## Описание:  
Формулы в эксель - это прекрасное изобретение,которое позволяет пользователям производить рассчеты без (помощи) программистов. Нередко возникают ситуации, когда такой функционал нужен в вашем приложении! Эта библиотека позволит вам использовать выражения, синтаксически похожие на формулы из excel, с той лишь разницей, что вместо координат ячеек вы сможете использовать свои переменные. 

 ## Особенности: 
  * Excel подобный  синтаксис. 
  * Поддержка переменных, задаваемых извне. 
  * Математический порядок действий (Пример: 2+2\*3 получим 8).
  * Поддерживает функции  (ЕСЛИ, НЕ, ОКРУГЛИТЬ и т.д.).
  * Возможность добавлять свои функции.
  * Преобразование синтаксического дерева в  json  и обратно.
  * Хранение переменных в синтаксическом дереве в виде идентификаторов. 
  * Возможность преобразовать синтаксическое дерево обратно в формулу. (Полезно, если возможно изменение названий переменных со временем).
    
## Синтаксис 
### Строка 
Начинается и заканчивается  одинарными или двойными кавычками. Пример: *"моя строка"*, *'моя строка'*

### Число  
Число бывает двух видов: Обычное и дробное. Дробные числа пишутся через точку.  

Пример целого числа: *1200*

Пример дробного числа: *3.2*

### Переменная 
Название переменной должно начинаться с буквы и может содержать в своем названии буквы, цифры и нижнее подчеркивание. 
Пример: *Вася*, *Моя_ПЕР* 

Переменные также могут включать любые символы включая пробел, если название переменной обернуто в '$' или '\'.
Пример: *$Моя ПЕР$*, *\Моя ПЕР\* 

### Функция 
Название переменной должно начинаться с буквы и может содержать в своем названии буквы, цифры и нижнее подчеркивание. В конце названия функции идет круглая скобка, после которой передаются аргументы этой функции, разделенные знаком: точка с запятой. Функция заканчивается после закрывающей скобки. 
Пример: *МОЯ_ФУНКЦИЯ(ПЕР1; ПЕР2)*


## Установка 
```bash
composer require sobolevwladimir/vexcel
```

## Начало работы    

Подключение: 
```php
<?php
...
use SobolevWladimir\Vexcel\Parser\Parser;
```
Далее нам можно преобразовать нашу формулу в [абстрактное синтаксическое дерево](https://ru.wikipedia.org/wiki/Абстрактное_синтаксическое_дерево) (Далее АСТ). 

```php
$parser = new Parser();
$ast = $parser->parse('3+3');// Получили синтаксическое дерево
````
Для получения значения вызовем функцию calculate():
```php
$answer = $ast->calculate(); // $answer = 6
```
Почему тут столько шагов? Т.к сам расчет производится в АСТ, вам достаточно один раз распарсить формулу, а потом, меняя на ходу значения переменных, рассчитать значение уже для нового поля (про переменные [ниже](https://github.com/SobolevWladimir/vexcel/tree/logo#использование-переменных-в-формулах) ). 
Например: 
```php
$parser = new Parser();
$ast = $parser->parse('ВАША_ПЕРЕМЕННАЯ+3');// Получили синтаксическое дерево

foreach($repositorys as $repository) {
    $answer = $ast->calculate($repository);
    // ... you code
}
````

### Использование переменных в формулах: 
Для использования переменных, нам необходимо объяснить системе: откуда брать значения переменных. Для этого создайте класс, реализующий интерфейс ValueRepository.
Например, давайте представим, что у нас есть переменные, названия которых соответствуют числам ("ПЕРЕМЕН_ОДИН"=1, "ПЕРЕМЕН_ДВА"=2, "ПЕРЕМЕН_ТРИ"=3 и тд). Тогда класс будет иметь следующий вид: 
```php
use SobolevWladimir\Vexcel\Repository\ValueRepositoryInterface;

class ValueRepositoryFake implements ValueRepositoryInterface
{
    /** @var array<string, int> */
    private array $variables = [
        'ПЕРЕМЕН_ОДИН'   => 1,
        'ПЕРЕМЕН_ДВА'    => 2,
        'ПЕРЕМЕН_ТРИ'    => 3,
        'ПЕРЕМЕН_ЧЕТЫРЕ' => 4,
        'ПЕРЕМЕН_ПЯТЬ'   => 5,
        'ПЕРЕМЕН_ШЕСТЬ'  => 6,
    ];

    public function getValueByIdentifier(string $identificator): mixed
    {
        return $this->variables[$identificator];
    }

   
}
```
Функция getValueByIdentifier принимает идентификатор переменной  (по умолчанию идентификатор равен имени переменной. <a href="https://github.com/SobolevWladimir/vexcel/blob/logo/README.md#хранение-формул-в-бд"> cм. подробнее: хранение формул в БД </a> ) и возращает значение. 

Далее мы передаем экземпляр этого класса для подсчета: 
```php
$parser = new Parser();
$ast = $parser->parse('ПЕРЕМЕН_ТРИ+3');// Получили синтаксическое дерево

$repository = new ValueRepositoryFake();// Наш репозиторий

$answer = $ast->calculate($repository); // $answer = 6
```


### Использование функций:  

```php
$parser = new Parser();
$ast = $parser->parse('ЕСЛИ(НЕ(3<2);"ДА";"НЕТ")');// Получили синтаксическое дерево
$answer = $ast->calculate(); // $answer = 'ДА'
```
На данный момент реализованы следующие функции по умолчанию: 'ЕСЛИ', 'НЕ', 'ОКРУГЛВЕРХ', 'ОКРУГЛНИЗ'. Если вам необходимо реализовать свои функции, то создайте класс, унаследованный от FunctionBuilder и переопределите метод  "build":
```php
$parser = new Parser(functionBuilder: new YouFunctionBuilder());
$ast = $parser->parse('МОЯФУНЦИЯ(3<2)');// Получили синтаксическое дерево
$answer = $ast->calculate(); 
```

### Хранение формул в БД
Если в вашей формуле есть переменные, название которых могут менятся со временем (Как пример: ваши переменные хранятся в БД), то имеет смысл хранить не саму формулу, введеную пользователем,  а АСТ, и в нем хранить постоянные идентификаторы на эти переменные. Тогда при преобразовании дерева обратно в формулу названия переменных будет востановленно по идентификаторам. 

Для этого: 
1) Создайте класс, реализующий интерфейс VariableRepositoryInterface  и передайте его в конструктор Parser().
2) Преобразуйте формулу в АСТ путем вызова $parser->parse();
3) Это дерево сохраните в виде json в бд (json_encode($ast)).

Чтобы преобразовать json обратно в синтаксическое дерево, выполните следующую процедуру: 
```php
$ast = FormulaAST::fromJson(json_decode((string)$formulaJson, true)); 
```
Далее можно преобразовать это дерево обратно в формулу: 
 ```php
$encoder = new VexcelEncoder($youVariableRepository); 
$code = $ast->toCode($encoder); 
```
 
