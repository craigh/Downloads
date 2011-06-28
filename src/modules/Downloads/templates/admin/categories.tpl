{ajaxheader modname='Downloads' ui=true}
{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Downloads category list'}</h3>
</div>

{insert name="getstatusmsg"}

<table class="z-datatable">
    <thead>
        <tr>
            <td>{gt text='Title'}</td>
            <td>{gt text='Description'}</td>
            <td>{gt text='Parent'}</td>
            <td>{gt text='Action'}</td>
        </tr>
    </thead>
    <tbody>
        {foreach from=$cats item='c'}
        <tr class="{cycle values="z-odd,z-even"}">
            <td>{$c.title|safetext}</td>
            <td>{$c.description|truncate:60|safetext}</td>
            <td>{$c.pid|getcategorynamefromid|safetext}</td>
            <td>
                <a href="{modurl modname="Downloads" type="admin" func="editCategory" id=$c.cid}">{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Edit' __alt='Edit' class='tooltips'}</a>
            </td>
        </tr>
        {foreachelse}
        <tr class='z-datatableempty'>
            <td colspan='4'>{gt text='There are no categories to display.'}</td>
        </tr>
        {/foreach}
    </tbody>
</table>

{adminfooter}
<script type="text/javascript">
// <![CDATA[
    Zikula.UI.Tooltips($$('.tooltips'));
// ]]>
</script>