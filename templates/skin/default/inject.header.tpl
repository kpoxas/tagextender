{if $sEvent=='add' || $sEvent=='edit'}
<script type="text/javascript">
	ls.registry.set('plugin.tagextender.topic_type',{json var=$sAction});
    {if $_aRequest.topic_tags_grouped}
	ls.registry.set('plugin.tagextender.topic_tags_grouped',{json var=$_aRequest.topic_tags_grouped});
    {/if}
</script>
{/if}
