<?php
/**
 * Конфиг
 */

$config = array();

// Переопределить имеющуюся переменную в конфиге:
// Переопределение роутера на наш новый Action - добавляем свой урл  http://domain.com/tagextender
// Config::Set('router.page.tagextender', 'PluginTagextender_ActionTagextender');

// Добавить новую переменную:
// $config['per_page'] = 15;
// Эта переменная будет доступна в плагине как Config::Get('plugin.tagextender.per_page')
Config::Set('db.table.topic_tag_group','___db.table.prefix___topic_tag_group');
Config::Set('db.table.tagextender_topic_tag_group','___db.table.prefix___topic_tag_group');
Config::Set('db.table.topic_tag_meta','___db.table.prefix___topic_tag_meta');
Config::Set('db.table.tagextender_topic_tag_meta','___db.table.prefix___topic_tag_meta');

$config['include_all']=true;  // показывать все теги в результатах отбора, даже если они не в той группе
$config['hide_native_tags']=true;  // прятать родные теги ЛС, если есть группы

// для AceAdmin
Config::Set(
    'plugin.aceadminpanel.autonomous.events',
    array_merge(
        (array)Config::Get('plugin.table.autonomous.events'),
        array('tagextender')
    )
);



return $config;
