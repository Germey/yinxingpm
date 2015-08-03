<?php
define('SYS_TIMESTART', microtime(true));
define('DIR_SEPERATOR', strstr(strtoupper(PHP_OS), 'WIN')?'\\':'/');
define('DIR_ROOT', str_replace('\\','/',dirname(__FILE__)));
define('SYS_MAGICGPC', get_magic_quotes_gpc());
define('SYS_PHPFILE', DIR_ROOT . '/config/system.php');
define('WWW_ROOT', rtrim(dirname(DIR_ROOT),'/'));
define('IMG_ROOT', WWW_ROOT . '/uploads');
define('UPLOAD_ROOT', WWW_ROOT . '/uploads');

//for importing/reading files
define('UTF32_BIG_ENDIAN_BOM'   , chr(0x00) . chr(0x00) . chr(0xFE) . chr(0xFF));
define('UTF32_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE) . chr(0x00) . chr(0x00));
define('UTF16_BIG_ENDIAN_BOM'   , chr(0xFE) . chr(0xFF));
define('UTF16_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE));
define('UTF8_BOM'               , chr(0xEF) . chr(0xBB) . chr(0xBF));



// 20  1   待初审 init
// 22  1   待复审 init
// 24  1   待派发 init
// 30  1   待签订合同   init
// 40  1   待拨第1笔款  ing
// 50  1   待拨第2笔款  ing
// 60  1   待拨第3笔款  ing
// 90  1   待中期报告   ing
// 100 1   待末期报告   ing
// 110 1   待结项 ing
// 120 1   项目关闭    end
// 130 1   已驳回 fail
// 140 1   项目延期    ing

// // 自定义 项目状态
define('STATUS_NEED_SCREEN'   , 20);
define('STATUS_NEED_AUDIT'    , 22);
// define('STATUS_NEED_CONFIRM' , 24);
define('STATUS_NEED_MEETING' , 26);
define('STATUS_NEED_CONTRACT' , 30);
define('STATUS_NEED_FIRST_GRANT' , 40);
define('STATUS_DELAY' , 115);
define('STATUS_FAILED' , 130);
