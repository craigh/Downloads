{ajaxheader modname='Downloads' ui=true}
{include file="admin/menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' set='icons/large' src='download_manager.png'}</div>
    <h2>{gt text="Downloads Main"}</h2>
    <div>

    {insert name="getstatusmsg"}

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
                        <td>{$d.category.title|safetext}</td>
                        <td>
                            <a href="{modurl modname="Downloads" type="user" func="prepHandOut" lid=$d.lid}">{img modname='core' set='icons/extrasmall' src='download.png' __title='Download' __alt='Download' class='tooltips'}</a>
                            <a href="{modurl modname="Downloads" type="admin" func="edit" id=$d.lid}">{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Edit' __alt='Edit' class='tooltips'}</a>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
// <![CDATA[
    Zikula.UI.Tooltips($$('.tooltips'));
// ]]>
</script>