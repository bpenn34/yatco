<div class="cfp-tab-content" id="cfp-setting-tab">
        <h3>Form Settings</h3>
    
        <p>
        <label for="show_form_responses">
            <input type="checkbox" id="show_form_responses" name="show_form_responses" 
            value="1" <?php checked(get_post_meta($post->ID, 'show_form_responses', true), '1'); ?>>
            Show Form Responses
        </label>
                </p>
        <p>        
        <label for="hide_labels">
            <input type="checkbox" id="hide_labels" name="hide_labels" 
            value="1" <?php checked(get_post_meta($post->ID, 'hide_labels', true), '1'); ?>>
            Hide Labels
        </label>
                </p>
                <p>
       <label for="labels_as_placeholders">
            <input type="checkbox" id="labels_as_placeholders" name="labels_as_placeholders" 
            value="1" <?php checked(get_post_meta($post->ID, 'labels_as_placeholders', true), '1'); ?>>
            Make Labels as Placeholders
        </label>
                </p>

                <p>
       <label for="hide_form_title">
            <input type="checkbox" id="hide_form_title" name="hide_form_title" 
            value="1" <?php checked(get_post_meta($post->ID, 'hide_form_title', true), '1'); ?>>
            Hide Form Title
        </label>
                </p>
                
                
                <p>
       <label for="newsletter_form">
            <input type="checkbox" id="newsletter_form" name="newsletter_form" 
            value="1" <?php checked(get_post_meta($post->ID, 'newsletter_form', true), '1'); ?>>
            This Form is a Newsletter Form
        </label>
                </p>
                <p>
       <label for="inline_form">
            <input type="checkbox" id="inline_form" name="inline_form" 
            value="1" <?php checked(get_post_meta($post->ID, 'inline_form', true), '1'); ?>>
            Make this form inline
        </label>
                </p>                                
        </div>