 **last命令** **-->列出截止目前登录过系统的用户信息;是Linux内置的审计跟踪工具**

**last信息解读**   
第一列: 用户名   
第二列: 终端位置  

    >>>pts: 意味着从SSH或TELNET的远程连接用户  
    >>>tty: 意味着直接连接到计算机或者本地连接用户  
    >>> 除了重启，所有状态会在启动时显示  
第三列: 登录IP或者内核  

    >>>0.0或者什么都没有的话：意味着用户通过本地终端连接  
    >>>重启活动，会显示内核版本  
第四列: 开始时间  
第五列: 结束时间  

    >>>still log in: 还在登录  
    >>>down: 直到正常关机  
    >>>crash: 直到强制关机  
第六列: 持续时间

**![][0]**

**【备注一】关于last命令的几点说明:**  
1. wtmp,btmp,utmp均为二进制文件，不能用cat查看，可用last打开  
2. echo > /var/log/wtmp 可清空wtmp记录

【 **备注二** 】**Linux系统的三个主要日志子系统:**  
1. 进程日志(acct/pacct: 记录用户命令)  
2. 错误日志(/var/log/messages:系统级信息；access-log:记录HTTP/WEB的信息)  
3. 连接日志(/var/log/wtmp,/var/log/btmp,/var/run/utmp)  

    >>>有关当前登录用户的信息记录在文件utmp中;  
    >>>登录进入和退出纪录在文件wtmp中;  
    >>>最后一次登录文件可以用lastlog命令察看;  
    >>>数据交换、关机和重起也记录在wtmp文件中;

[0]: ./img/20170114155651220.png