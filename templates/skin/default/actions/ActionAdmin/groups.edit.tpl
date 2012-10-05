{include file='header.tpl' sidebarPosition=right}
<script type="text/javascript" src="{$sTWCat}/js/category.js"></script>

<h2 class="page-header">
    <a href="{router page='admin'}">{$aLang.admin_header}</a> <span>&raquo;</span> {$aLang.plugin.tagextender.group_title}
    <a href="{router page='admin'}tagextender/add" class="button button-write">{$aLang.plugin.tagextender.group_add}</a>
</h2>


<form action="{$PATH_WEB_CURRENT}" method="post" id="form_tag_groups_list">
    <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
    <input type="submit"
           name="submit_save_changes"
           value="{$aLang.plugin.tagextender.group_save}"
           class="button button-primary"/>

    <input type="submit"
           name="submit_tag_groups_delete"
           value="{$aLang.plugin.tagextender.group_delete_selected}"
           class="button"
           onclick="return (jQuery('.form_tag_groups_checkbox:checked').length==0)?false:confirm('{$aLang.plugin.tagextender.msg_delete_selected}');" />

    <table class="table">
        <thead>
        <tr>
            <th class="cell-checkbox"><input type="checkbox" name="" class="input-checkbox" onclick="ls.tools.checkAll('form_tag_groups_checkbox', this, true);" /></th>
            <th>{$aLang.plugin.tagextender.group_field_id}</th>
            <th>{$aLang.plugin.tagextender.group_field_keyword}</th>
            <th>{$aLang.plugin.tagextender.group_field_name}</th>
            <th>{$aLang.plugin.tagextender.group_field_order_num}</th>
            <th>
                <label>
                    <input
                        type="checkbox"
                        name=""
                        class="input-checkbox"
                        onclick="ls.tools.checkAll('form_tag_groups_enabled_checkbox', this, true);" />
                    {$aLang.plugin.tagextender.group_field_enabled}
                </label>
            </th>
        </tr>
        </thead>

        <tbody>
        {foreach $aTagGroups as $oTagGroup}
        <tr {if $oTagGroup->enabled}class="active"{/if}>
            <td class="cell-checkbox"><input type="checkbox" name="tag_groups_checked[{$oTagGroup->id}]" class="form_tag_groups_checkbox" value="1"/></td>
            <td>{$oTagGroup->id}</td>
            <td>{$oTagGroup->keyword}</td>
            <td>
                <i class="icon-synio-actions-edit"></i>
                <a
                    href="{router page='admin'}tagextender/edit/{$oTagGroup->id}"
                    title='{$aLang.plugin.tagextender.group_edit|ls_lang:"name%%`$oTagGroup->name`"}'>
                    {$oTagGroup->name}
                </a>
            </td>
            <td><input type="text" name="tag_groups_order_num[{$oTagGroup->id}]" value="{$oTagGroup->order_num}" class="input-text input-width-50"></td>
            <td><input
                    type="checkbox"
                    name="tag_groups_enabled[{$oTagGroup->id}]"
                    class="form_tag_groups_enabled_checkbox"
                    {if $oTagGroup->enabled}checked=checked{/if}
                    value="1"/></td>
        </tr>
        {/foreach}
        </tbody>
    </table>

    <input type="submit"
           name="submit_save_changes"
           value="{$aLang.plugin.tagextender.group_save}"
           class="button button-primary"/>

    <input type="submit"
           name="submit_tag_groups_delete"
           value="{$aLang.plugin.tagextender.group_delete_selected}"
           class="button"
           onclick="return (jQuery('.form_tag_groups_checkbox:checked').length==0)?false:confirm('{$aLang.plugin.tagextender.msg_delete_selected}');" />
</form>


{include file='footer.tpl'}
