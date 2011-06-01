{include file="admin/menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' set='icons/large' src='download_manager.png'}</div>
    <h2>{gt text="New Download"}&nbsp;({gt text="version"}&nbsp;{$modinfo.version})</h2>
    {form cssClass="z-form" enctype="multipart/form-data"}
        <fieldset>
            <legend>{gt text='New Download'}</legend>

            {formvalidationsummary}

            <div class="z-formrow">
                {formlabel for="title" __text="Download title"}
                {formtextinput id="title" mandatory=true maxLength=100}
            </div>

            <div class="z-formrow">
                {formlabel for="filename" __text="Choose file for upload"}
                {formuploadinput id="filename" maxLength=255}
            </div>

            <div class="z-formnote">
                <strong>{gt text='OR'}</strong>
            </div>

            <div class="z-formrow">
                {formlabel for="url" __text="Download link"}
                {formtextinput id="url" maxLength=254}
            </div>

            <div class="z-formrow">
                {formlabel for="description" __text="Description"}
                {formtextinput textMode='multiline' id="description" mandatory=true}
            </div>

            <div class="z-formrow">
                {formlabel for="submitter" __text="Submitted by"}
                {formtextinput id="submitter" mandatory=true maxLength=60}
            </div>

            <div class="z-formrow">
                {formlabel for="email" __text="Email address"}
                {formtextinput id="email" maxLength=100}
            </div>

            <div class="z-formrow">
                {formlabel for="homepage" __text="Homepage"}
                {formtextinput id="homepage" maxLength=200}
            </div>

            <div class="z-formrow">
                {formlabel for="version" __text="Version"}
                {formtextinput id="version" maxLength=5}
            </div>
        </fieldset>

        {if $registries}
        <fieldset>
            <legend>{gt text='Categories'}</legend>

            {foreach from=$registries item="registryCid" key="property"}
                <div class="z-formrow">
                    {formlabel for="category_`$property`" __text="Category"}
                    {formcategoryselector id="category_`$property`" category=$registryCid dataField=$property enableDoctrine=true editLink=false}
                </div>
            {/foreach}
        </fieldset>
        {/if}

        <div class="z-buttons z-formbuttons">
            {formbutton class='z-bt-ok' commandName='create' __text='Save'}
            {formbutton class='z-bt-cancel' commandName='cancel' __text='Cancel'}
            {formbutton class="z-bt-delete z-btred" commandName="delete" __text="Delete" __confirmMessage='Delete'}
        </div>
    {/form}
</div>