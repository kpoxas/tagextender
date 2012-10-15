<?php

class PluginTagextender_ActionQuestion extends PluginTagextender_Inherit_ActionQuestion {

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
            $oTopic->setTagsGrouped(array_intersect_key((array)getRequest('topic_tags_grouped'),$aTagGroups));
        }
        $result = parent::checkTopicFields($oTopic);
        return $result;
    }
}
?>
