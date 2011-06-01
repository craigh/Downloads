{ajaxheader modname='Downloads' ui=true}
<h3>{gt text='Download Items'}</h3>

{insert name="getstatusmsg"}

{modulelinks modname='Downloads' type='user'}

<table class="z-datatable">
    <thead>
        <tr>
            <td>{gt text='Title'}</td>
            <td>{gt text='Version'}</td>
            <td>{gt text='Description'}</td>
            <td>{gt text='Submittor'}</td>
            <td>{gt text='Categories'}</td>
            <td>{gt text='Actions'}</td>
        </tr>
    </thead>
    <tbody>
        {foreach from=$downloads item='d'}
            <tr class="{cycle values="z-odd,z-even"}">
                <td>{$d.title|safetext}</td>
                <td>{$d.version|safetext}</td>
                <td>{$d.description|safetext}</td>
                <td>{$d.submitter|safetext}</td>
                <td>
                    {foreach from=$d.Categories item='c'}
                        {$c.Category.name|safetext}
                    {/foreach}
                </td>
                <td>
                    <a href="{$d.url}">{img modname='core' set='icons/extrasmall' src='download.png' __title='Download' __alt='Download' class='tooltips'}</a>
                    <a href="{modurl modname="Downloads" type="user" func="edit" id=$d.lid}">{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Edit' __alt='Edit' class='tooltips'}</a>
                </td>
            </tr>
        {/foreach}
    </tbody>
</table>
<script type="text/javascript">
// <![CDATA[
    Zikula.UI.Tooltips($$('.tooltips'));
// ]]>
</script>