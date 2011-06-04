{include file="admin/menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' set='icons/large' src='download_manager.png'}</div>
    <h2>{gt text="New Category"}&nbsp;({gt text="version"}&nbsp;{$modinfo.version})</h2>
    {form cssClass="z-form" enctype="multipart/form-data"}
        <fieldset>
            <legend>{gt text='New Category'}</legend>

            {formvalidationsummary}

            <div class="z-formrow">
                {formlabel for="title" __text="Category title"}
                {formtextinput id="title" mandatory=true maxLength=100}
            </div>

            <div class="z-formrow">
                {formlabel for="description" __text="Description"}
                {formtextinput textMode='multiline' id="description" mandatory=true}
            </div>

            <div class="z-formrow">
                {formlabel for="pid" __text="Child of:"}
                {formdropdownlist id="pid" items=$categories}
            </div>

        </fieldset>

        <div class="z-buttons z-formbuttons">
            {formbutton class='z-bt-ok' commandName='create' __text='Save'}
            {formbutton class='z-bt-cancel' commandName='cancel' __text='Cancel'}
            {formbutton class="z-bt-delete z-btred" commandName="delete" __text="Delete" __confirmMessage='Delete'}
        </div>
    {/form}
</div>