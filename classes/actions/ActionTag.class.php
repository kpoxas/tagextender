<?php

class PluginTagextender_ActionTag extends PluginTagextender_Inherit_ActionTag {


    public function RegisterEvent() {
        $this->AddEventPreg('/^[a-z][a-z0-9]+$/i','/^(.+)$/i','/^(page([1-9]\d{0,5}))?$/i','EventTagsGroup');
        parent::RegisterEvent();
    }

    public function EventTagsGroup() {
        /**
         * Получаем группу тегов из УРЛа
         */
        $sKeyword=$this->sCurrentEvent;
        /**
         * Получаем тег из УРЛа
         */
        $sTag=$this->GetParamEventMatch(0,1);
        /**
         * Передан ли номер страницы
         */
        $iPage=$this->GetParamEventMatch(1,2) ? $this->GetParamEventMatch(1,2) : 1;
        /**
         * Получаем список топиков
         */
        $aFilter = array(
            'tag' =>  $sTag,
            'keyword' =>  $sKeyword,
            'topic_publish' => 1,
        );
        $aResult=$this->Topic_GetTopicsByTagFilter($aFilter,$iPage,Config::Get('module.topic.per_page'));
        $aTopics=$aResult['collection'];
        /**
         * Вызов хуков
         */
        $this->Hook_Run('topics_list_show',array('aTopics'=>$aTopics));
        /**
         * Формируем постраничность
         */
        $aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.topic.per_page'),Config::Get('pagination.pages.count'),Router::GetPath('tag').$sKeyword.'/'.htmlspecialchars($sTag));
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('aPaging',$aPaging);
        $this->Viewer_Assign('aTopics',$aTopics);
        $this->Viewer_Assign('sTag',$sTag);
        $this->Viewer_AddHtmlTitle($this->Lang_Get('tag_title'));
        $this->Viewer_AddHtmlTitle($sTag);
        $this->Viewer_SetHtmlRssAlternate(Router::GetPath('rss').'tag/'.$sTag.'/',$sTag);
        /**
         * Устанавливаем шаблон вывода
         */
        $this->SetTemplateAction('index');
    }
}
?>
