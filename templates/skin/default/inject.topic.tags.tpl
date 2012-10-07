{assign var='aTagGroups' value=$oTopic->getTagGroups()}
{if $aTagGroups}
<div class="topic-footer">
    {foreach $oTopic->getTagsGroupedArray() as $aTags}
    {assign var='oTagGroup' value=$aTagGroups[$aTags@key]}
        <ul class="topic-tags">
            <li>{$oTagGroup->name}:</li>
            {strip}
            {foreach $aTags as $sTag}
                <li>{if !$sTag@first}, {/if}<a rel="tag" href="{router page='tag'}{$oTagGroup->keyword}/{$sTag|escape:'url'}/">{$sTag|escape:'html'}</a></li>
            {/foreach}
            {/strip}
        </ul>
    {/foreach}
</div>
{/if}

