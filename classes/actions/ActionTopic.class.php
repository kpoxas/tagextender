<?php

class PluginTagextender_ActionTopic extends PluginTagextender_Inherit_ActionTopic {

    /**
     * Инициализация экшена
     */
    public function Init() {
        return parent::Init();
    }

    /**
     * Проверка полей формы
     *
     * @return bool
     */
    protected function checkTopicFields($oTopic) {
        if ($aTagGroups = $this->PluginTagextender_Tagextender_GetTagGroups($oTopic->getType(),$oTopic->getBlogId())) {
            $oTopic->setAllowEmptyTags(true);
            $oTopic->setTagsGrouped(array_intersect_key(getRequest('topic_tags_grouped'),$aTagGroups));
        }
        $result = parent::checkTopicFields($oTopic);
        /*echo "<pre>";
        print_r($oTopic->_getValidateErrors());
        die();   */
        return $result;
    }
}
?>
