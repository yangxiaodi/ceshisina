# [使用tshark在命令行进行网络抓包](http://www.kaimingwan.com/post/ji-chu-zhi-shi/wang-luo/shi-yong-tsharkzai-ming-ling-xing-jin-xing-wang-luo-zhua-bao)

November 17, 2017 阅读量:12

* [1. tshark 选项说明一览][0]
* [2. tshark常用指令][1]
    * [2.1 不使用任何选项][2]
    * [2.2 使用-i参数指定需要抓包的设备][3]
    * [2.4 网络包过滤][4]
        * [2.4.1 capature filter(-f参数)][5]
            * [几种原语(primitive)][6]
        * [2.4.3 display filter(-Y参数)][7]
            * [协议与过滤字段][8]
            * [准备知识][9]
            * [配合两种过滤器使用的-T参数][10]
            * [**使用案例1： 获取HTTP请求的类型、URL、HOST等信息**][11]
            * [使用案例2： 获取SQL语句][12]
* [3. 其他有用的参数][13]
    * [3.1 -c参数指定停止条件][14]
    * [3.2 -V参数查看packet详情][15]
    * [3.3 -w写出到文件][16]
    * [3.4 -r 参数读取cap文件分析（重要）][17]
        * [实际案例][18]
* [4. 总结][19]
* [5. 练习题][20]

 使用wireshark的客户端进行抓包，或者tcpdump抓包再用wireshark分析，网上已经有很多资料了，这里就不再赘述了。不熟悉的可以看看网上的资料：[http://www.jianshu.com/p/a62ed1bb5b20][21] 或者官方文档user guide：[https://www.wireshark.org/docs/wsug_html_chunked/][22]

 线上问题排查，有时候时间争分夺秒，或者一些私有云环境完全隔离，根本没法让你导出抓包的文件，这时候需要直接使用命令行进行抓包分析。今天我们就来学习下tshark的使用。PS： tshark基本上可以替代tcpdump，抓包的文件也可以直接用于wireshark来分析

 主要先通过yum install wireshark安装后才能使用tshark

### 1. tshark 选项说明一览

 这么多参数肯定懒得看，我们继续下一节，掌握最常用的一些参数的用法。
```
TShark (Wireshark) 2.5.0 (v2.5.0rc0-1500-g78f9a07f)
Dump and analyze network traffic.
See https://www.wireshark.org for more information.

Usage: tshark [options] ...

Capture interface:
  -i <interface>           name or idx of interface (def: first non-loopback)
  -f <capture filter>      packet filter in libpcap filter syntax
  -s <snaplen>             packet snapshot length (def: appropriate maximum)
  -p                       don't capture in promiscuous mode
  -I                       capture in monitor mode, if available
  -B <buffer size>         size of kernel buffer (def: 2MB)
  -y <link type>           link layer type (def: first appropriate)
  --time-stamp-type <type> timestamp method for interface
  -D                       print list of interfaces and exit
  -L                       print list of link-layer types of iface and exit
  --list-time-stamp-types  print list of timestamp types for iface and exit

Capture stop conditions:
  -c <packet count>        stop after n packets (def: infinite)
  -a <autostop cond.> ...  duration:NUM - stop after NUM seconds
                           filesize:NUM - stop this file after NUM KB
                              files:NUM - stop after NUM files
Capture output:
  -b <ringbuffer opt.> ... duration:NUM - switch to next file after NUM secs
                           interval:NUM - create time intervals of NUM secs
                           filesize:NUM - switch to next file after NUM KB
                              files:NUM - ringbuffer: replace after NUM files
Input file:
  -r <infile>              set the filename to read from (- to read from stdin)

Processing:
  -2                       perform a two-pass analysis
  -M <packet count>        perform session auto reset
  -R <read filter>         packet Read filter in Wireshark display filter syntax
                           (requires -2)
  -Y <display filter>      packet displaY filter in Wireshark display filter
                           syntax
  -n                       disable all name resolutions (def: all enabled)
  -N <name resolve flags>  enable specific name resolution(s): "mnNtCd"
  -d <layer_type>==<selector>,<decode_as_protocol> ...
                           "Decode As", see the man page for details
                           Example: tcp.port==8888,http
  -H <hosts file>          read a list of entries from a hosts file, which will
                           then be written to a capture file. (Implies -W n)
  --enable-protocol <proto_name>
                           enable dissection of proto_name
  --disable-protocol <proto_name>
                           disable dissection of proto_name
  --enable-heuristic <short_name>
                           enable dissection of heuristic protocol
  --disable-heuristic <short_name>
                           disable dissection of heuristic protocol
Output:
  -w <outfile|->           write packets to a pcap-format file named "outfile"
                           (or to the standard output for "-")
  -C <config profile>      start with specified configuration profile
  -F <output file type>    set the output file type, default is pcapng
                           an empty "-F" option will list the file types
  -V                       add output of packet tree        (Packet Details)
  -O <protocols>           Only show packet details of these protocols, comma
                           separated
  -P                       print packet summary even when writing to a file
  -S <separator>           the line separator to print between packets
  -x                       add output of hex and ASCII dump (Packet Bytes)
  -T pdml|ps|psml|json|jsonraw|ek|tabs|text|fields|?
                           format of text output (def: text)
  -j <protocolfilter>      protocols layers filter if -T ek|pdml|json selected
                           (e.g. "ip ip.flags text", filter does not expand child
                           nodes, unless child is specified also in the filter)
  -J <protocolfilter>      top level protocol filter if -T ek|pdml|json selected
                           (e.g. "http tcp", filter which expands all child nodes)
  -e <field>               field to print if -Tfields selected (e.g. tcp.port,
                           _ws.col.Info)
                           this option can be repeated to print multiple fields
  -E<fieldsoption>=<value> set options for output when -Tfields selected:
     bom=y|n               print a UTF-8 BOM
     header=y|n            switch headers on and off
     separator=/t|/s|<char> select tab, space, printable character as separator
     occurrence=f|l|a      print first, last or all occurrences of each field
     aggregator=,|/s|<char> select comma, space, printable character as
                           aggregator
     quote=d|s|n           select double, single, no quotes for values
  -t a|ad|d|dd|e|r|u|ud|?  output format of time stamps (def: r: rel. to first)
  -u s|hms                 output format of seconds (def: s: seconds)
  -l                       flush standard output after each packet
  -q                       be more quiet on stdout (e.g. when using statistics)
  -Q                       only log true errors to stderr (quieter than -q)
  -g                       enable group read access on the output file(s)
  -W n                     Save extra information in the file, if supported.
                           n = write network address resolution information
  -X <key>:<value>         eXtension options, see the man page for details
  -U tap_name              PDUs export mode, see the man page for details
  -z <statistics>          various statistics, see the man page for details
  --capture-comment <comment>
                           add a capture comment to the newly created
                           output file (only for pcapng)
  --export-objects <protocol>,<destdir> save exported objects for a protocol to
                           a directory named "destdir"
  --color                  color output text similarly to the Wireshark GUI,
                           requires a terminal with 24-bit color support
                           Also supplies color attributes to pdml and psml formats
                           (Note that attributes are nonstandard)
  --no-duplicate-keys      If -T json is specified, merge duplicate keys in an object
                           into a single key with as value a json array containing all
                           values
Miscellaneous:
  -h                       display this help and exit
  -v                       display version info and exit
  -o <name>:<value> ...    override preference setting
  -K <keytab>              keytab file to use for kerberos decryption
  -G [report]              dump one of several available reports and exit
                           default report="fields"
                           use "-G help" for more help

WARNING: dumpcap will enable kernel BPF JIT compiler if available.
You might want to reset it
By doing "echo 0 > /proc/sys/net/core/bpf_jit_enable"
```
### 2. tshark常用指令

 我们通过一步步的增加命令的复杂度，使用更多的过滤条件从而来学习tshark。

#### 2.1 不使用任何选项

 直接使用tshark，会抓取第一个非回环网卡的所有网络包，本机时候用效果如下  

![][23]

 可以看到包含源和目标的地址和端口信息，还有协议和标志位等信息，和wireshark客户端上看到的效果是一样的

 

![][24]

#### 2.2 使用-i参数指定需要抓包的设备

 PS： 这里可以用tshark -D查看有哪些设备，当然也可以通过ifconfig来查看

 tshark -i的

    tshark -i en0

 

![][25]

#### 2.4 网络包过滤

 抓包的时候根据协议和端口来过滤是比较常见的用法，比如要抓取HTTP的网络包，或者抓取TCP的网络包等等。

##### 2.4.1 capature filter(-f参数)

 capature filter实际使用的参数为-f，也是默认的过滤器，所以一般不带这个参数也是可以的。

 tshark支持不带参数就可以使用的过滤表达式，和tcpdump的用法很接近，详情参考：[https://www.wireshark.org/docs/wsug_html_chunked/ChCapCaptureFilterSection.html][26]

 使用格式为：

    [not] primitive [and|or [not] primitive ...]

###### 几种原语(primitive)

 上面文档中4.2节罗列了可以使用的原语，为了方便学习，下面表格总结了最常见的几种原语,多个原语之间可以采用逻辑表达式and、or和not。PS：注意不能使用|| &&等符号

原语 说明 例子 [src dst] host 根据源端和目标端的IP进行过滤 [tcp|udp] [src|dst] port 根据协议、端口来进行过滤 tcp src 192.168.1.1 port 1080 

 原语之间可以通过逻辑运算符结合起来使用：  首先采用一条原语抓包获取发往其他主机80端口的TCP网络包：

    tshark tcp dst port 80

 

![][27]

 在上面原语的基础上增加目标端host ip的过滤条件

    tshark tcp dst port 80 and dst host 60.190.116.48

 

![][28]

 PS: _ip|ether proto 这个原语也挺有意思，你可以用命令tshark -G protocols查看支持哪些协议，这里有这些协议的缩写和全称_

##### 2.4.3 display filter(-Y参数)

 这个选项应该是这个命令中比较复杂的一个选项了，通过指定表达式可以支持非常复杂的过滤条件。-f的过滤表达式应该是2.4.2节功能的超集。表达式支持更加细粒度的过滤，例如http.request.url或者mysql.query等等。可以按照packet类型过滤，也可以按照一些等值条件进行过滤。这些针对特定应用层协议(HTTP协议、MYSQL协议)的过滤字段需要参考官方文档。

###### 协议与过滤字段

 所有支持协议的表达式  [https://www.wireshark.org/docs/dfref/][29]

 比较常用的是HTTP和MYSQL，可以参考如下官方文档：

1. mysql协议：[https://www.wireshark.org/docs/dfref/m/mysql.html][30]
1. HTTP协议：[https://www.wireshark.org/docs/dfref/h/http.html][31]

 因为最常用的过滤可以用不带参数的方式过滤，关于这种带参数的过滤方法，有兴趣的话可以参考文档：[https://www.wireshark.org/docs/man-pages/wireshark-filter.html][32]

###### 准备知识

 文档里面几个会用到的关系符，我这里先罗列下，建议直接使用第二列的数学符号，比较直观也好记

 **比较表达式**

        eq, ==    Equal
        ne, !=    Not Equal
        gt, >     Greater Than
        lt, <     Less Than
        ge, >=    Greater than or Equal to
        le, <=    Less than or Equal to

 **逻辑表达式**

    and, &&   Logical AND
    or,  ||   Logical OR
    not, !    Logical NOT

###### 配合两种过滤器使用的-T参数

 -Y参数必须配合-T参数使用才能成功打印出过滤后的结果，-T参数支持多种不同的输出格式，比如json、fields等等，最常用的是fileds，我们这里也只介绍和演示这种格式。fields的输出格式需要通过-e来指定需要打印的filed value,具体使用方法可以参考后面的使用案例。-T参数可以配合-f 或者-Y两种类型的过滤器。

 PS：-T参数指定field打印存在一些局限性，就是打印之后能只能在标准输出查看，或者将输出记录到文件，没法通过指定-w写入cap类型文件供wireshark来分析。

###### **使用案例1： 获取HTTP请求的类型、URL、HOST等信息**

 这里同时使用了两种过滤器

 

![][33]

###### 使用案例2： 获取SQL语句

 我这里用的field是mysq.query和，配个的过滤器是表达式过滤器

 

![][34]

 实际经过测试发现，基本上也就mysql.query、mysql.login_request这两个field比较有用，大部分MYSQL协议的packet都是这两种类型的。

### 3. 其他有用的参数

#### 3.1 -c参数指定停止条件

 抓包不指定停止条件会一直抓，tshark提供了几种停止的条件，常用的就是用-c来指定需要抓几个包，使用效果可以参考3.2节

#### 3.2 -V参数查看packet详情

 -V参数可以将packet展看查看详情，效果如下：

 

![][35]

 PS：-V参数无法和-T一起使用，-T指定的filed之后只会输出field的信息了

#### 3.3 -w写出到文件

 -w选项后可以接路径和文件名，保存到文件，默认按照cap格式保存。另外指定-T参数之后无法再使用-w，请注意。

#### 3.4 -r 参数读取cap文件分析（重要）

 一般情况下，针对一次抓包之后，我们很有可能进行多个维度的分析，所以先存到文件，再进行分析，是很常见的使用方法。下面通过一个实际案例来学习下这种抓包分析分发。

 PS: -Y指定的表达式过滤不能配合写文件使用，要在写文件前进行过滤，可以使用-f的capture filter

 

![][36]

##### 实际案例

 > 需求

 小明同学在排查问题的时候，突然有个需求，就是需要知道当前网络包抓取的SQL来源于哪个源端，并且需要知道SQL的内容。

 > 分析

 通过mysql.query可以来获取SQL内容，但是无法直接依靠-T参数输出指定field来满足该需求。因为-T指定输出格式后，无法知道SQL来自于哪个源端。

 思考后可行的方案为： 直接先用-f过滤，先过滤出指定类型和port上的packet，存入到文件，然后利用-V展开后再来分析。这种方式既可以按照条件过滤网络包，然后又可以采用tshark来分析，知道packet的源端信息。

 > 操作

1. 按照表达式过滤指定类型的packet
```
    tshark -c 50 -f 'tcp dst port 3306 and dst host 10.195.33.7 ' -w mysql_test.cap
```
1. 验证过滤结果

 

![][37]

1. 读取cap文件，并且展开包。需要的信息为源端和目标端的IP以及SQL内容。根据packet的格式，进行grep来获取需要的信息。
```
    tshark -r mysql_test.cap -V | egrep 'Internet|Statement'
```
 

![][38]

### 4. 总结

 通过一步步由简单到复杂，并且学习了最常用的选项之后，相信大家已经基本掌握了tshark的用法。

 针对简单的、单一维度查询的抓包需求，可以直接通过-f 和-Y来进行过滤，按照-T进行输出。

 针对需要多维度查询、查看packet详情的，可以直接通过-w导出抓包结果，再使用-r结合grep来查看分析。

### 5. 练习题

 自行准备测试环境，包含主机A和主机B，其中主机B上有DB可以进行SQL操作。在主机A上启动tshark进行抓包，将满足以下过滤条件的抓包结果全部写入文件tshark_x_y.pcap，其中x为下面习题的编号,如果一个习题下需要产生多个dump文件，可以用y进行编号，否则直接写成tshark_x.pcap即可。

1. 用tshark查看自己有哪些接口，然后分别获取Loopback和WIFI网卡上的网络包，查看区别
1. 用tshark捕获本地的tcp网络包，持续捕获30秒
1. 捕获本地3306端口上的网络包，捕获100条
1. 获取本地发往百度的HTTP流量包，并且查看cookies和METHOD信息
1. 获取包含特定关键字“XXX”的SQL，获取其发送端和目标端的IP和端口信息，以及其SQL内容，以及源端发送的时间
1. 指定源端的IP和协议、目标端的IP和协议，获取MYSQL登入的userName信息和网络包的发送时间，要求同时使用capture filter和expression filter，并且尝试使用-T参数在标准输出打印userName信息
1. 随便尝试建立一个TCP连接，捕获网络包，通过dump的pcap文件分析三次握手和四次握手时seq、ack的变化

[0]: #toc_0
[1]: #toc_1
[2]: #toc_2
[3]: #toc_3
[4]: #toc_4
[5]: #toc_5
[6]: #toc_6
[7]: #toc_7
[8]: #toc_8
[9]: #toc_9
[10]: #toc_10
[11]: #toc_11
[12]: #toc_12
[13]: #toc_13
[14]: #toc_14
[15]: #toc_15
[16]: #toc_16
[17]: #toc_17
[18]: #toc_18
[19]: #toc_19
[20]: #toc_20
[21]: http://www.jianshu.com/p/a62ed1bb5b20
[22]: https://www.wireshark.org/docs/wsug_html_chunked/
[23]: ../IMG/15-03-15.jpg
[24]: ../IMG/15-03-29.jpg
[25]: ../IMG/15-03-38.jpg
[26]: https://www.wireshark.org/docs/wsug_html_chunked/ChCapCaptureFilterSection.html
[27]: ../IMG/15-04-07.jpg
[28]: ../IMG/15-04-17.jpg
[29]: https://www.wireshark.org/docs/dfref/
[30]: https://www.wireshark.org/docs/dfref/m/mysql.html
[31]: https://www.wireshark.org/docs/dfref/h/http.html
[32]: https://www.wireshark.org/docs/man-pages/wireshark-filter.html
[33]: ../IMG/15-05-14.jpg
[34]: ../IMG/15-05-35.jpg
[35]: ../IMG/15-05-47.jpg
[36]: ../IMG/15-05-59.jpg
[37]: ../IMG/15-06-43.jpg
[38]: ../IMG/15-07-22.jpg