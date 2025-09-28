<?php
/**
 * Template for displaying search form
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="search-form-inner">
        <label class="screen-reader-text" for="search-field-<?php echo uniqid(); ?>">
            <?php _e('Search for:', 'arab-board-event'); ?>
        </label>
        
        <input type="search" 
               id="search-field-<?php echo uniqid(); ?>" 
               class="search-field" 
               placeholder="<?php echo esc_attr_x('البحث...', 'placeholder', 'arab-board-event'); ?>" 
               value="<?php echo get_search_query(); ?>" 
               name="s" 
               autocomplete="off" />
        
        <button type="submit" class="search-submit">
            <span class="search-icon">🔍</span>
            <span class="screen-reader-text"><?php _e('Search', 'arab-board-event'); ?></span>
        </button>
    </div>
</form>