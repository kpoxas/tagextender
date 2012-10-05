<?php

class PluginTagextender_ModuleTopic_MapperTopic extends PluginTagextender_Inherit_ModuleTopic_MapperTopic  {

    /**
     * Добавление тега к топику
     *
     * @param ModuleTopic_EntityTopicTag $oTopicTag	Объект тега топика
     * @return int
     */
    public function AddTopicTagGrouped(ModuleTopic_EntityTopicTag $oTopicTag) {
        $sql = "INSERT INTO ".Config::Get('db.table.topic_tag')."
			(topic_id,
			user_id,
			blog_id,
			topic_tag_text,
		    topic_tag_group_id
			)
			VALUES(?d,  ?d,  ?d,	?, ?d)
		";
        if ($iId=$this->oDb->query($sql,
            $oTopicTag->getTopicId(),
            $oTopicTag->getUserId(),
            $oTopicTag->getBlogId(),
            $oTopicTag->getText(),
            $oTopicTag->getGroupId())
        )
        {
            return $iId;
        }
        return false;
    }
    /**
     * Удаляет теги у топика
     *
     * @param int $sTopicId	ID топика
     * @return bool
     */
    public function DeleteTopicTagsGroupedByTopicId($sTopicId) {
        $sql = "DELETE FROM ".Config::Get('db.table.topic_tag')."
			WHERE
				topic_id = ?d
				AND topic_tag_group_id>0
		";
        if ($this->oDb->query($sql,$sTopicId)) {
            return true;
        }
        return false;
    }
    /**
     * Достает все типы топика
     *
     * @return array
     */
    public function GetTopicTypes() {
        $sql = "SHOW COLUMNS FROM ".Config::Get('db.table.topic')."
			WHERE
				field = 'topic_type'
		";
        if ($aResult = $this->oDb->selectRow($sql)) {
            $sTypes = str_ireplace(array("enum('", "')", "''"), array('', '', "'"), $aResult['Type']);
            return explode("','", $sTypes);
        }
        return false;
    }
    /**
     * Достает все типы блога
     *
     * @return array
     */
    public function GetBlogTypes() {
        $sql = "SHOW COLUMNS FROM ".Config::Get('db.table.blog')."
			WHERE
				field = 'blog_type'
		";
        if ($aResult = $this->oDb->selectRow($sql)) {
            $sTypes = str_ireplace(array("enum('", "')", "''"), array('', '', "'"), $aResult['Type']);
            return explode("','", $sTypes);
        }
        return false;
    }

}

?>
