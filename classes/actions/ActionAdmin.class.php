<?php

class PluginTagextender_ActionAdmin extends PluginTagextender_Inherit_ActionAdmin {

    /**
     * Инициализация экшена
     */
    public function Init() {
        return parent::Init();
    }

    /**
     * Регистрируем евенты
     */
    protected function RegisterEvent() {
        parent::RegisterEvent();
        $this->AddEventPreg('/^tagextender$/i','/^(add)$/i','EventAdminTagGroupEdit');
        $this->AddEventPreg('/^tagextender$/i','/^(edit)$/i','/^(\d+)$/i','EventAdminTagGroupEdit');
        $this->AddEvent('tagextender','EventAdminTagGroups');
    }

    protected function EventAdminTagGroups() {
        $aResult = $this->PluginTagextender_Tagextender_GetTopicTagGroupItemsByFilter(
            array(
                '#index-from-primary',
                '#order'=>array('order_num' => 'asc')
            )
        );
        /**
         * Редактирование элементов
         */
        if (isset($_REQUEST['submit_save_changes'])) {
            $aEnabled = getRequest('tag_groups_enabled');
            $aOrderNum = getRequest('tag_groups_order_num');
            foreach ($aResult as $iId=>&$oItem) {
                $oItem->setEnabled(isset($aEnabled[$iId]) ? 1 : 0);
                if (isset($aOrderNum[$iId])) $oItem->setOrderNum(intval($aOrderNum[$iId]));
                $oItem->save();
            }
            /**
             * Сортируем уже полученный массив
             * по новым значениям порядка перед выводом
             */
            usort($aResult,
                create_function(
                    '$a,$b',
                    'if ($a->getOrderNum() == $b->getOrderNum())
						{return 0;}
					return ($a->getOrderNum() < $b->getOrderNum()) ? -1 : 1;'
                )
            );
        /**
         * Удаление элементов
         */
        } else if(isset($_REQUEST['submit_tag_groups_delete'])) {
            $aChecked = getRequest('tag_groups_checked');
            foreach ($aResult as $iId=>&$oItem) {
                if (array_key_exists($iId,$aChecked)) {
                    $oItem->delete();
                    unset($aResult[$iId]);
                }
            }
        }
        $this->Viewer_Assign('aTagGroups',$aResult);
        $this->SetTemplateAction('groups.edit');
    }

    protected function EventAdminTagGroupEdit() {
        /**
         * Получаем id характеристики
         */
        $iId = $this->GetParamEventMatch(1, 1);
        $sAction = $this->GetParamEventMatch(0, 1);
        /**
         * Получаем данные о характеристике
         */
        if ($sAction == 'add') {
            /**
             * Готовим все для новой характеристики
             */
            $oTarget = Engine::getEntity('PluginTagextender_Tagextender_TopicTagGroup');
            $this->Viewer_AddHtmlTitle($this->Lang_get('plugin.tagextender.group_add'));
        } else if (!$iId || ! ($oTarget=$this->PluginTagextender_Tagextender_GetTopicTagGroupById($iId))) {
            return parent::EventNotFound();
        } else {
            $this->Viewer_Assign('sTitle',$oTarget->getName());
            $this->Viewer_AddHtmlTitle($oTarget->getName());
            $this->Viewer_AddHtmlTitle($this->Lang_get('plugin.tagextender.group_edit'));
        }
        $aTopicTypes = $this->Topic_GetTopicTypes();
        $aBlogTypes = $this->Topic_GetBlogTypes();
        /**
         * Переданы ли данные формы
         */
        if (isset($_REQUEST['submit_save'])) {
            /**
             * Заполняем поля для валидации
             */
            $oTarget->setName(getRequest('name', '', 'post'));
            $oTarget->setKeyword(getRequest('keyword', '', 'post'));
            $oTarget->setEnabled((bool)getRequest('enabled', false, 'post'));
            $oTarget->setOrderNum(intval(getRequest('order_num', 0, 'post')));
            $oTarget->setAllowEmpty(intval(getRequest('allow_empty', 0, 'post')));
            $oTarget->setMaxCount(intval(getRequest('count', 0, 'post')));
            $oTarget->setMaxLength(intval(getRequest('max', 0, 'post')));
            $oTarget->setMinLength(intval(getRequest('min', 0, 'post')));
            $oTarget->setSep(getRequest('sep'));
            $oTarget->setTopicTypes(array_values(array_intersect($aTopicTypes,getRequest('topic_types'))));
            $oTarget->setBlogTypes(array_values(array_intersect($aBlogTypes,getRequest('blog_types'))));
            /**
             * Проверка корректности полей формы и сохранение
             */
            if ($this->checkFormFields($oTarget) && $oTarget->save()) {
                $this->Message_AddNotice($this->Lang_get('plugin.tagextender.msg_group_saved'));
                return Router::Location(Router::GetPath('admin').'tagextender');
            }
        }
        /**
         * удаление характеристики
         */
        else if (isset($_REQUEST['submit_delete'])) {
            if ($oTarget->delete()) {
                $this->Message_AddNotice($this->Lang_get('plugin.tagextender.msg_group_deleted'), $oTarget->getName());
            }
            return Router::Location(Router::GetPath('admin').'tagextender');
        } else {
            $_REQUEST['name']				= $oTarget->getName();
            $_REQUEST['keyword']			= $oTarget->getKeyword();
            $_REQUEST['order_num']			= $oTarget->getOrderNum();
            $_REQUEST['enabled']			= $oTarget->getEnabled();
            $_REQUEST['allow_empty']		= $oTarget->getAllowEmpty();
            $_REQUEST['count']		        = $oTarget->getMaxCount();
            $_REQUEST['max']		        = $oTarget->getMaxLength();
            $_REQUEST['min']		        = $oTarget->getMinLength();
            $_REQUEST['sep']		        = $oTarget->getSep();
            $_REQUEST['topic_types']		= $oTarget->getTopicTypes();
            $_REQUEST['blog_types']		    = $oTarget->getBlogTypes();
        }
        $this->Viewer_Assign('aTopicTypes',$aTopicTypes);
        $this->Viewer_Assign('aBlogTypes',$aBlogTypes);
        /**
         * Устанавливаем шаблон вывода
         */
        $this->SetTemplateAction('group.edit');
    }

    /**
     * Проверка полей формы
     *
     * @return bool
     */
    protected function checkFormFields($oTarget) {
        $this->Security_ValidateSendForm();

        $bOk=true;
        /**
         * Валидируем топик
         */
        if (!$oTarget->_Validate()) {
            $this->Message_AddError($oTarget->_getValidateError(),$this->Lang_Get('error'));
            $bOk=false;
        }

        return $bOk;
    }
}
?>
