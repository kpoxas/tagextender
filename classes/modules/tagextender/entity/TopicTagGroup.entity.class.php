<?php

class PluginTagextender_ModuleTagextender_EntityTopicTagGroup extends EntityORM {
    /**
     * Массив для дополнительных данных
     *
     * @var array
     */
    protected $aExtra=null;
    /**
     * Определяем правила валидации
     */
    public function Init() {
        parent::Init();
        $this->aValidateRules[]=array('name','string','max'=>200,'min'=>2,'allowEmpty'=>false,'label'=>$this->Lang_Get('plugin.tagextender.group_field_name'));
        $this->aValidateRules[]=array('keyword','regexp','pattern'=>'/^[a-z][a-zA-Z0-9_]*$/i','allowEmpty'=>false,'label'=>$this->Lang_Get('plugin.tagextender.group_field_keyword'));
        $this->aValidateRules[]=array('keyword','keyword_unique');
    }
    /**
     * Проверка keyword на уникальность
     *
     * @param string $sValue	Проверяемое значение
     * @param array $aParams	Параметры
     * @return bool|string
     */
    public function ValidateKeywordUnique($sValue,$aParams) {
        if ($oTargetEquivalent=$this->PluginTagextender_Tagextender_GetTopicTagGroupByKeyword($sValue)) {
            if ($iId=$this->getId() and $oTargetEquivalent->getId()==$iId) {
                return true;
            }
            return $this->Lang_Get('plugin.tagextender.error_group_keyword_unique');
        }
        return true;
    }

    public function __get($sName) {
        // get an array data from object
        return array_key_exists($sName, $this->_aData) ? $this->_getDataOne($sName) : parent::__get($sName);
    }

    /***************************************************************************************************************************************************
     * методы расширения
     ***************************************************************************************************************************************************
     */
    /**
     * Возвращает сериализованные строку дополнительный данных
     *
     * @return string
     */
    public function getExtra() {
        return $this->_getDataOne('extra') ? $this->_getDataOne('extra') : serialize('');
    }
    /**
     * Устанавливает сериализованную строчку дополнительных данных
     *
     * @param string $data
     */
    public function setExtra($data) {
        $this->_aData['extra']=serialize($data);
    }
    /**
     * Извлекает сериализованные данные
     */
    protected function extractExtra() {
        if (is_null($this->aExtra)) {
            $this->aExtra=@unserialize($this->getExtra());
        }
    }
    /**
     * Устанавливает значение нужного параметра
     *
     * @param string $sName	Название параметра/данных
     * @param mixed $data	Данные
     */
    protected function setExtraValue($sName,$data) {
        $this->extractExtra();
        $this->aExtra[$sName]=$data;
        $this->setExtra($this->aExtra);
    }
    /**
     * Извлекает значение параметра
     *
     * @param string $sName	Название параметра
     * @return null|mixed
     */
    protected function getExtraValue($sName) {
        $this->extractExtra();
        if (isset($this->aExtra[$sName])) {
            return $this->aExtra[$sName];
        }
        return null;
    }
    /**
     * Данные для валидации
     * Можно ли оставлять пустым
     */
    public function getAllowEmpty() {
        return $this->getExtraValue('allow_empty');
    }
    public function setAllowEmpty($data) {
        $this->setExtraValue('allow_empty',$data);
    }
    /**
     * Данные для валидации
     * Количество тегов
     */
    public function getMaxCount() {
        return $this->getExtraValue('count')===null ? 15 : $this->getExtraValue('count');
    }
    public function setMaxCount($data) {
        $this->setExtraValue('count',(int)$data);
    }
    /**
     * Данные для валидации
     * Минимальня длина тега
     */
    public function getMinLength() {
        return $this->getExtraValue('min')===null ? 2 : $this->getExtraValue('min');
    }
    public function setMinLength($data) {
        $this->setExtraValue('min',$data);
    }
    /**
     * Данные для валидации
     * Максимальня длина тега
     */
    public function getMaxLength() {
        return $this->getExtraValue('max')===null ? 50 : $this->getExtraValue('max');
    }
    public function setMaxLength($data) {
        $this->setExtraValue('max',(int)$data);
    }
    /**
     * Данные для валидации
     * Разделитель тегов
     */
    public function getSep() {
        return $this->getExtraValue('sep')===null ? ',' : $this->getExtraValue('sep');
    }
    public function setSep($data) {
        $this->setExtraValue('sep',$data);
    }
    /**
     * Данные для фильтра отображения
     * Типы топиков
     */
    public function getTopicTypes() {
        return $this->getExtraValue('topic_types');
    }
    public function setTopicTypes($data) {
        $this->setExtraValue('topic_types',$data);
    }
    /**
     * Данные для фильтра отображения
     * Типы блогов
     */
    public function getBlogTypes() {
        return $this->getExtraValue('blog_types');
    }
    public function setBlogTypes($data) {
        $this->setExtraValue('blog_types',$data);
    }

    /**
     * Проверка доступности группы для топика
     */
    public function checkAvailable($oTopic) {
        // check for topic type
        if (!in_array($oTopic->getType(),(array)$this->getTopicTypes())) return false;
        // check for blog type
        if (false === ($oBlog = $this->Blog_GetBlogById($oTopic->getBlogId()))) return false;
        if (!in_array($oBlog->getType(),(array)$this->getBlogTypes())) return false;

        return true;
    }


}

?>
