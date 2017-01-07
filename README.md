NosRegister
=======
NosTale Register page for Emulators and Private Server

            DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE 
                              Version 2, December 2004 

           Copyright (C) 2004 Sam Hocevar <sam@hocevar.net> 

           Everyone is permitted to copy and distribute verbatim or modified 
           copies of this license document, and changing it is allowed as long 
           as the name is changed. 

                      DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE 
             TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION 

            0. You just DO WHAT THE FUCK YOU WANT TO.
            

ScreenShot
======
![alt text](http://i.imgur.com/P1iWqy0.png "PC Version")
![alt text](http://i.imgur.com/mKy22Ar.png "Mobile Version")
![alt text](https://i.imgur.com/f9OKXoh.png "Email Validation")
![alt text](https://i.imgur.com/2PNhIPn.jpg "Forgot Mail")


Install
======
### Download and install Xampp or Wampp, or if you want apache and php.

### In order to use sqlsvr you need to install mssql extension you can find more there : 
* http://php.net/manual/en/mssql.installation.php
* https://www.microsoft.com/en-us/download/details.aspx?id=20098 (install, copy .dll to php/ext ) and add to php.ini
* Microsoft Drivers for PHP for SQL Server : https://github.com/Microsoft/msphpsql (i don't know if this will help you)

### php.ini to add
* extension=php_pdo_sqlsrv_7_nts.dll 
* extension=php_sqlsrv_7_nts.dll
* extension=php_pdo_sqlsrv_7_nts_x64.dll
* extension=php_pdo_sqlsrv_7_nts_x86.dll
* extension=php_pdo_sqlsrv_7_ts_x64.dll
* extension=php_pdo_sqlsrv_7_ts_x86.dll
* extension=php_sqlsrv_7_nts_x64.dll
* extension=php_sqlsrv_7_nts_x86.dll
* extension=php_sqlsrv_7_ts_x64.dll
* extension=php_sqlsrv_7_ts_x86.dll

### PhpShort Tag
In order to use script you need to enable shorttag, search in php.ini for "short_open_tag" and enable him by "short_open_tag=On"

### Config you'r page
Download notepad++ or any other editor, go to config.php and modify what you need.

## Optional Steps for proper use.

### Google Captcha (reCaptcha)
Personally i strong recomand you to use this feature, this will prevent spam (al most). You can get free sitekey&secretkey via google just singup there https://www.google.com/recaptcha and register you'r site.

#### Enable 'sendverification'

If you have an mail server, you can enable sendverification this will send account validation mail to users, to confirm his account.

#### Enable 'displaytos'

This will provide an link to ToS and Privacy Policy page, as you know, you store user's mail and IP, so you may need to enable this tow feature to warn users about store personal data.

#### Enable forgot

Some user can forgot this password, and you know... if they want account back they can do via own mail.

#### Mail server

If you need a mail server for windows / windows server , as free alternative i recommand you https://www.hmailserver.com/

#### SSL Certificate

As you known (or no) OpenNos use port 80, so you can't run apache on 80, as alternative you can replace http with https , for that we need a SSL Ceritificare, SSL use 443 port, not 80, so your users can type in brower " https://exemple.com " and will be displayed your register page,  you can get a free ssl ceritificare at https://www.startssl.com/ as well this ceritificate work on mail server too.

TODO-List
=======

* Account Manager
* Change Password
* Change Email
* Verification via Email
* 2-FA

I need more help!
=======
If you still have a problem or a question you can open issues there in github, i will replay a.s.a.p.
##### Help for MailServer & SSL
I provide no help for instalation & configration mail server and/or SSL ceritificate, also i provide no help for any install in non-windows OS, if you want to use Linux or any other OS you are on your own.
