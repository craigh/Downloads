{ajaxheader modname='Downloads' ui=true}
<h3>{gt text='Download Items'}</h3>

{insert name="getstatusmsg"}
{modulelinks type='User'}
<div id='downloads_item'>
    <h3><a href="{modurl modname="Downloads" type="user" func="prepHandOut" lid=$item->getLid()}">{img modname='core' set='icons/large' src='download.png' __title='Download' __alt='Download' class='tooltips'}
    &nbsp;&nbsp;{$item->getTitle()|safetext}</a></h3>
</div>
<div id='downloads_item_details'>
    <h4>{gt text='Category'}: {getcategoryfullpath cid=$item->getCategory()}</h4>
    <p><strong>{gt text='Description'}</strong>: {$item->getDescription()|safehtml}</p>
    <ul>
        <li><strong>{gt text='Filetype'}</strong>: {$filetype}</li>
        <li><strong>{gt text='Filesize'}</strong>: {$item->getFilesize()} {gt text='Kilobytes (Kb)'}</li>
        <li><strong>{gt text='Version'}</strong>: {$item->getVersion()|safetext}</li>
        <li><strong>{gt text='Creation date'}</strong>: {assign var='date' value=$item->getDate()}{$date->format('Y-m-d h:m:s')|dateformat|safetext}</li>
        <li><strong>{gt text='Hits'}</strong>: {$item->getHits()}</li>
        <li><strong>{gt text='Submitter'}</strong>: {$item->getSubmitter()|safetext}
            {if $item->getHomepage() && $item->getEmail()}
            <ul>
                {if $item->getEmail()}
                <li>{gt text='Email'}: {$item->getEmail()|safehtml}</li>
                {/if}
                {if $item->getHomepage()}
                <li>{gt text='homepage'}: <a href='{$item->getHomepage()|safehtml}'>{$item->getHomepage()|safehtml}</a></li>
                {/if}
            </ul>
            {/if}
        </li>
		{if $item->getScreenshot()}
			<li><strong>{gt text='Screenshot'}:</strong></li>
			<center><img src="{$baseurl}{$ssPath}{$item->getScreenshot()}"></center>
		{/if}
    </ul>
</div>
{checkpermissionblock component='Downloads::' instance='::' level=ACCESS_EDIT}
    <p id="downloads_item_editlink"><a href="{modurl modname='Downloads' type='admin' func='edit' id=$item->getLid()}" title="{gt text='Edit this download'}">{gt text='Edit'}</a></p>
{/checkpermissionblock}
{notifydisplayhooks eventname='downloads.ui_hooks.downloads.display_view' id=$item->getLid()}
<script type="text/javascript">
    // <![CDATA[
    Zikula.UI.Tooltips($$('.tooltips'));
    // ]]>
</script>
