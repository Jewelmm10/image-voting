<?php
/**
 * Plugin Name: Image Voting
 * Description: A plugin to create an image voting contest using post meta for vote counts.
 * Version: 1.0
 * Author: Mohammad Jewel
 */

// Register Custom Post Type
function icp_register_post_type() {
    $labels = [
        'name'          => 'Image Contests',
        'singular_name' => 'Image Contest',
        'add_new_item'  => 'Add New Image Contest',
        'edit_item'     => 'Edit Image Contest',
    ];
    $args = [
        'labels'        => $labels,
        'public'        => true,
        'supports'      => ['title', 'thumbnail'],
        'has_archive'   => true,
        'menu_position' => 5,
        'menu_icon'     => 'dashicons-format-image',
    ];
    register_post_type('image_contest', $args);
}
add_action('init', 'icp_register_post_type');

// Enqueue Scripts and Styles
function icp_enqueue_scripts() {
    wp_enqueue_script('icp-voting-js', plugin_dir_url(__FILE__) . 'icp-voting.js', ['jquery'], '1.0', true);
    wp_localize_script('icp-voting-js', 'icp_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('icp_nonce'),
    ]);
    wp_enqueue_style('icp-styles', plugin_dir_url(__FILE__) . 'icp-styles.css');
}
add_action('wp_enqueue_scripts', 'icp_enqueue_scripts');

// Shortcode: Display Voting Cards
function icp_display_voting_cards($atts) {
    $args = [
        'post_type'      => 'image_contest',
        'posts_per_page' => -1,
    ];
    $query = new WP_Query($args);

    ob_start();
    if ($query->have_posts()) {
        $total_votes = 0;

        // Calculate total votes for all posts
        while ($query->have_posts()) {
            $query->the_post();
            $total_votes += (int) get_post_meta(get_the_ID(), '_icp_votes', true);
        }
        wp_reset_postdata();

        echo '<div class="voting-inner">';
        while ($query->have_posts()) {
            $query->the_post(); 
            $post_id = get_the_ID();
            $vote_count = get_post_meta($post_id, '_icp_votes', true) ?: 0;
            ?>            
            <div class="voting-item" data-post-id="<?php echo $post_id; ?>">
                <div class="big-cover">
                <?php 
                    if (has_post_thumbnail()) {
                        the_post_thumbnail('medium'); // Display featured image
                    } else {
                        echo '<img src="https://placehold.co/400x500">';
                    }
                ?>
                </div>                    
                <h3 class="name text-xl my-3 text-primary"><?php the_title(); ?></h3>
                <button class="btn-primary voting" data-post-id="<?php echo $post_id; ?>">Vote</button>                    
                <div class="vote-result p-4 text-center">
                    <span class="vote-count"><?php echo $vote_count; ?></span>/<span class="total-votes"><?php echo $total_votes; ?></span>
                </div>
            </div>
            <?php
        }
        echo '</div>';

        wp_reset_postdata();
    } else {
        echo '<p>No image contests found.</p>';
    }
    return ob_get_clean();
}
add_shortcode('icp_voting', 'icp_display_voting_cards');

// Handle Voting
function icp_handle_vote() {
    check_ajax_referer('icp_nonce', 'nonce');

    $post_id = intval($_POST['post_id']);
    if (!$post_id || get_post_type($post_id) !== 'image_contest') {
        wp_send_json_error(['message' => 'Invalid post ID']);
    }

    // Update this post's votes
    $current_votes = get_post_meta($post_id, '_icp_votes', true) ?: 0;
    $new_votes = $current_votes + 1;
    update_post_meta($post_id, '_icp_votes', $new_votes);

    // Calculate total votes
    $total_votes = 0;
    $query = new WP_Query(['post_type' => 'image_contest', 'posts_per_page' => -1]);
    while ($query->have_posts()) {
        $query->the_post();
        $total_votes += (int) get_post_meta(get_the_ID(), '_icp_votes', true);
    }
    wp_reset_postdata();

    wp_send_json_success(['votes' => $new_votes, 'total_votes' => $total_votes]);
}
add_action('wp_ajax_icp_vote', 'icp_handle_vote');
add_action('wp_ajax_nopriv_icp_vote', 'icp_handle_vote');
