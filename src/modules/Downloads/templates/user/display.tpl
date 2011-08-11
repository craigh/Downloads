{ajaxheader modname='Downloads' ui=true}
<h3>{gt text='Download Items'}</h3>

{insert name="getstatusmsg"}
<div id='downloads_item'>
    <h3><a href="{modurl modname="Downloads" type="user" func="prepHandOut" lid=$item.lid}">{img modname='core' set='icons/large' src='download.png' __title='Download' __alt='Download' class='tooltips'}
    &nbsp;&nbsp;{$item.title|safetext}</a></h3>
</div>
<div id='downloads_item_details'>
    <h4>{gt text='Category'}: <a href="{modurl modname="Downloads" type="user" func="view" cid=$item.cid}">{$item.category.title|safetext}</a></h4>
    <p><strong>{gt text='Description'}</strong>: {$item.description|safehtml}</p>
    <ul>
        <li><strong>{gt text='Filetype'}</strong>: {$filetype}</li>
        <li><strong>{gt text='Filesize'}</strong>: {$item.filesize} {gt text='Kilobytes (Kb)'}</li>
        <li><strong>{gt text='Version'}</strong>: {$item.version|safetext}</li>
        <li><strong>{gt text='Creation date'}</strong>: {$item.date|dateformat|safetext}</li>
        <li><strong>{gt text='Hits'}</strong>: {$item.hits}</li>
        <li><strong>{gt text='Submitter'}</strong>: {$item.submitter|safetext}
            <!--[if $item.homepage && $item.email]-->
            <ul>
                <!--[if $item.email]-->
                <li>{gt text='Email'}: {$item.email|safehtml}</li>
                <!--[/if]-->
                <!--[if $item.homepage]-->
                <li>{gt text='homepage'}: <a href='{$item.homepage|safehtml}'>{$item.homepage|safehtml}</a></li>
                <!--[/if]-->
            </ul>
            <!--[/if]-->
        </li>
    </ul>
    <br />
    <a href="{modurl modname="Downloads" type="user" func="view"}">{img modname='core' set='icons/medium' src='folder_red.png' __title='Root category' __alt='Root category' class='z-middle tooltips'}&nbsp;&nbsp;{gt text='Back to Root category'}</a>
    <a href="{modurl modname="Downloads" type="user" func="view" category=$item.cid}">{img modname='core' set='icons/medium' src='folder_blue.png' __title='Category' __alt='Category' class='z-middle tooltips'}&nbsp;&nbsp;{gt text="Back to category '%s'" tag1=$item.cid|getcategorynamefromid|safetext}</a>
</div>
{notifydisplayhooks eventname='downloads.ui_hooks.downloads.display_view' id=$item.lid}
<script type="text/javascript">
    // <![CDATA[
    Zikula.UI.Tooltips($$('.tooltips'));
    // ]]>
</script>