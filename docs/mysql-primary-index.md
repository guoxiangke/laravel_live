主键和唯一索引的区别？
    1）主键一定会创建一个唯一索引，但是有唯一索引的列不一定是主键；    
    2）主键不允许为空值，唯一索引列允许空值；   
    3）一个表只能有一个主键，但是可以有多个唯一索引；   
    4）主键可以被其他表引用为外键，唯一索引列不可以；   
    5）主键是一种约束，而唯一索引是一种索引，是表的冗余数据结构，两者有本质的差别
$table->morphs('memberable'); //morphs里面已经创建了索引了！