{if $oTopic->getTagGroups()}
<div class="topic-footer">
    {foreach $oTopic->getTagsGroupedArray() as $aTags}
    {assign var='aTagGroups' value=$oTopic->getTagGroups()}
        <ul class="topic-tags">
            <li>{$aTagGroups[$aTags@key]->name}:</li>
            {strip}
            {foreach $aTags as $sTag}
                <li>{if !$sTag@first}, {/if}<a rel="tag" href="{router page='tag'}{$sTag|escape:'url'}/">{$sTag|escape:'html'}</a></li>
            {/foreach}
            {/strip}
        </ul>
    {/foreach}
</div>
{/if}

