<?php
//公共配置
$common_config = include APP_PATH.'Common/Conf/config.php';

//私有配置
$private_config = array(
                        'SHOW_PAGE_TRACE' => true,
                        'LAYOUT_ON' => true,
                        'URL_ROUTER_ON' => true,
                        'URL_CASE_INSENSITIVE' =>true,
                        'URL_ROUTE_RULES' => array(
                                                  'modfood/:foodid' => 'Food/modfood',
                                                  'delfood/:foodid' => 'Food/delfood',
                                                  'modvote/:voteid' => 'Vote/modvote',
                                                  'delvote/:voteid' => 'Vote/delvote',
                                                  'deljxhd/:hdid' => 'Index/deljxhd',
                                                  'modjxhd/:hdid' => 'Index/modjxhd',
                                                  'viewvote/:voteid' => 'Vote/view',
                                                  'deljxsp/:spid' => 'Index/deljxsp',
                                                  'modjxsp/:spid' => 'Index/modjxsp',
                                                  )
                        );

return array_merge($common_config, $private_config);
