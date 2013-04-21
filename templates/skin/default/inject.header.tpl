<script type="text/javascript">
	ls.registry.set('plugin.tagextender.topic_type',{json var=$sAction});
	ls.registry.set('plugin.tagextender.hide_native_tags',{json var=$oConfig->Get('plugin.tagextender.hide_native_tags')});
    {if $_aRequest.topic_tags_grouped}
	ls.registry.set('plugin.tagextender.topic_tags_grouped',{json var=$_aRequest.topic_tags_grouped});
    {/if}
</script>