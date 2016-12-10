# NosRegister
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
            
        
#In order to use sqlsvr you need to install mssql extension you can find more there : 
* http://php.net/manual/en/mssql.installation.php
* https://www.microsoft.com/en-us/download/details.aspx?id=20098 (install, copy .dll to php/ext ) and add to php.ini
* Microsoft Drivers for PHP for SQL Server : https://github.com/Microsoft/msphpsql (i don't know if this will help you)

##php.ini to add
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

##I need more help!
If you don't know how to add extention just search on google/youtube "How to add extenstion to php"
