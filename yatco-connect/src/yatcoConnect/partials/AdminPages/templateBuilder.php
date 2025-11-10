<?php
if( isset($_GET['edit_template']) && $_GET['edit_template'] != '' ){
    $template_manager = new yatcoConnect_TemplateManager;
    $is_default = $template_manager->is_default_template($_GET['edit_template']);
    if($is_default){
        if (!isset($_GET['edit_as']) || $_GET['edit_as'] != 'author') {
            ?>
            <div class="wrap yatco-api-builder">
                <div class="grey-box p-4">
                    <h1><?php echo "Cannot edit default templates!"; ?></h1>
                </div>
            </div>
            <?php
            return;
        }
    }
}
?>

<div class="wrap yatco-api-builder">
    <div class="grey-box p-4">
        <h1>API Template Builder</h1>
    </div>
    <div class="editor__container">
        <!-- Top Panel -->
        <div class="panel__top">
            <div class="panel__basic-actions"></div>
        </div>

        <div class="editor__main">
            <!-- Sidebar for Blocks -->
            <div id="blocks" class="editor__sidebar"></div>

            <!-- Editor Canvas -->
            <div id="gjs" class="editor__canvas"></div>

            <!-- Sidebar for Styles 
            <div id="styles-container" class="editor__sidebar"></div>-->
            <div class="panel__right" id="panel__right">
                <div class="panel__right-buttons">
                    <!-- Buttons will be rendered by GrapesJS -->
                </div>
                <div class="panel__right-content">
                    <div class="styles-container"></div>
                    <div class="traits-container" style="display: none;">
                        <div class="gjs-selectors-container"></div>
                    </div>
                    <div class="layers-container" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="grey-box p-4">
        <p>Template name: <input type="text" id="yt_template_name" name="yt_template_name" value="" /></p>
        <p>No. of cards in a row (grid view only): <input type="number" style="width:60px;" id="yt_cards_count" name="yt_cards_count" value="" /></p>
        <?php
        
        if ( isset( $_GET['edit_as'] ) && $_GET['edit_as'] == 'author' ) {
            $checked = $is_default ? "checked" : "";

            echo "<input type='checkbox' id='is_default' $checked> Is Default";
        }

        ?>
    </div>
</div>