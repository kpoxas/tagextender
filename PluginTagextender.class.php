<?php

/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
    die('Hacking attemp!');
}

class PluginTagextender extends Plugin {

    // Объявление делегирований (нужны для того, чтобы назначить свои экшны и шаблоны)
    public $aDelegates = array(

    );

    // Объявление переопределений (модули, мапперы и сущности)
    protected $aInherits=array(
         'action'  =>array('ActionAdmin','ActionTopic','ActionBlog'),
         'module'  =>array('ModuleTopic'),
         'entity'  =>array('ModuleTopic_EntityTopic'),
         'mapper'  =>array('ModuleTopic_MapperTopic'),
    );

    // Активация плагина
    public function Activate() {

        if (!$this->isFieldExists(Config::Get('db.table.topic_tag'),'topic_tag_group_id')) {
            $this->ExportSQL(dirname(__FILE__).'/install.sql'); // Если нам надо изменить БД, делаем это здесь.
        } else {
            $this->ExportSQL(dirname(__FILE__).'/update.sql'); // Если нам надо изменить БД, делаем это здесь.
        }

        return true;
    }

    // Деактивация плагина
    public function Deactivate(){
        /*
        $this->ExportSQL(dirname(__FILE__).'/deinstall.sql'); // Выполнить деактивационный sql, если надо.
        */
        return true;
    }


    // Инициализация плагина
    public function Init() {
       // $this->Viewer_AppendStyle(Plugin::GetTemplatePath(__CLASS__)."/css/style.css"); // Добавление своего CSS
        //$this->Viewer_AppendScript(Plugin::GetTemplatePath(__CLASS__)."/js/script.js"); // Добавление своего JS

        //$this->Viewer_AddMenu('blog',Plugin::GetTemplatePath(__CLASS__).'/menu.blog.tpl'); // например, задаем свой вид меню
    }

}
?>