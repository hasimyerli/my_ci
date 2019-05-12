<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['twig']['template_dir'] = VIEWPATH;
$config['twig']['template_ext'] = 'twig';
$config['twig']['environment']['autoescape'] = TRUE;
$config['twig']['environment']['cache'] = FALSE;
//debug sonrası tekrar "false" yapılacak view içinde dump kullanımı gibi detaylar için
$config['twig']['environment']['debug'] = TRUE;
