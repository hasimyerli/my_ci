<?php
$route["admin/modules"] = "Admin/Settings/Module/Module/getList";
//Yeni modülleri veritabanına ekler
$route["admin/module/refresh"] = "Admin/Settings/Module/Module/refreshModules";
$route["admin/module/edit/(:num)"] = "Admin/Settings/Module/Module/edit/$1";
