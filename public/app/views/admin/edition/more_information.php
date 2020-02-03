<div class="portlet light" id="more_info">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-edit font-dark"></i>
            <span class="caption-subject font-dark bold uppercase">More information</span>
        </div>
        <div class='btn-group pull-right'>
            <?= fbutton("Apply", "submit", "primary", NULL, "save_only", "more_info"); ?>
        </div>
    </div>
    <div class="portlet-body">
        <?php
//  Event Intro
        echo "<div class='form-group'>";
        echo form_label('Event Intro', 'edition_intro_detail');
        echo form_textarea([
            'name' => 'edition_intro_detail',
            'id' => 'edition_intro_detail',
            'value' => set_value('edition_intro_detail', @$edition_detail['edition_intro_detail'], false),
        ]);

        echo "</div>";

//  Entry Details
        echo "<div class='form-group'>";
        echo form_label('Entry Details', 'edition_entry_detail');
        echo form_textarea([
            'name' => 'edition_entry_detail',
            'id' => 'edition_entry_detail',
            'value' => set_value('edition_entry_detail', @$edition_detail['edition_entry_detail'], false),
        ]);

        echo "</div>";

//  Decription
        echo "<div class='form-group'>";
        echo form_label('General Information', 'edition_general_detail');
        echo form_textarea([
            'name' => 'edition_general_detail',
            'id' => 'edition_description',
            'value' => set_value('edition_general_detail', @$edition_detail['edition_general_detail'], false),
        ]);
        echo "</div>";
        ?>
    </div>
</div>