<?php

class PluginTagextender_HookTagextender extends Hook {

    /*
     * Регистрация событий на хуки
     */
    public function RegisterHook() {

        $this->AddHook('topic_add_show','injectJS', __CLASS__, -5);
        $this->AddHook('topic_edit_show','injectJS', __CLASS__, -5);
        $this->AddHook('topic_edit_show','addTopicFormVars', __CLASS__, -10);
        $this->AddHook('template_html_head_end', 'InjectHeader');
        $this->AddHook('template_admin_action_item', 'addMenuAdmin',  __CLASS__, -10);

        $this->AddHook('template_topic_content_begin', 'injectTags',  __CLASS__, -10);
        $this->AddHook('template_topic_preview_content_begin', 'injectTagsPreview',  __CLASS__, -10);
    }

    public function injectJS() {
        $this->Viewer_AppendScript(Plugin::GetWebPath(__CLASS__) . 'js/topic.edit.js');
    }

    public function addTopicFormVars($aParams) {
        $oTopic = $aParams['oTopic'];
        if (!isPost('submit_topic_publish') && !isPost('submit_topic_save')) {
           $_REQUEST['topic_tags_grouped'] = $oTopic->getTagsGrouped();
        }
    }

    /**
     * Добавляет пункт в меню админки
     * @return mixed
     */
    public function addMenuAdmin() {
        $this->Viewer_Assign('sSettingsPage', Router::GetPath('admin').'tagextender');
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'menu.admin.tpl');
    }
    /**
     * Прогружаем значение JS переменных
     */
    public function InjectHeader() {
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'inject.header.tpl');
    }
    /**
     * Вывод доп тегов в топике
     */
    public function injectTags($aParams) {
        $oTopic = $aParams['topic'];
        $this->Viewer_Assign('oTopic',$oTopic);
        $this->Viewer_Assign('bTopicList',$aParams['bTopicList']);
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'inject.topic.tags.tpl');
    }
    /**
     * Вывод доп тегов в превью
     */
    public function injectTagsPreview($aParams) {
        $oTopic = $aParams['topic'];
        if ($aTagGroups = $this->PluginTagextender_Tagextender_GetTagGroups($oTopic->getType(),$oTopic->getBlogId())) {
            $oTopic->setTagsGrouped(array_intersect_key((array)getRequest('topic_tags_grouped'),$aTagGroups));
            $this->Viewer_Assign('oTopic',$oTopic);
            return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__) . 'inject.topic.tags.tpl');
        }
    }
}
?>
