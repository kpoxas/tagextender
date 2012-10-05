<div id="topic_tags_grouped">
{foreach $aTagGroups as $oTagGroup}
<p>
    <label for="topic_tags_{$oTagGroup->id}">{$oTagGroup->name}:</label>
    <input type="text" id="topic_tags_{$oTagGroup->id}" name="topic_tags_grouped[{$oTagGroup->id}]" value="{$_aRequest.topic_tags_grouped[{$oTagGroup->id}]}" class="input-text input-width-full autocomplete-tags-sep" />
</p>
{/foreach}
</div>