<?php

class PluginTagextender_ModuleTopic_EntityTopic extends PluginTagextender_Inherit_ModuleTopic_EntityTopic {

    protected $aTagGroups = null;
    protected $aTagGroupsByKeyword = null;
    protected $iTagGroupsBlogId = null;
    protected $sTagGroupsTopicType = null;

    /**
     * Получает возможные группы тегов для данного топика
     * @return array
     */
    public function getTagGroups() {
        if ($this->aTagGroups===null && $this->getBlogId()==$this->iTagGroupsBlogId && $this->getType()==$this->sTagGroupsTopicType) {
            return $this->aTagGroups;
        }
        $aTagGroups = $this->PluginTagextender_Tagextender_GetTagGroups($this->getType(),$this->getBlogId());
        // соответствие keyword => id
        if (!empty($aTagGroups)) {
            foreach ($aTagGroups as $oTagGroup) {
                $this->aTagGroupsByKeyword[func_underscore($oTagGroup->keyword)]=$oTagGroup->id;
            }
        }
        $this->aTagGroups = $aTagGroups;
        $this->iTagGroupsBlogId =$this->getBlogId();
        $this->sTagGroupsTopicType =$this->getType();
        return $this->aTagGroups;
    }

    /**
     * Извлекает сериализированные теги по группам
     * @return mixed
     */
    public function getTagsGrouped() {
        return $this->getExtraValue('topic_tags_grouped');
    }
    /**
     * Получает массив массивов тегов по группам
     * @param integer $id
     * @return mixed
     */
    public function getTagsGroupedArray($id = false) {
        $aTagsGrouped = $this->getExtraValue('topic_tags_grouped');
        if (empty($aTagsGrouped)) return;
        $aTags = array();
        if ($id && !isset($aTagsGrouped[$id])) {
            return false;
        } else if (isset($aTagsGrouped[$id])) {
            return explode(',',$aTagsGrouped[$id]);
        }
        foreach($aTagsGrouped as $iGroupId=>&$sTags) {
            $aTags[$iGroupId] = explode(',',$sTags);
        }
        return $aTags;
    }
    /**
     * Получает массив тегов по ключевому слову группы
     * @return mixed
     */
    public function getTagsByKeyword($keyword) {
        if (!$this->getTagGroups()) return false;
        if (isset($this->aTagGroupsByKeyword[$keyword])) {
            return $this->getTagsGroupedArray($this->aTagGroupsByKeyword[$keyword]);
        }
    }

    public function setTagsGrouped($data) {
        $this->setExtraValue('topic_tags_grouped',array_diff(array_map('trim',$data),array('')));
        // needs for validation
        if (empty($data)) return;
        foreach ((array)$data as $iGroupId=>$sTags) {
            $this->_aData['topic_tags_grouped'.$iGroupId] = $sTags;
        }
    }

    public function setAllowEmptyTags($data) {
         foreach($this->aValidateRules as &$aValidateRule) {
             if($aValidateRule[0] == 'topic_tags') {
                 $aValidateRule['allowEmpty'] = $data;
             }
         }
    }

    public function _Validate($aFields=null, $bClearErrors=true) {
        /**
         * Определяем правила валидации
         */
        $aTagGroups =  $this->getTagGroups();
        if (!empty($aTagGroups)) {
            $this->setAllowEmptyTags(true);
            // set validator for each group
            foreach ($aTagGroups as $oTagGroup) {
                $this->aValidateRules[]=array('topic_tags_grouped'.$oTagGroup->getId(),'tags',
                    'count'=>15,
                    'label'=>$this->Lang_Get('topic_create_tags'),
                    'allowEmpty'=> $oTagGroup->getAllowEmpty() === null ?  Config::Get('module.topic.allow_empty_tags') : $oTagGroup->getAllowEmpty(),
                    'count' => $oTagGroup->getMaxCount(),
                    'min' => $oTagGroup->getMinLength(),
                    'max' => $oTagGroup->getMaxLength(),
                    'on'=>array('topic','link','question','photoset'),
                    'label' => $oTagGroup->getName(),
                );
            }
        }

        $result = call_user_func_array(array('parent',__FUNCTION__), func_get_args());
        // set validated tags
        $aTagsGroupedNew = array();
        foreach ($aTagGroups as $iGroupId=>$sTags) {
            if (!isset($this->_aData['topic_tags_grouped'.$iGroupId])) continue;
            $aTagsGroupedNew[$iGroupId] = $this->_aData['topic_tags_grouped'.$iGroupId];
        }
        $this->setTagsGrouped($aTagsGroupedNew);
        return $result;
    }

    public function __call($sName,$aArgs) {
        $sFunc = func_underscore($sName);
        if (preg_match('/^get_tags_([a-z_0-9]+)$/i',$sFunc, $aMatches)) {
            return $this->getTagsByKeyword($aMatches[1]);
        }
        return call_user_func_array(array('parent',__FUNCTION__),func_get_args());
    }

}

?>
