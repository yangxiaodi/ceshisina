 **fdisk命令** **-->磁盘分区管理工具**

【 **添加分区的流程** 】

fdisk /dev/sda # 选择要分区的硬盘  
p # 列出当前分区表  
n # 添加新分区  
回车 # 选择开始的块地址,直接回车默认就可以了  
+2G # 输入要添加分区的大小+200M，+1G这样的都能识别  
回车 # 确定  
w # 写入并退出  
partprobe # 更新当前分区表给内核 这一步非常重要, 否则你的分区重启才能看到.  
mkfs.ext3 /dev/sda6 # 格式化新建分区  
mount /dev/sda6 /data # 挂载  
  
**备注:**  
另外t 参数可以对分区格式做转换，fd是raid类型,e8是做LVM时用到的pv类型

![][0]

【 **什么是分区** 】

分区是将一个硬盘驱动器分成若干个逻辑驱动器，分区是把硬盘连续的区块当做一个独立的磁硬使用。

分区表是一个硬盘分区的索引,分区的信息都会写进分区表。

【 **为什么要有多个分区** 】  
1) 防止数据丢失：如果系统只有一个分区，那么这个分区损坏，用户将会丢失所的有数据。  
2) 增加磁盘空间使用效率：可以用不同的区块大小来格式化分区，如果有很多1K的文件，而硬盘分区区块大小为4K，那么每存储一个文件将会浪费3K空间。这时我们需要取这些文件大小的平均值进行区块大小的划分。  
数据激增到极限不会引起系统挂起：将用户数据和系统数据分开，可以避免用户数据填满整个硬盘，引起的系挂起。

[0]: ./img/20170212194752027.png