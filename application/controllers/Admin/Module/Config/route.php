<?php
$route["admin/modules"] = "Admin/Module/Module/getList";
//Yeni modülleri veritabanına ekler
$route["admin/module/refresh"] = "Admin/Module/Module/refreshModules";
$route["admin/module/edit/(:num)"] = "Admin/Module/Module/edit/$1";
