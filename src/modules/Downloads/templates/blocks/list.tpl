<ul>
{foreach from=$downloads item='d'}
    <li>
        <a href="{modurl modname="Downloads" type="user" func="prepHandOut" lid=$d.lid}">{$d.title|safetext} (v{$d.version|safetext})[{$d.filesize|safetext}Kb]</a>
        <a href="{modurl modname="Downloads" type="user" func="display" lid=$d.lid}">{img modname='core' set='icons/extrasmall' src='info.png' __title='View' __alt='View'}</a>
    </li>
    {* other array values available in a template override *}
    {*$d.hits*}
    {*$d.description*}
    {*$d.submitter*}
    {*$d.category.title*}
    {*$d.url is available but don't use it! - use prepHandOut method above*}
    
{foreachelse}
    <li>{gt text='No downloads available'}</li>
{/foreach}
</ul>
