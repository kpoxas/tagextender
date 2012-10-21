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
        $sql = "SHOW COLUMNS FROM `".Config::Get('db.table.topic')."`
			WHERE
				`Field` = 'topic_type'
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
        $sql = "SHOW COLUMNS FROM `".Config::Get('db.table.blog')."`
			WHERE
				`Field` = 'blog_type'
		";
        if ($aResult = $this->oDb->selectRow($sql)) {
            $sTypes = str_ireplace(array("enum('", "')", "''"), array('', '', "'"), $aResult['Type']);
            return explode("','", $sTypes);
        }
        return false;
    }

    /**
     * Получает список топиков по тегу
     *
     * @param  array  $aFilter	Фильтр
     * @param  array  $aExcludeBlog	Список ID блогов для исключения
     * @param  int    $iCount	Возвращает общее количество элементов
     * @param  int    $iCurrPage	Номер страницы
     * @param  int    $iPerPage	Количество элементов на страницу
     * @return array
     */
    public function GetTopicsByTagFilter($aFilter,$aExcludeBlog,&$iCount,$iCurrPage,$iPerPage) {
        $sTag = $aFilter['tag'];
        if (isset($aFilter['keyword']) && !empty($aFilter['keyword'])) {
            $aKeywords = (array)$aFilter['keyword'];
            foreach($aKeywords as &$sKeyword) {
               $sKeyword = mb_strtolower($sKeyword,"UTF-8");
            }
        } else {
            $aKeywords = null;
        }

        $sql = "
							SELECT
								tt.topic_id
							FROM
								".Config::Get('db.table.topic_tag')." tt
								{ LEFT JOIN ".Config::Get('db.table.topic_tag_group')." ttg ON (tt.topic_tag_group_id=ttg.id and 1=?d) }
							WHERE
								topic_tag_text = ?
								{ AND tt.blog_id NOT IN (?a) }
								{ AND tt.topic_tag_group_id IN (?a) }
								{ AND LOWER(ttg.keyword) IN (?a) }
                            ORDER BY topic_id DESC
                            LIMIT ?d, ?d ";

        $aTopics=array();
        if ($aRows=$this->oDb->selectPage(
            $iCount,$sql,
            $aKeywords?1:DBSIMPLE_SKIP,
            $sTag,
            (is_array($aExcludeBlog)&&count($aExcludeBlog)) ? $aExcludeBlog : DBSIMPLE_SKIP,
            (isset($aFilter['group_id'])&&count((array)$aFilter['group_id'])) ? (array)$aFilter['group_id'] : DBSIMPLE_SKIP,
            $aKeywords,
            ($iCurrPage-1)*$iPerPage, $iPerPage
        )
        ) {
            foreach ($aRows as $aTopic) {
                $aTopics[]=$aTopic['topic_id'];
            }
        }
        return $aTopics;
    }



}

?>
