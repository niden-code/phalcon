# Container Usage

> **Note:**
>
> If you use PHPStorm, you can copy the `resources/phpstorm.meta.php`
> file to your project root as `.phpstorm.meta.php` for autocompletion on
> `get()` and `new()` method calls.

After instantiating a _Container_ ...

```php
use Phalcon\Container\Container;use Phalcon\Container\Definition\Definitions;

$def = new Definitions();
$container = new Container($def);
```

... use its methods to retrieve identified objects and values.

## Retrieving Shared Instances

<code>get(*string* $id): *mixed*</code>

Returns a shared instance of the defined class. Multiple calls to `get()` return
the same object.

```php
$foo1 = $container->get(Foo::class);
$foo2 = $container->get(Foo::class);
var_dump($foo1 === $foo2); // bool(true)
```

## Retrieving New Instances

<code>new(*string* $id): *mixed*</code>

Returns a new instance of the defined class. Multiple calls to `new()` return
different new object instances.

```php
$foo1 = $container->new(Foo::class);
$foo2 = $container->new(Foo::class);
var_dump($foo1 === $foo2); // bool(false)
```

## Retrieving Values

You can use `get()` to retrieve defined primitive values.

```php
$host = $container->get('db.host');
```

If a primitive value defined as a _Lazy_, multiple calls to `get()` will return
the same value. However, multiple calls to `new()` may return different values,
as the _Lazy_ will be re-evaluated on each call.

## Checking For Existence

<code>has(*string* $id): *bool*</code>

Returns `true` if the _Container_ can successfully `get()` or `new()` the `$id`;
otherwise, `false`.

```php
$container->has(stdClass::class); // true
$container->has('NoSuchClass'); // false
```

## Callable Factories

<code>callableGet(*string* $id): *callable*</code>

<code>callableNew(*string* $id): *callable*</code>

These return a call to `get()` or `new()` wrapped in a closure. Useful for
providing factories to other containers.

```php
$callableGet = $container->callableGet(Foo::class);
$foo1 = $callableGet();
$foo2 = $callableGet();
var_dump($foo1 === $foo2); // bool(true)

$callableNew = $container->callableNew(Foo::class);
$foo1 = $callableNew();
$foo2 = $callableNew();
var_dump($foo1 === $foo2); // bool(false)
```
