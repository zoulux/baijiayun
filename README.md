# baijiayun sdk

公司已经需要在多个项目中使用百家云了，故整理一套sdk。

## Requirement
- PHP >= 7.0
- [Composer](https://getcomposer.org/)
- [guzzlehttp](http://guzzlephp.org/) 扩展

## Installation
```bash
$ composer require "jake/baijiayun" -vvv
```

## Usage
基本使用

```php
$bjcloud = new  \Jake\Baijiayun\BJCloud([
    'partnerId' => '####',
    'partnerKey' => '####'
]); 
$res = $bjcloud->roomCreate('live', time() + 60 * 60, time() + 60 * 60 * 2);
print_r($res);
```
更多文档参考 [百家云直播文档](http://dev.baijiayun.com/wiki/detail/79)

## License
MIT