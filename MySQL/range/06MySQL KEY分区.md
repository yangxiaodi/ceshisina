# [MySQL KEY分区][0]

### 介绍 

KEY分区和HASH分区相似，但是KEY分区支持除text和BLOB之外的所有数据类型的分区，而HASH分区只支持数字分区，KEY分区不允许使用用户自定义的表达式进行分区，KEY分区使用系统提供的HASH函数进行分区。当表中存在主键或者唯一键时，如果创建key分区时没有指定字段系统默认会首选主键列作为分区字列,如果不存在主键列会选择非空唯一键列作为分区列,注意唯一列作为分区列唯一列不能为null。

### 一、常规KEY

**1.创建分区**

```sql
    CREATE TABLE tb_key (
        id INT ,
        var CHAR(32) 
    )
    PARTITION BY KEY(var)
    PARTITIONS 10;

    SELECT PARTITION_NAME,PARTITION_METHOD,PARTITION_EXPRESSION,PARTITION_DESCRIPTION,TABLE_ROWS,SUBPARTITION_NAME,SUBPARTITION_METHOD,SUBPARTITION_EXPRESSION 
    FROM information_schema.PARTITIONS WHERE TABLE_SCHEMA=SCHEMA() AND TABLE_NAME='tb_key';

    INSERT INTO tb_key() VALUES(1,'星期一'),(2,'1998-10-19'),(3,'new'),(4,'非常好'),(5,'5');
```

![][1]

### 二、LINEAR KEY

同样key分区也存在线性KEY分区，概念和线性HASH分区一样。

**1.创建分区**
```sql
    CREATE TABLE tb_keyline (
        id INT NOT NULL,
        var CHAR(5)
    )
    PARTITION BY LINEAR KEY (var)
    PARTITIONS 3;
```

![][2]

### 三、分区管理

key分区管理和hash分区管理是一样的，只能删除和增加分区，这里不再做详细介绍。

**1.删除2个分区**

    ALTER TABLE tb_key COALESCE PARTITION 2;

**2.增加三个分区**

    ALTER TABLE tb_key add PARTITION partitions 3;

### 四、移除表的分区

    ALTER TABLE tablename
    REMOVE PARTITIONING ;

注意：使用remove移除分区是仅仅移除分区的定义，并不会删除数据和drop PARTITION不一样，后者会连同数据一起删除

**参考：**

### **总结** 

KEY分区和HASH分区类似，在处理大量数据记录时能有效的分散数据热点。

[0]: http://www.cnblogs.com/chenmh/p/5647210.html
[1]: ./img/135426-20160706174140311-157149005.png
[2]: ./img/135426-20160706175311717-981974037.png
