<?php
/**
 * The main template file for Arab Board Event 2025
 */

// Check if this is the home page and redirect to front-page template
if (is_home() || is_front_page()) {
    include(get_template_directory() . '/front-page.php');
    return;
}

get_header(); ?>

<main id="main" class="site-main">
    
    <div class="container">
        <div class="content-area">
            
            <?php if (have_posts()) : ?>
                
                <header class="page-header">
                    <h1 class="page-title">
                        <?php
                        if (is_home() && !is_front_page()) :
                            single_post_title();
                        elseif (is_archive()) :
                            the_archive_title();
                        elseif (is_search()) :
                            printf(__('Search Results for: %s', 'arab-board-event'), '<span>' . get_search_query() . '</span>');
                        else :
                            _e('Latest Posts', 'arab-board-event');
                        endif;
                        ?>
                    </h1>
                    
                    <?php if (is_archive()) : ?>
                        <div class="archive-description">
                            <?php the_archive_description(); ?>
                        </div>
                    <?php endif; ?>
                </header>

                <div class="posts-container">
                    <?php while (have_posts()) : the_post(); ?>
                        
                        <article id="post-<?php the_ID(); ?>" <?php post_class('post-item'); ?>>
                            
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="post-content">
                                <header class="entry-header">
                                    <h2 class="entry-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                    
                                    <div class="entry-meta">
                                        <span class="post-date">
                                            <i class="icon-calendar"></i>
                                            <?php echo get_the_date(); ?>
                                        </span>
                                        
                                        <span class="post-author">
                                            <i class="icon-user"></i>
                                            <?php the_author(); ?>
                                        </span>
                                        
                                        <?php if (has_category()) : ?>
                                        <span class="post-categories">
                                            <i class="icon-folder"></i>
                                            <?php the_category(', '); ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </header>
                                
                                <div class="entry-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <footer class="entry-footer">
                                    <a href="<?php the_permalink(); ?>" class="read-more-btn">
                                        <?php _e('Read More', 'arab-board-event'); ?>
                                    </a>
                                    
                                    <?php if (has_tag()) : ?>
                                    <div class="post-tags">
                                        <?php the_tags('<span class="tags-label">' . __('Tags:', 'arab-board-event') . '</span> ', ', '); ?>
                                    </div>
                                    <?php endif; ?>
                                </footer>
                            </div>
                            
                        </article>
                        
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <nav class="posts-navigation">
                    <?php
                    the_posts_pagination(array(
                        'mid_size' => 2,
                        'prev_text' => __('Previous', 'arab-board-event'),
                        'next_text' => __('Next', 'arab-board-event'),
                    ));
                    ?>
                </nav>

            <?php else : ?>
                
                <section class="no-results">
                    <header class="page-header">
                        <h1 class="page-title"><?php _e('Nothing Found', 'arab-board-event'); ?></h1>
                    </header>

                    <div class="page-content">
                        <?php if (is_home() && current_user_can('publish_posts')) : ?>
                            <p>
                                <?php
                                printf(
                                    wp_kses(
                                        __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'arab-board-event'),
                                        array(
                                            'a' => array(
                                                'href' => array(),
                                            ),
                                        )
                                    ),
                                    esc_url(admin_url('post-new.php'))
                                );
                                ?>
                            </p>
                        <?php elseif (is_search()) : ?>
                            <p><?php _e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'arab-board-event'); ?></p>
                            <?php get_search_form(); ?>
                        <?php else : ?>
                            <p><?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'arab-board-event'); ?></p>
                            <?php get_search_form(); ?>
                        <?php endif; ?>
                    </div>
                </section>

            <?php endif; ?>
            
        </div>
        
        <!-- Sidebar -->
        <?php if (is_active_sidebar('sidebar-1')) : ?>
            <aside class="widget-area">
                <?php dynamic_sidebar('sidebar-1'); ?>
            </aside>
        <?php endif; ?>
        
    </div>

</main>

<?php get_footer(); ?>