{ajaxheader modname='Downloads' ui=true}
<h3>{gt text='Download Items'}</h3>

{insert name="getstatusmsg"}
<div id='downloads_item'>
    <h3><a href="{modurl modname="Downloads" type="user" func="prepHandOut" lid=$item.lid}">{img modname='core' set='icons/large' src='download.png' __title='Download' __alt='Download' class='tooltips'}
    &nbsp;&nbsp;{$item.title|safetext}</a></h3>
</div>
<div id='downloads_item_details'>
    <h4>{gt text='Category'}: {$item.category.title|safetext}</h4>
    <p><strong>{gt text='Description'}</strong>: {$item.description|safehtml}</p>
    <ul>
        <li><strong>{gt text='Filetype'}</strong>: {$filetype}</li>
        <li><strong>{gt text='Filesize'}</strong>: {$item.filesize} {gt text='Kilobytes (Kb)'}</li>
        <li><strong>{gt text='Version'}</strong>: {$item.version|safetext}</li>
        <li><strong>{gt text='Creation date'}</strong>: {$item.date|dateformat|safetext}</li>
        <li><strong>{gt text='Hits'}</strong>: {$item.hits}</li>
        <li><strong>{gt text='Submitter'}</strong>: {$item.submitter|safetext}
            <ul>
                <li>{gt text='Email'}: {$item.email|safehtml}</li>
                <li>{gt text='homepage'}: <a href='{$item.homepage|safehtml}'>{$item.homepage|safehtml}</a></li>
            </ul>
        </li>
    </ul>
    <br />
    <a href="{modurl modname="Downloads" type="user" func="view"}">{img modname='core' set='icons/large' src='windowlist.png' __title='Listview' __alt='Listview' class='z-middle tooltips'}&nbsp;&nbsp;{gt text='Back to list view'}</a>
</div>
<script type="text/javascript">
// <![CDATA[
    Zikula.UI.Tooltips($$('.tooltips'));
// ]]>
</script>