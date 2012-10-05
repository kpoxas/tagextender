<?php

class PluginTagextender_ActionBlog extends PluginTagextender_Inherit_ActionBlog {

    /**
     * Инициализация экшена
     */
    public function Init() {
        return parent::Init();
    }

    protected function AjaxBlogInfo() {
        parent::AjaxBlogInfo();
        $sTopicType = getRequest('topic_type','','post');
        $iBlogId    = getRequest('idBlog',null,'post');
        if (!$aTagGroups = $this->PluginTagextender_Tagextender_GetTagGroups($sTopicType,$iBlogId)) {
            return;
        }
        $oViewer = $this->Viewer_GetLocalViewer();
        $oViewer->Assign('aTagGroups',$aTagGroups);
        $sTagGroups = $oViewer->Fetch(Plugin::GetTemplatePath(__CLASS__) . 'inject.topic.form.tpl');
        $this->Viewer_AssignAjax('sTagGroups',$sTagGroups);
    }
}
?>
