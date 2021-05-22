
# 引入composer

不用再向后端催接口、求文档。数据和结构完全定制，要啥有啥。看请求知结果，所求即所得。可一次获取任何数据、任何结构。能去除重复数据，节省流量提高速度。

# 使用案例

```shell script
composer require mdao/query-orm-server
```

### thinkPHP 使用 
```php

use mdao\QueryOrmServer\Servers\QueryServer;
use mdao\QueryOrmServer\Entities\OrmEntity;

 $queryServer = new QueryServer(OrmEntity::createEntity(request()->get()));

//获取where
$queryServer->getQueryWheres();
//将where转换为数组
$queryServer->getQueryWheres()->toArray();
//将where转换为json
$queryServer->getQueryWheres()->toJson();
//获取where数量
count($queryServer->getQueryWheres());
//获取字段操作符
$queryServer->getQueryWheres()['id']->getOperator();
//获取值
$queryServer->getQueryWheres()['id']->getValue();


//获取select
$queryServer->getQuerySelect();
//获取OrderBy
$queryServer->getQueryOrderBy();
//获取Pagination
$queryServer->getQueryPagination();

```

# 查询表达式
表达式`不分大小写`，支持的查询表达式有下面几种，分别表示的含义是：

| 表达式                         | 描述             |
| ------------------------------ | ---------------- |
| filter[field{eq}]=1            | 等于             |
| filter[field{neq}]=1           | 不等于           |
| filter[field{gt}]=1            | 大于             |
| filter[field{egt}]=1           | 大于等于         |
| filter[field{lt}]=1            | 小于             |
| filter[field{elt}]=1           | 小于等于         |
| filter[field{like}]=1          | 模糊查询         |
| filter[field{in}]=1      | in 查询  |
| filter[field{between}]=1 | 区间查询 |

## 表达式查询的用法示例如下： 

eq ：等于（=）
---
例如：
```
url?filter[id{eq}]=100 
或者
url?filter[id]=100 
```
服务端会解析成
```php
where('id',100);
```
表示的查询条件就是 id = 100

neq ：不等于（<>）
---
例如：
```
url?filter[id{neq}]=100 
```
服务端会解析成
```php
where('id','<>',100);
```
表示的查询条件就是 id != 100

gt ：大于（>）
---
例如：
```
url?filter[id{gt}]=100 
```
服务端会解析成
```php
where('id','>',100);
```
表示的查询条件就是 id > 100

egt ：大于等于（>=）
---
例如：
```
url?filter[id{egt}]=100 
```
服务端会解析成
```php
where('id','>=',100);
```
表示的查询条件就是 id >= 100

lt ：小于（<）
---
例如：
```
url?filter[id{lt}]=100 
```
服务端会解析成
```php
where('id','<',100);
```
表示的查询条件就是 id < 100

elt ：小于等于（<=）
---
例如：
```
url?filter[id{elt}]=100 
```
服务端会解析成
```php
where('id','<=',100);
```
表示的查询条件就是 id <= 100

like ：同sql的LIKE
---
例如：
```
url?filter[id{like}]=张三% 
```
服务端会解析成
```php
where('id','like','张三%');
```

in ：查询 id为1,2,3 的数据
---
例如：
```
支持
url?filter[id]=[1,2,3]
url?filter[id{in}]=1,2,3 
url?filter[id{in}]=[1,2,3]
不支持
url?filter[id]=1,2,3
```
服务端会解析成
```php
where('id','in',('1,2,3'));
```

between ：查询 id为1到8 的数据
---
例如：
```
url?filter[id{between}]=1,8 
```
服务端会解析成
```php
where('id','between','1,8');
```

## 多字段，多条件组合使用
---
```
url?filter[type{eq}]=1&filter[age{lt}]=50&filter[sex{neq}]=2
```
服务端会解析成
```php
where([
['type','=',1],
['age','<',50],
['sex','<>',2],
]);
```
表示的查询条件是 type=1 并且 age<50 并且 sex!=2

用法示例如下：
```shell script
url?page=1&page_size=15
```

# 字段过滤
--- 
| 参数名                         | 描述             |
| ------------------------------ | ---------------- |
| select            | 显示的字段以逗号相隔 |

用法示例如下：
```shell script
url?select=id,date,content
```
表示的是只显示id,date,content 字段

# 字段别名
---
如果想要使用字段的别名可以这样写：select=id,date:time,content:text
```shell script
url?select=id,date,content:text
```
表示的是只显示id,date,text 字段

# 排序
---
1. 单字段排序

| 参数名                         | 描述             |
| ------------------------------ | ---------------- |
| order_by            | 排序字段 |
| sorted_by           | 排序方式 默认就是升序排列 |
用法示例如下：
```shell script
url?order_by=id&sorted_by=desc
```
表示的是根据id降序排序

2. 多字段排序

| 参数名                         | 描述             |
| ------------------------------ | ---------------- |
| order_by            | 排序字段 |
| sorted_by           | 排序方式 默认就是升序排列 |

多字段排序已逗号分开
用法示例如下：
```shell script
url?order_by=id,type&sorted_by=desc,asc
```
表示的是根据id降序排序，和更据type升序排序
`请注意 两个字段排序方式相同时可以简写sorted_by字段里面的值为desc或asc`

# 分页
---
| 参数名                         | 描述             |
| ------------------------------ | ---------------- |
| page            | 页码，从1开始 |
| page_size           | 每页几条数据         |

用法示例如下：
```shell script
url?page=1&page_size=15
```
