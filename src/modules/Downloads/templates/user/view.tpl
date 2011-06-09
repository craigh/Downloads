{ajaxheader modname='Downloads' ui=true}
<h3>{gt text='Download Items'} :: {gt text='Category'}: {$cid|getcategorynamefromid|safetext}</h3>

{insert name="getstatusmsg"}
<table class="z-datatable">
    <tbody>
        {if ($cid <> 0)}
        <tr class='downloads-rootparent'>
            <td><a href="{modurl modname="Downloads" type="user" func="view" category=0}">{img modname='core' set='icons/medium' src='folder_red.png' __title='View root category' __alt='View root category' class='tooltips'}</a></td>
            <td colspan='2'>{gt text='Back to'} <strong>{gt text='Root'}</strong></td>
        </tr>
        {/if}
        {if ($categoryinfo.0.pid <> 0)}
        <tr class='downloads-parent'>
            <td><a href="{modurl modname="Downloads" type="user" func="view" category=$categoryinfo.0.pid}">{img modname='core' set='icons/medium' src='folder_blue.png' __title='View parent category' __alt='View parent category' class='tooltips'}</a></td>
            <td colspan='2'>{gt text='Back to parent: '} <strong>{$categoryinfo.0.pid|getcategorynamefromid|safetext}</strong></td>
        </tr>
        {/if}
        {foreach from=$subcategories item='sc'}
        <tr class="{cycle values="z-odd,z-even"}">
            <td><a href="{modurl modname="Downloads" type="user" func="view" category=$sc.cid}">{img modname='core' set='icons/medium' src='folder_green.png' __title='View subcategory' __alt='View subcategory' class='tooltips'}</a></td>
            <td>{$sc.title|safetext}</td>
            <td>{$sc.description|truncate:60|safetext}</td>
        </tr>
        {foreachelse}
        <tr class='z-datatableempty'><td colspan='3' class='z-center'>{gt text='No subcategories in category "%1$s".' tag1=$cid|getcategorynamefromid|safetext}</td></tr>
        {/foreach}
    </tbody>
</table>
{if ($cid <> 0)}
<table class="z-datatable">
    <thead>
        <tr>
            <td><a class='{$sort.class.title}' href='{$sort.url.title|safetext}'>{gt text='Title'}</a></td>
            <td>{gt text='Version'}</td>
            <td><a class='{$sort.class.hits}' href='{$sort.url.hits|safetext}'>{gt text='Downloads'}</a></td>
            <td>{gt text='Description'}</td>
            <td><a class='{$sort.class.submitter}' href='{$sort.url.submitter|safetext}'>{gt text='Submitter'}</a></td>
            <td>{gt text='Categories'}</td>
            <td>{gt text='Actions'}</td>
        </tr>
    </thead>
    <tbody>
        {foreach from=$downloads item='d'}
        <tr class="{cycle values="z-odd,z-even"}">
            <td>{$d.title|safetext}</td>
            <td>{$d.version|safetext}</td>
            <td>{$d.hits|safetext}</td>
            <td>{$d.description|truncate:60|safehtml}</td>
            <td>{$d.submitter|safetext}</td>
            <td>{$d.category.title|safetext}</td>
            <td>
                <a href="{modurl modname="Downloads" type="user" func="display" lid=$d.lid}">{img modname='core' set='icons/extrasmall' src='14_layer_visible.png' __title='View' __alt='View' class='tooltips'}</a>
                <a href="{modurl modname="Downloads" type="user" func="prepHandOut" lid=$d.lid}">{img modname='core' set='icons/extrasmall' src='download.png' __title='Download' __alt='Download' class='tooltips'}</a>
            </td>
        </tr>
        {foreachelse}
        <tr class='z-datatableempty'><td colspan='6' class='z-center'>{gt text='No records in category "%1$s". Try a sub-category, or a different category.' tag1=$cid|getcategorynamefromid|safetext}</td></tr>
        {/foreach}
    </tbody>
</table>
{pager rowcount=$rowcount limit=$modvars.Downloads.perpage posvar='startnum'}
{/if}
<script type="text/javascript">
// <![CDATA[
    Zikula.UI.Tooltips($$('.tooltips'));
// ]]>
</script>