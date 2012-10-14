<?php

class PluginTagextender_ModuleTagextender extends ModuleORM {

    public function Init() {
        /**
         * Проверяем авторизован ли юзер
         */
        if ($this->User_IsAuthorization()) {
            $this->oUserCurrent=$this->User_GetUserCurrent();
        }
        return parent::Init();
    }

    public function GetTagGroups($sTopicType=false, $iBlogId=0) {
        $aFilter = array(
            '#index-from-primary',
            'enabled' => 1,
            '#order' => array('order_num' => 'ASC'),
        );

        if (!$aTagGroups = $this->GetTopicTagGroupItemsByFilter($aFilter)) {
            return null;
        }
        foreach ($aTagGroups as $key=>$oTagGroup) {
            // check for topic type
            if (!$sTopicType || !in_array($sTopicType,(array)$oTagGroup->getTopicTypes())) unset($aTagGroups[$key]);
            // check for blog type
            if ($iBlogId===null) continue;
            if ($iBlogId==0) {
                $oBlog=$this->Blog_GetPersonalBlogByUserId($this->oUserCurrent->getId());
            } else {
                $oBlog=$this->Blog_GetBlogById($iBlogId);
            }
            if (!$oBlog->getType() || !in_array($oBlog->getType(),(array)$oTagGroup->getBlogTypes())) unset($aTagGroups[$key]);
        }

        return $aTagGroups;
    }

}
?>
