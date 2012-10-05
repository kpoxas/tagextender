{include file='header.tpl'}
<h2 class="page-header">
    <a href="{router page='admin'}">{$aLang.admin_header}</a> <span>&raquo;</span>
    <a href="{router page='admin'}tagextender">{$aLang.plugin.tagextender.group_title}</a> <span>&raquo;</span>
    {if $aParams[0]=='new'}
        {$aLang.plugin.tagextender.group_add}
    {else}
        {$aLang.plugin.tagextender.group_edit_title|ls_lang:"name%%`$sTitle`"}
    {/if}
</h2>

<form action="" method="POST" enctype="multipart/form-data" id="form-topic-add" class="wrapper-content">

    <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}"/>

    <p><label for="name">{$aLang.plugin.tagextender.group_field_name}</label>
        <input type="text" id="name" name="name" value="{$_aRequest.name}"
               class="input-text input-width-full"/>
        <small class="note">{$aLang.plugin.tagextender.note_group_name}</small>
    </p>

    <table>
        <tbody>
            <tr>
                <td valign=top>
                    <label for="keyword">{$aLang.plugin.tagextender.group_field_keyword}</label>
                    <input type="text" id="keyword" name="keyword" value="{$_aRequest.keyword}"
                           class="input-text input-width-300"/>
                    <small class="note">{$aLang.plugin.tagextender.note_group_keyword}</small>
                </td>
                <td valign=top>
                    <label for="keyword">{$aLang.plugin.tagextender.group_field_order_num}</label>
                    <input type="text" id="order_num" name="order_num" value="{$_aRequest.order_num}"
                           class="input-text input-width-50"/>

                </td>
            </tr>
        </tbody>
    </table>

    <table width="100%">
        <tbody>
        <tr>
            <td valign=top>
                <label for="enabled">{$aLang.plugin.tagextender.group_field_enabled}
                    <input
                            type="checkbox"
                            name="enabled"
                            id="enabled"
                            class="input-checkbox"
                            {if $_aRequest.enabled}checked=checked{/if}
                            value="1"/>
                </label>
            </td>
            <td valign=top>
                <label for="allow_empty">{$aLang.plugin.tagextender.group_field_allow_empty}
                    <input
                            type="checkbox"
                            name="allow_empty"
                            id="allow_empty"
                            class="input-checkbox"
                            {if $_aRequest.allow_empty}checked=checked{/if}
                            value="1"/>
                </label>
            </td>
        </tr>
        <tr>
            <td valign=top>
                <label for="min">{$aLang.plugin.tagextender.group_field_min}
                <input type="text" id="min" name="min" value="{$_aRequest.min}"
                       class="input-text input-width-50"/>
                </label>
            </td>
            <td valign=top>
                <label for="max">{$aLang.plugin.tagextender.group_field_max}
                <input type="text" id="max" name="max" value="{$_aRequest.max}"
                       class="input-text input-width-50"/>
                </label>
            </td>
        </tr>

        <tr>
            <td valign=top>
                <label for="count">{$aLang.plugin.tagextender.group_field_count}
                <input type="text" id="count" name="count" value="{$_aRequest.count}"
                       class="input-text input-width-50"/>
                </label>

            </td>
            <td valign=top>
                <label for="sep">{$aLang.plugin.tagextender.group_field_sep}
                <input type="text" id="sep" name="sep" value="{$_aRequest.sep}"
                       class="input-text input-width-50"/>
                </label>
            </td>
        </tr>

        <tr>
            <td valign=top>
                <h3>{$aLang.plugin.tagextender.note_topic_show_options}</h3>
                {html_checkboxes
                    name='topic_types'
                    values=$aTopicTypes
                    output=$aTopicTypes
                    selected=$_aRequest.topic_types
                    class="input-checkbox"}


            </td>
            <td valign=top>
                <h3>{$aLang.plugin.tagextender.note_blog_show_options}</h3>
                {html_checkboxes
                    name='blog_types'
                    values=$aBlogTypes
                    output=$aBlogTypes
                    selected=$_aRequest.blog_types
                    class="input-checkbox"}
            </td>
        </tr>

        </tbody>
    </table>

    <br>

    <button type="submit" name="submit_save" id="submit_save"
            class="button button-primary">{$aLang.plugin.tagextender.group_save}</button>

    <input type="submit"
           name="submit_delete"
           value="{$aLang.plugin.tagextender.group_delete}"
           class="button"
           {assign var=msg_delete value=$aLang.plugin.tagextender.msg_delete|ls_lang:"name%%`$sTitle`"}
           onclick="return confirm('{$msg_delete}');" />
</form>

{include file='footer.tpl'}