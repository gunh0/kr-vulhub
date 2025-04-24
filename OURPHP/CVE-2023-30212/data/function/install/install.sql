DROP TABLE IF EXISTS `ourphp_web`;
CREATE TABLE `ourphp_web` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Website` varchar(255) NOT NULL default '',
`OP_Weburl` varchar(255) NOT NULL default '',
`OP_Weblogo` varchar(255) NOT NULL default '',
`OP_Webname` varchar(255) NOT NULL default '',
`OP_Webadd` varchar(255) NOT NULL default '',
`OP_Webtel` varchar(255) NOT NULL default '',
`OP_Webmobi` varchar(255) NOT NULL default '',
`OP_Webfax` varchar(255) NOT NULL default '',
`OP_Webemail` varchar(255) NOT NULL default '',
`OP_Webzip` varchar(255) NOT NULL default '',
`OP_Webqq` varchar(255) NOT NULL default '',
`OP_Weblinkman` varchar(255) NOT NULL default '',
`OP_Webicp` varchar(255) NOT NULL default '',
`OP_Webstatistics` text,
`OP_Webtime` varchar(255) NOT NULL default '',
`OP_Webourphpurl` varchar(255) NOT NULL default '',
`OP_Webourphpcode` text,
`OP_Webourphpu` text,
`OP_Webourphpp` text,
`OP_Websitemin` varchar(255) NOT NULL default '',
`OP_Weixin` varchar(255) NOT NULL default '',
`OP_Weixinerweima` varchar(255) NOT NULL default '',
`OP_Alipayname` varchar(255) NOT NULL default '',
`OP_Webhttp` varchar(255) NOT NULL default 'http://',
`OP_Webpoliceicp` varchar(255) NOT NULL default '',
`OP_Webpoliceicpurl` varchar(255) NOT NULL default '',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_wap`;
CREATE TABLE `ourphp_wap` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Website` varchar(255) NOT NULL default '',
`OP_Weblogo` varchar(255) NOT NULL default '',
`OP_Webkeywords` text,
`OP_Webdescriptions` text,
`OP_Weburl` text,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_admin`;
CREATE TABLE `ourphp_admin` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Adminname` varchar(255) NOT NULL default '',
`OP_Adminpass` varchar(255) NOT NULL default '',
`OP_Adminpower` text,
`OP_Admin` int(11) NOT NULL default '0', /*管理员主权限*/
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_article`;
CREATE TABLE `ourphp_article` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Articletitle` varchar(255) NOT NULL default '',
`OP_Articleauthor` varchar(255) NOT NULL default '',
`OP_Articlesource` varchar(255) NOT NULL default '',
`time` datetime,
`OP_Articlecontent` text,
`OP_Articlecontent_wap` text,
`OP_Class` int(11) NOT NULL default '0',
`OP_Lang` varchar(255) NOT NULL default '',
`OP_Tag` varchar(255) NOT NULL default '',
`OP_Sorting` int(11) NOT NULL default '0', /*排序*/
`OP_Attribute` varchar(255) NOT NULL default '', /*属性*/
`OP_Url` varchar(255) NOT NULL default '',
`OP_Description` text, /*描述*/
`OP_Click` int(11) NOT NULL default '0', /*点击量*/
`OP_Minimg` text, /*缩略图*/
`OP_Callback` int(11) NOT NULL default '0', /*回收站 0=NO  1=YES*/
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_photo`;
CREATE TABLE `ourphp_photo` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Phototitle` varchar(255) NOT NULL default '',
`time` datetime,
`OP_Photocminimg` varchar(255) NOT NULL default '',
`OP_Photoimg` text,
`OP_Photocontent` text,
`OP_Photocontent_wap` text,
`OP_Class` int(11) NOT NULL default '0',
`OP_Lang` varchar(255) NOT NULL default '',
`OP_Tag` varchar(255) NOT NULL default '',
`OP_Sorting` int(11) NOT NULL default '0', /*排序*/
`OP_Attribute` varchar(255) NOT NULL default '', /*属性*/
`OP_Url` varchar(255) NOT NULL default '',
`OP_Description` text, /*描述*/
`OP_Click` int(11) NOT NULL default '0', /*点击量*/
`OP_Callback` int(11) NOT NULL default '0', /*回收站 0=NO  1=YES*/
`OP_Photoname` varchar(255) NOT NULL default '',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_video`;
CREATE TABLE `ourphp_video` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Videotitle` varchar(255) NOT NULL default '',
`time` datetime,
`OP_Videoimg` text,
`OP_Videovurl` text,
`OP_Videoformat` varchar(255) NOT NULL default '',
`OP_Videowidth` varchar(255) NOT NULL default '',
`OP_Videoheight` varchar(255) NOT NULL default '',
`OP_Videocontent` text,
`OP_Videocontent_wap` text,
`OP_Class` int(11) NOT NULL default '0',
`OP_Lang` varchar(255) NOT NULL default '',
`OP_Tag` varchar(255) NOT NULL default '',
`OP_Sorting` int(11) NOT NULL default '0', /*排序*/
`OP_Attribute` varchar(255) NOT NULL default '', /*属性*/
`OP_Url` varchar(255) NOT NULL default '',
`OP_Description` text, /*描述*/
`OP_Click` int(11) NOT NULL default '0', /*点击量*/
`OP_Callback` int(11) NOT NULL default '0', /*回收站 0=NO  1=YES*/
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_down`;
CREATE TABLE `ourphp_down` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Downtitle` varchar(255) NOT NULL default '',
`time` datetime,
`OP_Downimg` text,
`OP_Downdurl` text,
`OP_Downcontent` text,
`OP_Downcontent_wap` text,
`OP_Downempower` varchar(255) NOT NULL default '',
`OP_Downtype` varchar(255) NOT NULL default '',
`OP_Downlang` varchar(255) NOT NULL default '',
`OP_Downsize` varchar(255) NOT NULL default '',
`OP_Class` int(11) NOT NULL default '0',
`OP_Lang` varchar(255) NOT NULL default '',
`OP_Downmake` varchar(255) NOT NULL default '',
`OP_Downsetup` varchar(255) NOT NULL default '',
`OP_Tag` varchar(255) NOT NULL default '',
`OP_Downrights` varchar(255) NOT NULL default '', /*下载权限*/
`OP_Sorting` int(11) NOT NULL default '0', /*排序*/
`OP_Attribute` varchar(255) NOT NULL default '', /*属性*/
`OP_Url` varchar(255) NOT NULL default '',
`OP_Description` text, /*描述*/
`OP_Click` int(11) NOT NULL default '0', /*点击量*/
`OP_Random` text, /*随机的一个验证码，用于验证下载文件的*/
`OP_Callback` int(11) NOT NULL default '0', /*回收站 0=NO  1=YES*/
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_job`;
CREATE TABLE `ourphp_job` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Jobtitle` varchar(255) NOT NULL default '',
`time` datetime,
`OP_Jobwork` varchar(255) NOT NULL default '',
`OP_Jobadd` varchar(255) NOT NULL default '',
`OP_Jobnature` varchar(255) NOT NULL default '',
`OP_Jobexperience` varchar(255) NOT NULL default '',
`OP_Jobeducation` varchar(255) NOT NULL default '',
`OP_Jobnumber` varchar(255) NOT NULL default '',
`OP_Jobage` varchar(255) NOT NULL default '',
`OP_Jobwelfare` varchar(255) NOT NULL default '',
`OP_Jobwage` varchar(255) NOT NULL default '',
`OP_Jobcontact` varchar(255) NOT NULL default '',
`OP_Jobtel` varchar(255) NOT NULL default '',
`OP_Jobcontent` text,
`OP_Jobcontent_wap` text,
`OP_Class` int(11) NOT NULL default '0',
`OP_Lang` varchar(255) NOT NULL default '',
`OP_Tag` varchar(255) NOT NULL default '',
`OP_Sorting` int(11) NOT NULL default '0', /*排序*/
`OP_Attribute` varchar(255) NOT NULL default '', /*属性*/
`OP_Url` varchar(255) NOT NULL default '',
`OP_Description` text, /*描述*/
`OP_Click` int(11) NOT NULL default '0', /*点击量*/
`OP_Callback` int(11) NOT NULL default '0', /*回收站 0=NO  1=YES*/
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_booksection`;
CREATE TABLE `ourphp_booksection` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Booksectiontitle` varchar(255) NOT NULL default '',
`OP_Booksectioncontent` text,
`OP_Booksectionlanguage` varchar(255) NOT NULL default '',
`OP_Booksectionsorting` int(11) NOT NULL default '0',
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_usercontrol`;
CREATE TABLE `ourphp_usercontrol` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Userreg` int(11) NOT NULL default '0', /*是否可以注册 1可以 2不可以*/
`OP_Userlogin` int(11) NOT NULL default '0', /*是否可以登录 1可以 2不可以*/
`OP_Userprotocol` text, /*注册协议*/
`OP_Usergroup` int(11) NOT NULL default '0', /*默认用户组*/
`OP_Usermoney` varchar(255) NOT NULL default '', /*注册增加多少现金和积分  现金|积分|推广现金|推广积分*/
`OP_Useripoff` int(11) NOT NULL default '0', /*开启IP限制，1开启 2关闭*/
`OP_Regtyle` varchar(255) NOT NULL default 'email', /*默认为注册账号类型为邮箱，tel是手机*/
`OP_Regcode` int(11) NOT NULL default '0', /*是否开启验证码*/
`OP_Userpassgo` int(11) NOT NULL default '0',
`OP_Coinset` varchar(255) NOT NULL default '1:1',
`OP_Withdrawal` varchar(255) NOT NULL default '',
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_user`;
CREATE TABLE `ourphp_user` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Useremail` varchar(255) NOT NULL default '',
`OP_Userpass` varchar(255) NOT NULL default '',
`OP_Username` varchar(255) NOT NULL default '',
`OP_Usertel` varchar(255) NOT NULL default '',
`OP_Userqq`  varchar(255) NOT NULL default '',
`OP_Userskype` varchar(255) NOT NULL default '',
`OP_Useraliww` varchar(255) NOT NULL default '',
`OP_Useradd` varchar(255) NOT NULL default '',
`OP_Userclass` int(11) NOT NULL default '0', /*会员级别*/
`OP_Usersource` varchar(255) NOT NULL default '',/*会员来源*/
`OP_Userhead` varchar(255) NOT NULL default '',/*会员头像*/
`OP_Usermoney` decimal(10,2) NOT NULL default '0', /*账户现金*/
`OP_Userintegral` decimal(10,2) NOT NULL default '0', /*账户积分*/
`OP_Userip` varchar(255) NOT NULL default '',/*会员ip地址*/
`OP_Userproblem` varchar(255) NOT NULL default '',/*会员找回密码时的问题*/
`OP_Useranswer` varchar(255) NOT NULL default '',/*会员找回密码时的答案*/
`OP_Userstatus` int(11) NOT NULL default '0', /*账户状态 1开启 2锁定*/
`OP_Usertext` text,/*人生宣言*/
`logintime` varchar(255) NOT NULL default '',/*登录时间*/
`OP_Usercode` varchar(255) NOT NULL default '',
`OP_Usercoin` decimal(20,8) NOT NULL default '0',
`time` datetime,
`OP_Userregcode` int(11) NOT NULL default '0',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_userproblem`;
CREATE TABLE `ourphp_userproblem` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Userproblem` varchar(255) NOT NULL default '',
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_userleve`;
CREATE TABLE `ourphp_userleve` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Userlevename` varchar(255) NOT NULL default '',
`OP_Userweight` int(11) NOT NULL default '0', /*用户组权重*/
`OP_Useropen` int(11) NOT NULL default '0',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_usermessage`;
CREATE TABLE `ourphp_usermessage` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Usersend` varchar(255) NOT NULL default '',
`OP_Usercollect` varchar(255) NOT NULL default '',
`OP_Usercontent` text,
`OP_Userclass` int(11) NOT NULL default '0',
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_userpay`;
CREATE TABLE `ourphp_userpay` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Useremail` varchar(255) NOT NULL default '',
`OP_Usermoney` decimal(10,2) NOT NULL default '0', /*账户现金*/
`OP_Userintegral` decimal(10,2) NOT NULL default '0', /*账户积分*/
`OP_Usercontent` text,
`OP_Useradmin` varchar(255) NOT NULL default '',
`OP_Uservoucherone` varchar(255) NOT NULL default '',
`OP_Uservouchertwo` varchar(255) NOT NULL default '',
`OP_Userpaytype` varchar(255) NOT NULL default '',
`OP_Usercoin` decimal(10,2) NOT NULL default '0',
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_book`;
CREATE TABLE `ourphp_book` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Bookcontent` text,
`OP_Bookname` varchar(255) NOT NULL default '',
`OP_Booktel` varchar(255) NOT NULL default '',
`OP_Bookip` varchar(255) NOT NULL default '',
`OP_Bookclass` int(11) NOT NULL default '0',
`OP_Booklang` varchar(255) NOT NULL default '',
`OP_Bookreply` text,
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_banner`;
CREATE TABLE `ourphp_banner` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Bannerimg` text,
`OP_Bannertitle` varchar(255) NOT NULL default '',
`OP_Bannerurl` varchar(255) NOT NULL default '',
`OP_Bannerlang` varchar(255) NOT NULL default '',
`time` datetime,
`OP_Bannerclass` int(11) NOT NULL default '0',
`OP_Bannertext` text,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_qq`;
CREATE TABLE `ourphp_qq` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_QQname` varchar(255) NOT NULL default '',
`OP_QQnumber` varchar(255) NOT NULL default '',
`OP_QQclass` varchar(255) NOT NULL default '',
`OP_QQsorting` int(11) NOT NULL default '0',
`OP_QQother` varchar(255) NOT NULL default '',
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_link`;
CREATE TABLE `ourphp_link` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Linkname` varchar(255) NOT NULL default '',
`OP_Linkurl` varchar(255) NOT NULL default '',
`OP_Linkclass` varchar(255) NOT NULL default '',
`OP_Linkimg` text,
`OP_Linksorting` int(11) NOT NULL default '0',
`OP_Linkstate` int(11) NOT NULL default '0', /*显示隐藏 1显示 2隐藏*/
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_ad`;
CREATE TABLE `ourphp_ad` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Adtitle` varchar(255) NOT NULL default '',
`OP_Adcontent` text,
`OP_Adclass` varchar(255) NOT NULL default '',
`OP_Adpiaofui` varchar(255) NOT NULL default '',
`OP_Adpiaofuu` text,
`OP_Adyouxiat` varchar(255) NOT NULL default '',
`OP_Adyouxiaf` text,
`OP_Adduilianli` varchar(255) NOT NULL default '',
`OP_Adduilianlu` text,
`OP_Adduilianri` varchar(255) NOT NULL default '',
`OP_Adduilianru` text,
`OP_Adstateo` int(11) NOT NULL default '0',/*显示隐藏 1显示 2隐藏*/
`OP_Adstatet` int(11) NOT NULL default '0',
`OP_Adstates` int(11) NOT NULL default '0',
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_watermark`;
CREATE TABLE `ourphp_watermark` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Watermarkimg` varchar(255) NOT NULL default '',
`OP_Watermarkfont` varchar(255) NOT NULL default '',
`OP_Watermarkcolor` varchar(255) NOT NULL default '',
`OP_Watermarksize` varchar(255) NOT NULL default '',
`OP_Watermarkposition` int(11) NOT NULL default '0',
`OP_Watermarkoff` int(11) NOT NULL default '0', /*1打开 2关闭*/
`OP_Imgcompress` varchar(255) NOT NULL default 'ourphp',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_temp`;
CREATE TABLE `ourphp_temp` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Temppath` varchar(255) NOT NULL default '',
`OP_Tempauthor` varchar(255) NOT NULL default '',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_lang`;
CREATE TABLE `ourphp_lang` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Lang` varchar(255) NOT NULL default '',
`OP_Font` varchar(255) NOT NULL default '',
`OP_Default` varchar(255) NOT NULL default '',
`OP_Note` varchar(255) NOT NULL default '',
`OP_Langtitle` varchar(255) NOT NULL default '',
`OP_Langkeywords` text,
`OP_Langdescription` text,
`OP_Webname` varchar(255) NOT NULL default '',
`OP_Webadd` varchar(255) NOT NULL default '',
`OP_Weblinkman` varchar(255) NOT NULL default '',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_adminclick`;
CREATE TABLE `ourphp_adminclick` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Title` varchar(255) NOT NULL default '',
`OP_Url` varchar(255) NOT NULL default '',
`OP_Click` int(11) NOT NULL default '0',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_mail`;
CREATE TABLE `ourphp_mail` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Mailsmtp` varchar(255) NOT NULL default '',
`OP_Mailport` int(11) NOT NULL default '0',
`OP_Mailusermail` varchar(255) NOT NULL default '',
`OP_Mailuser` varchar(255) NOT NULL default '',
`OP_Mailpass` varchar(255) NOT NULL default '',
`OP_Mailsubject` varchar(255) NOT NULL default '',
`OP_Mailcontent` text,
`OP_Mailtype` varchar(255) NOT NULL default '',
`OP_Mailtitle` varchar(255) NOT NULL default '', /*邮件类型*/
`OP_Mailclass` int(11) NOT NULL default '0', /*1开启 2关闭*/
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_column`;
CREATE TABLE `ourphp_column` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Uid` int(11) NOT NULL default '0',
`OP_Lang` varchar(255) NOT NULL default '',  /*语言类别*/
`OP_Columntitle` varchar(255) NOT NULL default '',  /*主标题*/
`OP_Columntitleto` varchar(255) NOT NULL default '', /*副标题*/
`OP_Model` varchar(255) NOT NULL default '', /*模型*/
`OP_Templist` varchar(255) NOT NULL default '', /*列表页模板*/
`OP_Tempview` varchar(255) NOT NULL default '', /*内容页模板*/
`OP_Url` varchar(255) NOT NULL default '', /*外部链接地址*/
`OP_About` text, /*单页面*/
`OP_About_wap` text, /*单页面*/
`OP_Hide` int(11) NOT NULL default '0', /*栏目隐藏与显示，0为显示 1为隐藏*/
`OP_Sorting` int(11) NOT NULL default '0', /*栏目排序*/
`OP_Briefing` text, /*栏目介绍*/
`OP_Img` varchar(255) NOT NULL default '', /*栏目图片*/
`OP_Userright` varchar(255) NOT NULL default '', /*栏目权限*/
`OP_Weight` int(11) NOT NULL default '0', /*栏目权重*/
`OP_Adminopen` text, /*管理权限*/
`OP_Total` int(11) NOT NULL default '0',
`OP_Click` int(11) NOT NULL default '0',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_product`;
CREATE TABLE `ourphp_product` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Class` int(11) NOT NULL default '0',
`OP_Lang` varchar(255) NOT NULL default '',  /*语言类别*/
`OP_Title` varchar(255) NOT NULL default '',  /*标题*/
`OP_Number` varchar(255) NOT NULL default '',  /*编号*/
`OP_Goodsno` varchar(255) NOT NULL default '',  /*货号*/
`OP_Brand` varchar(255) NOT NULL default '',  /*品牌*/
`OP_Market` decimal(10,2) NOT NULL default '0',  /*市场价*/
`OP_Webmarket` decimal(10,2) NOT NULL default '0',  /*本站价*/
`OP_Stock` int(11) NOT NULL default '0',  /*库存*/
`OP_Usermoney` text,  /*会员价格*/
`OP_Specificationsid` varchar(255) NOT NULL default '',  /*规格ID*/
`OP_Specificationstitle` text,  /*规格标题*/
`OP_Specifications` text,  /*产品规格*/
`OP_Pattribute` text,  /*产品属性*/
`OP_Minimg` varchar(255) NOT NULL default '',  /*缩略图*/
`OP_Maximg` varchar(255) NOT NULL default '',  /*大图*/
`OP_Img` text,  /*组图*/
`OP_Content` text,  /*内容*/
`OP_Content_wap` text,  /*内容*/
`OP_Down` int(11) NOT NULL default '0', /*下架 1下架 2不下架*/
`OP_Weight` int(11) NOT NULL default '1', /*重量*/
`OP_Freight` int(11) NOT NULL default '1', /*运费模板*/
`OP_Tag` varchar(255) NOT NULL default '', /*标签*/
`OP_Sorting` int(11) NOT NULL default '0', /*排序*/
`OP_Attribute` varchar(255) NOT NULL default '', /*属性*/
`OP_Url` varchar(255) NOT NULL default '',
`OP_Description` text, /*描述*/
`OP_Click` int(11) NOT NULL default '0', /*点击量*/
`time` datetime,
`OP_Integral` decimal(10,2) NOT NULL default '0', /*商品赠送积分 v1.2.2*/
`OP_Integralok` int(11) NOT NULL default '0', /*是否允许用积分对换 0=否 1=是*/
`OP_Integralexchange` decimal(10,2) NOT NULL default '0', /*兑换积分*/
`OP_Suggest` varchar(255) NOT NULL default '', /*一句话介绍*/
`OP_Productimgname` varchar(255) NOT NULL default '',
`OP_Usermoneyclass` int(11) NOT NULL default '1',
`OP_Tuanset` int(11) NOT NULL default '1',
`OP_Tuanusernum` int(11) NOT NULL default '0',
`OP_Tuantime` varchar(255) NOT NULL default '',
`OP_Tuannumber` int(11) NOT NULL default '0',
`OP_Buynum` int(11) NOT NULL default '0',
`OP_Couponset` varchar(255) NOT NULL default '0',
`OP_Buyoffnum` int(11) NOT NULL default '0',
`OP_Buytotalnum` int(11) NOT NULL default '0',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_productcp`;
CREATE TABLE `ourphp_productcp` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Vender` varchar(255) NOT NULL default '', 
`OP_Brand` varchar(255) NOT NULL default '', 
`OP_Class` int(11) NOT NULL default '0', /*1厂商 2品牌*/
`OP_Img` varchar(255) NOT NULL default '',  /*品牌图片*/
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_productattribute`;
CREATE TABLE `ourphp_productattribute` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Title` varchar(255) NOT NULL default '', 
`OP_Class` varchar(255) NOT NULL default '', 
`OP_Text` text,
`OP_Sorting` int(11) NOT NULL default '0', /*排序*/
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_productspecifications`;
CREATE TABLE `ourphp_productspecifications` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Title` varchar(255) NOT NULL default '', 
`OP_Titleto` varchar(255) NOT NULL default '',
`OP_Value` text, /*值*/
`OP_Class` int(11) NOT NULL default '0', /*1文字 2图片*/
`OP_Img` varchar(255) NOT NULL default '', 
`OP_Sorting` int(11) NOT NULL default '0', /*排序*/
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_productset`;
CREATE TABLE `ourphp_productset` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Pattern` int(11) NOT NULL default '0', /*1产品展示模式 2商城模式*/
`OP_Scheme` int(11) NOT NULL default '0', /*1统一价格 2详细价格*/
`OP_Stock` int(11) NOT NULL default '0', /*库存数量报警*/
`OP_buy` int(11) NOT NULL default '0', /*游客是否可以提交订单 1可以 2不可以*/
`OP_Sendout` text, /*发货方式*/
`time` datetime,
`OP_Delivery` int(11) NOT NULL default '0', /*货到付款 0=NO 1=YES*/
`OP_Userbuysms` int(11) NOT NULL default '0',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_webdeploy`;
CREATE TABLE `ourphp_webdeploy` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Weboff` int(11) NOT NULL default '0', /*开站开关 1等于开 2关*/
`OP_Webofftext` text,
`OP_Webrewrite` int(11) NOT NULL default '0', /*伪静态开关 1等于开 2关*/
`OP_Webpage` text, /*翻页*/
`OP_Webkeywords` int(11) NOT NULL default '0', /*关键词优化*/
`OP_Webkeywordsto` text,
`OP_Webdescriptions` text,
`OP_Webfenci` int(11) NOT NULL default '0', /*分词开关 1等于开 2关*/
`OP_Webservice` varchar(255) NOT NULL default '',
`OP_Webocomment` int(11) NOT NULL default '2', /*其它评论开关 1等于开 2关 3登录可以评论*/
`OP_Webpcomment` int(11) NOT NULL default '2', /*商品评论开关 1等于开 2关  3登录可以评论 4只有购买者可以评论*/
`OP_Webweight` int(11) NOT NULL default '1', /*权限方式 1权限 2权重*/
`time` datetime,
`OP_Webfile` int(11) NOT NULL default '1', /*删除附件 1不删 2删*/
`OP_Ucenter` int(11) NOT NULL default '0', 
`OP_Searchtime` int(11) NOT NULL default '10',
`OP_Home` varchar(255) NOT NULL default 'cn|cn|cn', /*网站默认语言*/
`OP_Sensitive` text, /*网站过滤敏感词*/
`OP_Bookuser` int(11) NOT NULL default '0', 
`OP_Adminrecord` text,
`OP_Pagestype` int(11) NOT NULL default '0',
`OP_Pages` text NOT NULL,
`OP_Pagefont` varchar(255) NOT NULL default '',
`OP_Login` text,
`OP_Empower` text,
`OP_Empowerlist` text,
`OP_Empowerright` text,
`OP_Wapurl` varchar(255) NOT NULL default '',
`OP_Webupdate` int(11) NOT NULL default '0',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_orders`;
CREATE TABLE `ourphp_orders` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Ordersname` text, 
`OP_Ordersid` int(11) NOT NULL default '0', 
`OP_Ordersnum` int(11) NOT NULL default '0', 
`OP_Ordersemail` varchar(255) NOT NULL default '', 
`OP_Ordersusername` varchar(255) NOT NULL default '', 
`OP_Ordersusertel` varchar(255) NOT NULL default '', 
`OP_Ordersuseradd` text, 
`OP_Ordersusetext` text, 
`OP_Ordersproductatt` text, 
`OP_Orderswebmarket` decimal(10,2) NOT NULL default '0',
`OP_Ordersusermarket` decimal(10,2) NOT NULL default '0',
`OP_Ordersweight` int(11) NOT NULL default '1', /*重量*/
`OP_Ordersfreight` int(11) NOT NULL default '1', /*运费价格*/
`OP_Ordersexpress` varchar(255) NOT NULL default '', 
`OP_Ordersexpressnum` varchar(255) NOT NULL default '', 
`time` datetime,
`OP_Ordersnumber` varchar(255) NOT NULL default '', 
`OP_Orderspay` int(11) NOT NULL default '1',  /*1 未付款 2已付款*/
`OP_Orderssend` int(11) NOT NULL default '1',  /*1 未发货 2已发货*/
`OP_Ordersgotime` varchar(255) NOT NULL default '2015-01-01 12:00:00',
`OP_Integralok` int(11) NOT NULL default '0', /*是否允许用积分对换 0=普通商品 1=积分兑换*/
`OP_Sign` int(11) NOT NULL default '0', /*是否签收*/
`OP_Ordersclassid` int(11) NOT NULL default '0',
`OP_Ordersadminoper` int(11) NOT NULL default '0',
`OP_Usermoneyback` int(11) NOT NULL default '1',
`OP_Tuanset` int(11) NOT NULL default '1',
`OP_Tuanid` int(11) NOT NULL default '0',
`OP_Ordersimg` varchar(255) NOT NULL default '', /*产品图片*/
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_shoppingcart`;
CREATE TABLE `ourphp_shoppingcart` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Shopproductid` int(11) NOT NULL default '0', 
`OP_Shopnum` int(11) NOT NULL default '0', 
`OP_Shopusername` varchar(255) NOT NULL default '', 
`OP_Shopatt` text, 
`OP_Shopkc` varchar(255) NOT NULL default '', 
`OP_Shophh` varchar(255) NOT NULL default '', 
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_api`;
CREATE TABLE `ourphp_api` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Key` text,  
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_freight`;
CREATE TABLE `ourphp_freight` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Freightname` varchar(255) NOT NULL default '', 
`OP_Freighttext` text,  
`OP_Freightdefault` int(11) NOT NULL default '0', 
`OP_Freightweight` int(11) NOT NULL default '0', 
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_search`;
CREATE TABLE `ourphp_search` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Searchtext` text,  
`OP_Searchclick` int(11) NOT NULL default '0', 
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_comment`;
CREATE TABLE `ourphp_comment` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Content` text,  /*内容*/
`OP_Class` int(11) NOT NULL default '0', /*分类*/
`OP_Type` varchar(255) NOT NULL default '', /*类别*/
`OP_Name` varchar(255) NOT NULL default '', /*姓名*/
`OP_Ip` varchar(255) NOT NULL default '', /*IP*/
`OP_Vote` int(11) NOT NULL default '0', /*好评*/
`OP_Scoring` varchar(255) NOT NULL default '', /*打分*/
`OP_Gocontent` text,  /*回复*/
`OP_Gotime` varchar(255) NOT NULL default '', /*回复时间*/
`time` datetime,
`OP_Usernick` varchar(255) NOT NULL default '',
`OP_Userimg` varchar(255) NOT NULL default '',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_plus`;
CREATE TABLE `ourphp_plus` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Name` varchar(255) NOT NULL default '', /*插件名称*/
`OP_Version` varchar(255) NOT NULL default '', /*插件版本*/
`OP_Versiondate` varchar(255) NOT NULL default '', /*更新日期*/
`OP_Author` varchar(255) NOT NULL default '', /*插件作者*/
`OP_Fraction` varchar(255) NOT NULL default '', /*分数*/
`OP_About` text, /*插件介绍*/
`OP_Pluspath` text, /*管理路径*/
`OP_Time` date, /*安装日期*/
`OP_Off` int(11) NOT NULL default '1', /* 1关闭 2开启*/
`OP_Plugid` varchar(255) NOT NULL default '', /* 插件ID*/
`OP_Plugclass` varchar(255) NOT NULL default '', /* 插件类型*/
`OP_Plugmysql` varchar(255) NOT NULL default '', /* 插件数据表*/
`OP_Plugadminid` int(11) NOT NULL default '0',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_integral`;
CREATE TABLE `ourphp_integral` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Iid` int(11) NOT NULL default '0', /* 产品ID*/
`OP_Iname` varchar(255) NOT NULL default '', /*产品名称*/
`OP_Imarket` decimal(10,2) NOT NULL default '0',/*产品价格*/
`OP_Iwebmarket` decimal(10,2) NOT NULL default '0',/*本站价格*/
`OP_Iintegral` decimal(10,2) NOT NULL default '0', /*积分*/
`OP_Ivirtual` int(11) NOT NULL default '0', /*虚拟实物 0=实物 1=虚拟*/
`OP_Iconfirm` int(11) NOT NULL default '0', /*确认领取 0=未领 1=领取*/
`OP_Iuseremail` varchar(255) NOT NULL default '', /*会员账号*/
`OP_Iadmin` int(11) NOT NULL default '0', /*管理权限 0=会员 1=管理*/
`OP_ITime` varchar(255) NOT NULL default '', /*领取时间*/
`time` datetime, /*产生时间*/
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_usershopadd`;
CREATE TABLE `ourphp_usershopadd` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Add` varchar(255) NOT NULL default '', /*地址*/
`OP_Addtel` varchar(255) NOT NULL default '', /*电话*/
`OP_Addname` varchar(255) NOT NULL default '', /*姓名*/
`OP_Adduser` varchar(255) NOT NULL default '', /*归属*/
`OP_Addindex` int(11) NOT NULL default '0', /*默认*/
`OP_Maplat` varchar(255) NOT NULL default '',
`OP_Maplng` varchar(255) NOT NULL default '',
`time` datetime, /*日期*/
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_coupon`;
CREATE TABLE `ourphp_coupon` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Title` varchar(255) NOT NULL default '', /*优惠券标题*/
`OP_Money` decimal(10,2) NOT NULL default '0', /*优惠券金额*/
`OP_Timewin` varchar(255) NOT NULL default '2015-01-01 12:00:00',  /*时间限制*/
`OP_Moneygo` decimal(10,2) NOT NULL default '0',  /*满金额使用*/
`OP_Content` text, /*描述*/
`OP_Type` int(11) NOT NULL default '0', /*类型 0=全部领取 1=向指定用户发放*/
`OP_Md` varchar(255) NOT NULL default '', /*MD5校验值*/
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_couponlist`;
CREATE TABLE `ourphp_couponlist` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Couponid` int(11) NOT NULL default '0', /*优惠券ID*/
`OP_Username` varchar(255) NOT NULL default '', /*领取的会员账户*/
`OP_Type` int(11) NOT NULL default '0', /*使用 0=未用 1=已用*/
`OP_Timewin` varchar(255) NOT NULL default '2015-01-01 12:00:00',  /*时间限制*/
`OP_Moneygo` decimal(10,2) NOT NULL default '0',  /*满金额使用*/
`OP_Md` varchar(255) NOT NULL default '', /*MD5校验值*/
`OP_Time` varchar(255) NOT NULL default '2015-01-01 12:00:00',  /*使用时间*/
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_orderslist`;
CREATE TABLE `ourphp_orderslist` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Ordersnum` varchar(255) NOT NULL default '', /*统一订单号*/
`OP_Ordersid` varchar(255) NOT NULL default '', /*订单ID集合*/
`OP_Ordersusername` varchar(255) NOT NULL default '', /*收件人姓名*/
`OP_Ordersusertel` varchar(255) NOT NULL default '', /*收件人电话*/
`OP_Ordersuseradd` varchar(255) NOT NULL default '', /*收件人地址*/
`OP_Orderscouponmoney` decimal(10,2) NOT NULL default '0',  /*优惠券金额*/
`OP_Orderscouponid` int(11) NOT NULL default '0', /*优惠券ID*/
`OP_Ordersuser` varchar(255) NOT NULL default '', /*订单归属者*/
`OP_Ordersimg` varchar(255) NOT NULL default '', /*产品图片*/
`OP_Look` int(11) NOT NULL default '0', /*已读订单*/
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_adminnav`;
CREATE TABLE `ourphp_adminnav` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Title` varchar(255) NOT NULL default '',
`OP_Soft` int(11) NOT NULL default '0',
`OP_Ourphp` int(11) NOT NULL default '0',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_adminnavlist`;
CREATE TABLE `ourphp_adminnavlist` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Title` varchar(255) NOT NULL default '',
`OP_Path` varchar(255) NOT NULL default '',
`OP_Soft` int(11) NOT NULL default '0',
`OP_Uid` int(11) NOT NULL default '0',
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_userregreward`;
CREATE TABLE `ourphp_userregreward` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Useremail` varchar(255) NOT NULL default '',
`OP_Useremailto` varchar(255) NOT NULL default '',
`OP_Usermoney` decimal(10,2) NOT NULL default '0', /*奖励现金*/
`OP_Userintegral` decimal(10,2) NOT NULL default '0', /*奖励积分*/
`OP_Userid` int(11) NOT NULL default '0', /*被推荐人ID*/
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_usermoneyback`;
CREATE TABLE `ourphp_usermoneyback` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Useremail` varchar(255) NOT NULL default '', /*退款账户*/
`OP_Orderid` int(11) NOT NULL default '0', /*退款订单ID*/
`OP_Userposnum` varchar(255) NOT NULL default '',
`OP_Userposname` varchar(255) NOT NULL default '',
`OP_Admintime` varchar(255) NOT NULL default '',
`OP_Adminname` varchar(255) NOT NULL default '',
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_usermoneyout`;
CREATE TABLE `ourphp_usermoneyout` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Useremail` varchar(255) NOT NULL default '', /*提现人*/
`OP_Useroutmoney` decimal(10,2) NOT NULL default '0', /*提现金额*/
`OP_Type` int(11) NOT NULL default '0',
`OP_User` varchar(255) NOT NULL default '',
`OP_Usertime` varchar(255) NOT NULL default '',
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_tuan`;
CREATE TABLE `ourphp_tuan` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Tuanpid` int(11) NOT NULL default '0',
`OP_Tuanoid` int(11) NOT NULL default '0',
`OP_Tuanuser` varchar(255) NOT NULL default '',
`OP_Tuannum` int(11) NOT NULL default '0',
`OP_Tuanznum` int(11) NOT NULL default '0',
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_tuanuserlist`;
CREATE TABLE `ourphp_tuanuserlist` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Tuanid` int(11) NOT NULL default '0',
`OP_Tuanpid` int(11) NOT NULL default '0',
`OP_Tuanoid` int(11) NOT NULL default '0',
`OP_Tuanuser` varchar(255) NOT NULL default '',
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ourphp_logs`;
CREATE TABLE `ourphp_logs` (
`id` int(10) unsigned NOT NULL auto_increment,
`OP_Logscontent` varchar(255) NOT NULL default '',
`OP_Logsaccount` varchar(255) NOT NULL default '',
`OP_Logsip` varchar(255) NOT NULL default '',
`time` datetime,
PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*索引*/
ALTER TABLE  `ourphp_article` ADD INDEX (`OP_Class`);
ALTER TABLE  `ourphp_article` ADD INDEX (`OP_Callback`);
ALTER TABLE  `ourphp_article` ADD INDEX (`OP_Lang`);
ALTER TABLE  `ourphp_article` ADD INDEX (`OP_Attribute`);
ALTER TABLE  `ourphp_article` ADD INDEX (`OP_Articletitle`);
ALTER TABLE  `ourphp_article` ADD INDEX (`OP_Sorting`);
ALTER TABLE  `ourphp_product` ADD INDEX (`OP_Class`);
ALTER TABLE  `ourphp_product` ADD INDEX (`OP_Down`);
ALTER TABLE  `ourphp_product` ADD INDEX (`OP_Lang`);
ALTER TABLE  `ourphp_product` ADD INDEX (`OP_Attribute`);
ALTER TABLE  `ourphp_product` ADD INDEX (`OP_Brand`);
ALTER TABLE  `ourphp_product` ADD INDEX (`OP_Sorting`);
ALTER TABLE  `ourphp_down` ADD INDEX (`OP_Class`);
ALTER TABLE  `ourphp_down` ADD INDEX (`OP_Callback`);
ALTER TABLE  `ourphp_down` ADD INDEX (`OP_Lang`);
ALTER TABLE  `ourphp_down` ADD INDEX (`OP_Attribute`);
ALTER TABLE  `ourphp_down` ADD INDEX (`OP_Downtitle`);
ALTER TABLE  `ourphp_down` ADD INDEX (`OP_Sorting`);
ALTER TABLE  `ourphp_job` ADD INDEX (`OP_Class`);
ALTER TABLE  `ourphp_job` ADD INDEX (`OP_Callback`);
ALTER TABLE  `ourphp_job` ADD INDEX (`OP_Lang`);
ALTER TABLE  `ourphp_job` ADD INDEX (`OP_Attribute`);
ALTER TABLE  `ourphp_job` ADD INDEX (`OP_Jobtitle`);
ALTER TABLE  `ourphp_job` ADD INDEX (`OP_Sorting`);
ALTER TABLE  `ourphp_photo` ADD INDEX (`OP_Class`);
ALTER TABLE  `ourphp_photo` ADD INDEX (`OP_Callback`);
ALTER TABLE  `ourphp_photo` ADD INDEX (`OP_Lang`);
ALTER TABLE  `ourphp_photo` ADD INDEX (`OP_Attribute`);
ALTER TABLE  `ourphp_photo` ADD INDEX (`OP_Phototitle`);
ALTER TABLE  `ourphp_photo` ADD INDEX (`OP_Sorting`);
ALTER TABLE  `ourphp_video` ADD INDEX (`OP_Class`);
ALTER TABLE  `ourphp_video` ADD INDEX (`OP_Callback`);
ALTER TABLE  `ourphp_video` ADD INDEX (`OP_Lang`);
ALTER TABLE  `ourphp_video` ADD INDEX (`OP_Attribute`);
ALTER TABLE  `ourphp_video` ADD INDEX (`OP_Videotitle`);
ALTER TABLE  `ourphp_video` ADD INDEX (`OP_Sorting`);
ALTER TABLE  `ourphp_column` ADD INDEX (`OP_Hide`);
ALTER TABLE  `ourphp_column` ADD INDEX (`OP_Lang`);
ALTER TABLE  `ourphp_column` ADD INDEX (`OP_Uid`);
ALTER TABLE  `ourphp_column` ADD INDEX (`OP_Sorting`);
ALTER TABLE  `ourphp_book` ADD INDEX (`OP_Bookclass`);
ALTER TABLE  `ourphp_book` ADD INDEX (`OP_Booklang`);
ALTER TABLE  `ourphp_user` ADD INDEX (`OP_Useremail`);
ALTER TABLE  `ourphp_user` ADD INDEX (`OP_Userpass`);
ALTER TABLE  `ourphp_user` ADD INDEX (`OP_Usertel`);
ALTER TABLE  `ourphp_user` ADD INDEX (`OP_Userstatus`);
ALTER TABLE  `ourphp_user` ADD INDEX (`OP_Usercode`);
ALTER TABLE  `ourphp_usermessage` ADD INDEX (`OP_Usersend`);
ALTER TABLE  `ourphp_usermessage` ADD INDEX (`OP_Usercollect`);
ALTER TABLE  `ourphp_orders` ADD INDEX (`OP_Ordersid`);
ALTER TABLE  `ourphp_orders` ADD INDEX (`OP_Ordersemail`);
ALTER TABLE  `ourphp_orders` ADD INDEX (`OP_Ordersnumber`);
ALTER TABLE  `ourphp_orders` ADD INDEX (`OP_Orderspay`);
ALTER TABLE  `ourphp_orders` ADD INDEX (`OP_Orderssend`);
ALTER TABLE  `ourphp_orders` ADD INDEX (`OP_Sign`);
ALTER TABLE  `ourphp_orders` ADD INDEX (`OP_Ordersclassid`);
ALTER TABLE  `ourphp_orders` ADD INDEX (`OP_Ordersadminoper`);
ALTER TABLE  `ourphp_orders` ADD INDEX (`OP_Ordersimg`);
ALTER TABLE  `ourphp_orderslist` ADD INDEX (`OP_Look`);
ALTER TABLE  `ourphp_orderslist` ADD INDEX (`OP_Ordersimg`);
ALTER TABLE  `ourphp_adminnav` ADD INDEX (`OP_Soft`);
ALTER TABLE  `ourphp_adminnav` ADD INDEX (`OP_Ourphp`);
ALTER TABLE  `ourphp_adminnavlist` ADD INDEX (`OP_Soft`);
ALTER TABLE  `ourphp_adminnavlist` ADD INDEX (`OP_Uid`);
ALTER TABLE  `ourphp_tuan` ADD INDEX (`OP_Tuanpid`);
ALTER TABLE  `ourphp_tuan` ADD INDEX (`OP_Tuanoid`);
ALTER TABLE  `ourphp_tuan` ADD INDEX (`OP_Tuanuser`);
ALTER TABLE  `ourphp_tuanuserlist` ADD INDEX (`OP_Tuanid`);
ALTER TABLE  `ourphp_tuanuserlist` ADD INDEX (`OP_Tuanpid`);
ALTER TABLE  `ourphp_tuanuserlist` ADD INDEX (`OP_Tuanoid`);
ALTER TABLE  `ourphp_tuanuserlist` ADD INDEX (`OP_Tuanuser`);
ALTER TABLE  `ourphp_logs` ADD INDEX (`OP_Logsaccount`);
ALTER TABLE  `ourphp_web` ADD INDEX (`id`);
ALTER TABLE  `ourphp_web` ADD INDEX (`id`);
ALTER TABLE  `ourphp_wap` ADD INDEX (`id`);
ALTER TABLE  `ourphp_admin` ADD INDEX (`id`);
ALTER TABLE  `ourphp_article` ADD INDEX (`id`);
ALTER TABLE  `ourphp_photo` ADD INDEX (`id`);
ALTER TABLE  `ourphp_video` ADD INDEX (`id`);
ALTER TABLE  `ourphp_down` ADD INDEX (`id`);
ALTER TABLE  `ourphp_job` ADD INDEX (`id`);
ALTER TABLE  `ourphp_booksection` ADD INDEX (`id`);
ALTER TABLE  `ourphp_usercontrol` ADD INDEX (`id`);
ALTER TABLE  `ourphp_user` ADD INDEX (`id`);
ALTER TABLE  `ourphp_userproblem` ADD INDEX (`id`);
ALTER TABLE  `ourphp_userleve` ADD INDEX (`id`);
ALTER TABLE  `ourphp_usermessage` ADD INDEX (`id`);
ALTER TABLE  `ourphp_userpay` ADD INDEX (`id`);
ALTER TABLE  `ourphp_book` ADD INDEX (`id`);
ALTER TABLE  `ourphp_banner` ADD INDEX (`id`);
ALTER TABLE  `ourphp_qq` ADD INDEX (`id`);
ALTER TABLE  `ourphp_link` ADD INDEX (`id`);
ALTER TABLE  `ourphp_ad` ADD INDEX (`id`);
ALTER TABLE  `ourphp_watermark` ADD INDEX (`id`);
ALTER TABLE  `ourphp_temp` ADD INDEX (`id`);
ALTER TABLE  `ourphp_lang` ADD INDEX (`id`);
ALTER TABLE  `ourphp_adminclick` ADD INDEX (`id`);
ALTER TABLE  `ourphp_mail` ADD INDEX (`id`);
ALTER TABLE  `ourphp_column` ADD INDEX (`id`);
ALTER TABLE  `ourphp_product` ADD INDEX (`id`);
ALTER TABLE  `ourphp_productcp` ADD INDEX (`id`);
ALTER TABLE  `ourphp_productattribute` ADD INDEX (`id`);
ALTER TABLE  `ourphp_productspecifications` ADD INDEX (`id`);
ALTER TABLE  `ourphp_productset` ADD INDEX (`id`);
ALTER TABLE  `ourphp_webdeploy` ADD INDEX (`id`);
ALTER TABLE  `ourphp_orders` ADD INDEX (`id`);
ALTER TABLE  `ourphp_shoppingcart` ADD INDEX (`id`);
ALTER TABLE  `ourphp_api` ADD INDEX (`id`);
ALTER TABLE  `ourphp_freight` ADD INDEX (`id`);
ALTER TABLE  `ourphp_search` ADD INDEX (`id`);
ALTER TABLE  `ourphp_comment` ADD INDEX (`id`);
ALTER TABLE  `ourphp_plus` ADD INDEX (`id`);
ALTER TABLE  `ourphp_integral` ADD INDEX (`id`);
ALTER TABLE  `ourphp_usershopadd` ADD INDEX (`id`);
ALTER TABLE  `ourphp_coupon` ADD INDEX (`id`);
ALTER TABLE  `ourphp_couponlist` ADD INDEX (`id`);
ALTER TABLE  `ourphp_orderslist` ADD INDEX (`id`);
ALTER TABLE  `ourphp_adminnav` ADD INDEX (`id`);
ALTER TABLE  `ourphp_adminnavlist` ADD INDEX (`id`);
ALTER TABLE  `ourphp_userregreward` ADD INDEX (`id`);
ALTER TABLE  `ourphp_usermoneyback` ADD INDEX (`id`);
ALTER TABLE  `ourphp_usermoneyout` ADD INDEX (`id`);
ALTER TABLE  `ourphp_tuan` ADD INDEX (`id`);
ALTER TABLE  `ourphp_tuanuserlist` ADD INDEX (`id`);
ALTER TABLE  `ourphp_logs` ADD INDEX (`id`);




INSERT INTO `ourphp_temp` VALUES('1','default','ourphp!');
INSERT INTO `ourphp_wap` VALUES('1','我的手机网站','function/uploadfile/ourphp888/logo.png','','','yes');
INSERT INTO `ourphp_usercontrol` VALUES('1','1','1','欢迎您注册成为[.$ourphp_web.website.] 用户！','1','0|0|0|0','2','email','0','0','1:1','10','2015-1-1 12:00:00');
INSERT INTO `ourphp_userproblem` VALUES('1','妈妈的姓名?','2015-1-1 12:00:00'),('2','爸爸的姓名?','2015-1-1 12:00:00'),('3','老婆的姓名?','2015-1-1 12:00:00'),('4','家乡在哪里?','2015-1-1 12:00:00'),('5','大学名称是什么?','2015-1-1 12:00:00'),('6','老婆的生日?','2015-1-1 12:00:00'),('7','自已的生日?','2015-1-1 12:00:00');
INSERT INTO `ourphp_userleve` VALUES('1','普通会员','10',0),('2','银牌会员','20',0),('3','金牌会员','30',0),('4','分销商','40',0),('5','代理商','50',0);
INSERT INTO `ourphp_ad` VALUES('1','全站顶部','','','../../skin/headerbanner.gif','','','','','','','','2','2','2','2015-1-1 12:00:00'),('2','全站底部','','','../../skin/footerbanner.gif','','','','','','','','2','2','2','2015-1-1 12:00:00'),('3','信息列表页','','','../../skin/threadlist.gif','','','','','','','','2','2','2','2015-1-1 12:00:00'),('4','信息内容页','','','../../skin/article.gif','','','','','','','','2','2','2','2015-1-1 12:00:00'),('5','特殊广告','','','','','','','','','','','2','2','2','2015-1-1 12:00:00'),('6','会员中心登录左侧广告位','','','../../skin/userrightad.gif','','','','','','','','2','2','2','2015-1-1 12:00:00'),('7','移动端弹出','','','../../skin/userrightad.gif','','','','','','','','2','2','2','2015-1-1 12:00:00'),('8','倒计时广告','','','../../skin/userrightad.gif','','','','','5','','','2','2','2','2015-1-1 12:00:00');
INSERT INTO `ourphp_watermark` VALUES('1','watermark.png','www.ourphp.net','#000000','5','9','2','ourphp');
INSERT INTO `ourphp_lang` VALUES ('1','cn','中文','Default','中文语言唯一标识','','','','','','');
INSERT INTO `ourphp_mail` VALUES('1','smtp.qq.com','25','993141000@qq.com','993141000','123456','这是一封测试邮件','测试内容','HTML','注册会员邮件提醒','2'),('2','smtp.qq.com','25','993141000@qq.com','993141000','123456','这是一封测试邮件','测试内容','HTML','提交订单邮件提醒','2'),('3','smtp.qq.com','25','993141000@qq.com','993141000','123456','这是一封测试邮件','测试内容','HTML','后台发货邮件提醒','2'),('4','smtp.qq.com','25','993141000@qq.com','993141000','123456','您的会员注册验证码','验证码：#opcms#code#','HTML','用户注册邮件验证码','2');
INSERT INTO `ourphp_productset` VALUES('1','2','1','10','2','','2015-1-1 12:00:00','0','0');
INSERT INTO `ourphp_webdeploy` VALUES('1','1','网站公告请在后台修改','2','20,20,20,20,20,20,20','1','OURPHP','OURPHP','2','default','2','4','1','2015-1-1 12:00:00','1','0','10','cn|cn|cn','傻逼|二货|狗屎|去死','0','暂无建站备忘事项!','0','css','cn{上一页|下一页|页码}','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','','0');
INSERT INTO `ourphp_column` (`id`, `OP_Uid`, `OP_Lang`, `OP_Columntitle`, `OP_Columntitleto`, `OP_Model`, `OP_Templist`, `OP_Tempview`, `OP_Url`, `OP_About`, `OP_Hide`, `OP_Sorting`, `OP_Briefing`, `OP_Img`, `OP_Userright`, `OP_Weight`, `OP_Adminopen`, `OP_Total`) VALUES (1, 0, 'cn', '网站首页', '', 'weburl', '', '', '/', '', '0', '0', '', '', '0', '0', '00,01', '0');
INSERT INTO `ourphp_api` (`id`, `OP_Key`) VALUES(1, '阿里云快递接口|2|key值'),(2, '支付宝服务窗API接口|2|0|0'),(3, '支付宝[即时到账]接口|2|0|0|0'),(4, '银联[网银支付]接口|2|0|0|0'),(5, '第三方微信平台登录接口|2|0|0|链接地址'),(6, '手机短信API接口|2|0|0|sendsms|regsms'),(7, 'QQ登录API接口|2|0|0'),(8, '支付宝[移动支付]接口|2|0|0'),(9, '电脑端微信扫码支付|2|0|0|0|0'),(10,'微信手机端H5支付接口|2|0|0|0|0'),(11,'微信公众号登陆|2|0|0|0|0'),(12,'地图API接口|2|0|0|0');
INSERT INTO `ourphp_adminnav` (`id`, `OP_Title`, `OP_Soft`,`OP_Ourphp`) VALUES(1, '全局',2,1),(2, '内容',3,1),(3, '商品',4,1),(4, '用户',5,1),(5, '运营',6,1),(6, 'SEO优化',7,1),(7, '移动端',8,1),(8, '工具',9,1),(9, '财务',10,1);
INSERT INTO `ourphp_adminnavlist` (`id`, `OP_Title`, `OP_Path`, `OP_Soft`, `OP_Uid`) VALUES
(1, '全局','ourphp',1,1),
(2, '网站语言配置','/ourphp_lang.php?id=ourphp',2,1),
(3, '网站功能管理','/ourphp_webdeploy.php?id=ourphp',3,1),
(4, 'API接口管理','/ourphp_api.php?id=ourphp',4,1),
(5, '图像水印及压缩','/ourphp_watermark.php?id=ourphp',5,1),
(6, '系统邮件设置','/ourphp_mail.php?id=ourphp',6,1),
(7, '界面','ourphp',7,1),
(8, '翻页按钮样式','/ourphp_templatepages.php?id=ourphp',8,1),
(9, '模板安装使用','/ourphp_template.php?id=ourphp',9,1),
(10, '在线编辑模板','/ourphp_filebox.php',10,1),
(11, '模板标签','/tags.php',11,1),
(12, '全站','ourphp',1,2),
(13, '网站栏目管理','/ourphp_column.php?id=ourphp',2,2),
(14, '基本信息设置','/ourphp_web.php',3,2),
(15, '留言板管理','/ourphp_book.php?id=ourphp',4,2),
(16, 'Banner管理','/ourphp_banner.php?id=ourphp',5,2),
(17, '浮动客服管理','/ourphp_qq.php?id=ourphp',6,2),
(18, '评论管理','/ourphp_comment.php?opcms=articleview',7,2),
(19, '查看回收站','/ourphp_callback.php?opcms=article',8,2),
(20, '内容管理','ourphp',9,2),
(21, '文章管理','/ourphp_article.php?id=ourphp&aid=0',10,2),
(22, '图集管理','/ourphp_photo.php?id=ourphp&aid=0',11,2),
(23, '视频管理','/ourphp_video.php?id=ourphp&aid=0',12,2),
(24, '下载管理','/ourphp_down.php?id=ourphp&aid=0',13,2),
(25, '招聘管理','/ourphp_job.php?id=ourphp&aid=0',14,2),
(26, '商品管理','ourphp',1,3),
(27, '商品列表','/ourphp_productlist.php?id=ourphp&aid=0',2,3),
(28, '发布商品','/ourphp_product.php?id=ourphp',3,3),
(29, '商城设置','/ourphp_productset.php?id=ourphp',4,3),
(30, '订单处理','/ourphp_orders.php?id=ourphp',5,3),
(31, '仓库管理','/ourphp_productlibrary.php?id=ourphp',6,3),
(32, '商品规格管理','/ourphp_productspecifications.php?id=ourphp',7,3),
(33, '商品属性参数','/ourphp_productattribute.php?id=ourphp',8,3),
(34, '品牌管理','/ourphp_productp.php?id=ourphp',9,3),
(35, '运费模板','/ourphp_freight.php?id=ourphp',10,3),
(36, '商品评论管理','/ourphp_comment.php?opcms=productview',1,3),
(37, '优惠','ourphp',11,3),
(38, '积分领取管理','/ourphp_integral.php?opcms=ourphp',12,3),
(39, '优惠券管理','/ourphp_coupon.php?opcms=ourphp',13,3),
(40, '用户','ourphp',1,4),
(41, '会员选项','/ourphp_usercontrol.php?id=ourphp',2,4),
(42, '会员管理','/ourphp_user.php?id=ourphp',3,4),
(43, '用户组管理','/ourphp_usergroup.php?id=ourphp',4,4),
(44, '站内邮件','/ourphp_usermessage.php?id=ourphp',5,4),
(45, '会员充值','/ourphp_pay.php?id=ourphp',6,4),
(46, '管理员','ourphp',7,4),
(47, '管理员角色','/ourphp_manage.php?id=ourphp',8,4),
(48, '运营','ourphp',1,5),
(49, '广告管理','/ourphp_ad.php?id=ourphp',2,5),
(50, '友情链接管理','/ourphp_link.php?id=ourphp',3,5),
(51, '网站流量统计','/ourphp_statistics.php?id=ourphp',4,5),
(52, '扩展','ourphp',5,5),
(53, '插件管理','/ourphp_plug.php?id=ourphp',6,5),
(54, '应用市场','/ourphp_app.php?id=ourphp',7,5),
(55, '优化管理','ourphp',1,6),
(56, '网站相关优化','/ourphp_webseo.php?id=ourphp',2,6),
(57, '用户搜索词查看','/ourphp_usersearch.php?id=ourphp',3,6),
(58, '推广网站','/plugs/seo.html',4,6),
(59, '手机网站','ourphp',1,7),
(60, '手机网站设置','/ourphp_wap.php?id=ourphp',2,7),
(61, '第三方微信平台','/ourphp_weixin.php',3,7),
(62, '第三方小程序平台','/ourphp_alipay.php',4,7),
(63, '工具','ourphp',1,8),
(64, '数据库操作','/ourphp_bak.php?id=ourphp',2,8),
(65, '执行SQL语句','/ourphp_sql.php?id=ourphp',3,8),
(66, '环境检测','/ourphp_tz.php',4,8),
(67, '产品计算器','/plugs/Calculator/index.html',5,8),
(68, '后台信息检索','/ourphp_adminsearch.php?id=ourphp',6,8),
(69, '邀请奖励列表','/ourphp_userinvitation.php?name=0&list=2',6,4),
(70, '团购管理','/ourphp_grouplist.php?id=ourphp',14,3),
(71, '财务管理','ourphp',1,9),
(72, '财务对账','/ourphp_finance.php?id=ourphp',2,9),
(73, '提现管理','/ourphp_backmoney.php?id=ourphp',3,9),
(74, '退款管理','/ourphp_outmoney.php?id=ourphp',4,9),
(75, '系统日志','/ourphp_logs.php?id=ourphp',7,8),
(76, '开发者','/ourphp_developer.php?id=ourphp',8,5),
(77, '生成Sitemap','/ourphp_sitemap.php?id=ourphp',5,6);
/*
	北京市|天津市|上海市|重庆市|国外|河北省|河南省|云南省|辽宁省|黑龙江省|湖南省|安徽省|山东省|新疆|江苏省|浙江省|江西省|湖北省|广西|甘肃省|山西省|内蒙古|陕西省|吉林省|福建省|贵州省|广东省|青海省|西藏|四川省|宁夏|海南省|台湾省|香港|澳门
*/
INSERT INTO `ourphp_freight` (`id`, `OP_Freightname`, `OP_Freighttext`, `OP_Freightdefault`, `OP_Freightweight`) VALUES(1, '包邮模板(官方默认)','0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0','1','0');