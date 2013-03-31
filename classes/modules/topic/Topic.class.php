<?php

class PluginTagextender_ModuleTopic extends PluginTagextender_Inherit_ModuleTopic {

    /**
     * Добавляет топик
     *
     * @param ModuleTopic_EntityTopic $oTopic	Объект топика
     * @return ModuleTopic_EntityTopic|bool
     */
    public function AddTopic(ModuleTopic_EntityTopic $oTopic) {
        $args = func_get_args();
        // execute original function first
        $result = call_user_func_array(array('parent',__FUNCTION__),$args);
        // check if it is a proper object
        /*if (is_a($result,'ModuleTopic_EntityTopic')) {
            $oTopic = $result;
        } */
        $aTagsGrouped = $oTopic->getTagsGrouped();
        if (!$oTopic->getPublish() || empty($aTagsGrouped)) {
            return $result;
        }
        $this->AddTopicTagsGrouped($oTopic);
        return $result;
    }

    public function AddTopicTagsGrouped(ModuleTopic_EntityTopic $oTopic) {
        $this->oMapperTopic->DeleteTopicTagsGroupedByTopicId($oTopic->getId());
        $aTagsGrouped = $oTopic->getTagsGrouped();
        foreach ($aTagsGrouped as $iGroupId=>$sTags) {
            $aTags = explode(',',$sTags);
            foreach ($aTags as $sTag) {
                $oTag=Engine::GetEntity('Topic_TopicTag');
                $oTag->setTopicId($oTopic->getId());
                $oTag->setUserId($oTopic->getUserId());
                $oTag->setBlogId($oTopic->getBlogId());
                $oTag->setText($sTag);
                $oTag->setGroupId($iGroupId);
                $this->AddTopicTagGrouped($oTag);
            }
        }
    }

    public function AddTopicTagGrouped(ModuleTopic_EntityTopicTag $oTopicTag) {
        return $this->oMapperTopic->AddTopicTagGrouped($oTopicTag);
    }

    /**
     * Обновляет топик
     *
     * @param ModuleTopic_EntityTopic $oTopic	Объект топика
     * @return bool
     */
    public function UpdateTopic(ModuleTopic_EntityTopic $oTopic) {
        $args = func_get_args();
        $oTopicOld=$this->GetTopicById($oTopic->getId());
        // execute original function first
        $result = call_user_func_array(array('parent',__FUNCTION__),$args);
        /**
         * Получаем топик ДО изменения
         */
        if ($oTopic->getPublish()!=$oTopicOld->getPublish()
            || $oTopic->getBlogId()!=$oTopicOld->getBlogId()
            || @serialize($oTopic->getTagsGrouped())!==@serialize($oTopicOld->getTagsGrouped())
        ) {
            $aTagsGrouped = $oTopic->getTagsGrouped();
            if ($oTopic->getPublish() && !empty($aTagsGrouped)) {
                $this->AddTopicTagsGrouped($oTopic);
            }
        }
        return $result;
    }
    /**
     * Получает список топиков по тегу
     *
     * @param  array  $aFilter	Фильтр
     * @param  int    $iPage	Номер страницы
     * @param  int    $iPerPage	Количество элементов на страницу
     * @param  bool   $bAddAccessible Указывает на необходимость добавить в выдачу топики,
     *                                из блогов доступных пользователю. При указании false,
     *                                в выдачу будут переданы только топики из общедоступных блогов.
     * @return array
     */
    public function GetTopicsByTagFilter($aFilter,$iPage,$iPerPage,$bAddAccessible=true) {
        if (!isset($aFilter['tag']) || !$sTag = $aFilter['tag']) {
            return false;
        }
        if (!is_numeric($iPage) or $iPage<=0) {
            $iPage=1;
        }
        $aCloseBlogs = ($this->oUserCurrent && $bAddAccessible)
            ? $this->Blog_GetInaccessibleBlogsByUser($this->oUserCurrent)
            : $this->Blog_GetInaccessibleBlogsByUser();
        $aFilterForCache = array_diff_assoc($aFilter,array('tag'=>''));
        $s = serialize(func_array_merge_assoc($aFilterForCache,$aCloseBlogs));
        if (false === ($data = $this->Cache_Get("topic_tag_{$sTag}_{$iPage}_{$iPerPage}_{$s}"))) {
            $data = array('collection'=>$this->oMapperTopic->GetTopicsByTagFilter($aFilter,$aCloseBlogs,$iCount,$iPage,$iPerPage),'count'=>$iCount);
            $this->Cache_Set($data, "topic_tag_{$sTag}_{$iPage}_{$iPerPage}_{$s}", array('topic_update','topic_new'), 60*60*24*2);
        }
        $data['collection']=$this->GetTopicsAdditionalData($data['collection']);
        return $data;
    }

    public function GetTopicsByTag($sTag,$iPage,$iPerPage,$bAddAccessible=true) {
        $aFilter = array(
            'tag' => $sTag,
        );
        if (!Config::Get('plugin.tagextender.include_all')) {
            $aFilter['group_id'] = 0;
        }
        return $this->GetTopicsByTagFilter($aFilter,$iPage,$iPerPage,$bAddAccessible);
    }
    /**
     * Получает список тегов из топиков открытых блогов (open,personal)
     *
     * @param  int $iLimit	Количество
     * @param  int|null $iUserId	ID пользователя, чью теги получаем
     * @param  int|array|null $aGroupId	ID групп тегов
     * @return array
     */
    public function GetOpenTopicTags($iLimit,$iUserId=null,$aGroupId=array(0)) {
        if (empty($aGroupId)) $aGroupId = array(0);
        $aGroupId = (array)$aGroupId;

        $sCacheKey = "tag_{$iLimit}_{$iUserId}_open";
        if (sizeof($aGroupId)>1||$aGroupId[0]!==0) {
           $sCacheKey.= @serialize($aGroupId);
        }
        if (false === ($data = $this->Cache_Get($sCacheKey))) {
            $data = $this->oMapperTopic->GetOpenTopicTags($iLimit,$iUserId,$aGroupId);
            $this->Cache_Set($data, $sCacheKey, array('topic_update','topic_new'), 60*60*24*3);
        }
        return $data;
    }
    /**
     * Достает все типы топика
     *
     * @return array
     */
    public function GetTopicTypes() {
        return $this->oMapperTopic->GetTopicTypes();
    }
    /**
     * Достает все типы блога
     *
     * @return array
     */
    public function GetBlogTypes() {
        return $this->oMapperTopic->GetBlogTypes();
    }
}
?>
