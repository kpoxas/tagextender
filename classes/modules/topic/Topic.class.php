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
        if (is_a($result,'ModuleTopic_EntityTopic')) {
            $oTopic = $result;
        }
        $aTagsGrouped = $oTopic->getTagsGrouped();
        if ($oTopic->getPublish() || empty($aTagsGrouped)) {
            return $result;
        }
        $this->AddTopicTagsGrouped($oTopic);
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
            $this->AddTopicTagsGrouped($oTopic);
        }
        return $result;
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
