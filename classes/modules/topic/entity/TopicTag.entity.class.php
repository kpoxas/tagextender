<?php

class PluginTagextender_ModuleTopic_EntityTopicTag extends PluginTagextender_Inherit_ModuleTopic_EntityTopicTag {

    public function getGroupId() {
        return $this->_getDataOne('topic_tag_group_id');
    }
    public function setGroupId($data) {
        $this->_aData['topic_tag_group_id']=$data;
    }

}

?>
