# Lang-PHP
Language changer for PHP

## Initialization
### Parameters
`current`: Current language

`available`: Array of available languages

`dir` (optional): Directory of language files exists (default: lang)

`default`: Default language if current language doesn't match available languages

`cookie` (optional): Cookie name (default: LANG)

`cookieExpire` (optional): Expire time for cookies in Unix timestamp (default: 168 days)

### How it works
Script finds `.json` files in given language directory then initializes it

### Code
```php
<?php
require_once 'Lang.php';

$L = Lang([

  'current' =>      $_GET['lang'],
  'available' =>    ['az', 'en'],
  'default' =>      'az',
  'cookie' =>       'LANG',
  'cookieExpire' => time()+86400*24*7

]);
```

## Usage
### Single Key
Gets keyName from current language's json file

Example JSON: `{ "keyName": "Hi there!" }`
```php
<?php
echo $L->key('keyName');  // Hi there!
```

### Nested keys

Example JSON: `{ "keyName": [ {"inside": "This is the value inside keyName" } ] }`
```php
<?php
echo $L->key('keyName@inside');   // This is the value inside keyName
```

### Variables

Example JSON: `{ "keyName": "Hello :name !" }`

```php
$values = [ 'name' => 'Mahabbat!' ];
echo $L->key('keyName', $values);   // Hello Mahabbat !
```
