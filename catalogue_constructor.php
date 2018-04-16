<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
    
    /* Template Name: Catalogue constructor */

?>
<?php 
    $catalog_title = vp_metabox('rehub_catalog.catalog_title_before');
    $catalog_content = vp_metabox('rehub_catalog.catalog_content_before');
    $catalog_fetch = vp_metabox('rehub_catalog.catalog_fetch');
    $catalog_style = vp_metabox('rehub_catalog.catalog_design');
    $catalog_type = vp_metabox('rehub_catalog.catalog_post_type');
    $catalog_tax = vp_metabox('rehub_catalog.catalog_tax');
    $catalog_tax_slug = vp_metabox('rehub_catalog.catalog_tax_slug');
    $catalog_tax_show = vp_metabox('rehub_catalog.catalog_tax_show');
    $catalog_desc = vp_metabox('rehub_catalog.catalog_desc');
    $catalog_fields = vp_metabox('rehub_catalog.catalog_fields');
    $catalog_readmore = vp_metabox('rehub_catalog.catalog_readmore');
    if ($catalog_fetch ==''){$catalog_fetch = '9';}; 
    if ($catalog_style ==''){$module_style = 'grid_three';};  
    if ($catalog_type ==''){$catalog_type = 'post';};         
?>
<?php get_header(); ?>
<!-- CONTENT -->
<div class="rh-container"> 
    <div class="rh-content-wrap clearfix">
	    <!-- Main Side -->
        <div class="main-side page clearfix">          		
            <?php if ($catalog_title =='1') :?><div class="title"><h1><?php the_title(); ?></h1></div><?php endif ;?>
            <?php if ($catalog_content =='1' && !is_paged()) :?>
                <article class="top_rating_text">
                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?><?php the_content(); ?><?php endwhile; endif; ?>
                </article>
                <div class="clearfix"></div>                    
            <?php endif ;?>
            
            <?php if ($catalog_style =='grid_two') : ?>
                <?php  wp_enqueue_script('masonry'); wp_enqueue_script('imagesloaded'); wp_enqueue_script('masonry_init'); ?>
                <div class="masonry_grid_fullwidth col_wrap_two">
            <?php elseif ($catalog_style =='grid_three') : ?>
                <div class="rh-flex-eq-height col_wrap_three">               
            <?php endif ;?>  

            <?php if ( get_query_var('paged') ) { $paged = get_query_var('paged'); } else if ( get_query_var('page') ) {$paged = get_query_var('page'); } else {$paged = 1; } ?>
            <?php $args = array(  
                'paged' => $paged, 
                'post_status' => 'publish', 
                'ignore_sticky_posts' => 1, 
                'posts_per_page' => $catalog_fetch,
                'post_type' => $catalog_type,
            );
            ?> 
            <?php if (!empty ($catalog_tax_slug) && !empty ($catalog_tax)) : ?>
                <?php $args['tax_query'] = array (
                    array(
                        'taxonomy' => $catalog_tax,
                        'field'    => 'slug',
                        'terms'    => $catalog_tax_slug,
                    ),
                );?>

            <?php endif ?>    
            <?php 
            $args = apply_filters('rh_module_args_query', $args);
            $wp_query = new WP_Query($args);
            do_action('rh_after_module_args_query', $wp_query);
            $i=1; if ($wp_query->have_posts()) :?> 
            <?php while ($wp_query->have_posts()) : $wp_query->the_post();?>
                <article class="<?php if ($catalog_style =='grid_three') :?>column_grid col_item<?php elseif($catalog_style =='grid_two') :?>small_post grid_catalog col_item<?php else :?>list_grid<?php endif; ?>">
                    <figure><?php echo re_badge_create('ribbonleft'); ?>             
                        <a href="<?php the_permalink();?>"><?php if ($catalog_style =='grid_three') :?><?php wpsm_thumb ('news_big') ?><?php else :?><?php wpsm_thumb ('grid_news') ?><?php endif;?></a>
                    </figure>
                    <div class="content_constructor">
                        <?php if ($catalog_tax !='' && $catalog_tax_show =='1') :?>
                            <div class="post-meta"><i class="fa fa-tag"></i>&nbsp;  
                                <?php $terms = get_the_terms($post->ID, $catalog_tax );
                                if ($terms && ! is_wp_error($terms)) :
                                    $term_slugs_arr = array();
                                    foreach ($terms as $term) {
                                        $term_slugs_arr[] = ''.$term->name.'';
                                    }
                                    $terms_slug_str = join(", ", $term_slugs_arr);
                                    echo $terms_slug_str;
                                endif;
                                ?> 
                            </div>
                        <?php endif ;?>
                        <h2><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
                        <?php if($catalog_desc !='') : ?>
                            <div class="rehub_catalog_desc">                                 
                                <?php kama_excerpt('maxchar='.$catalog_desc.''); ?>                       
                            </div>
                        <?php endif; ?>                        
                        <?php if($catalog_fields[0]['catalog_fields_name'] !='') : ?>
                            <div class="rehub_catalog_fields">                                 
                                <?php $count=1; foreach ($catalog_fields as $catalog_field) { ?>
                                <?php $field_value = get_post_meta($post->ID, $catalog_field['catalog_fields_name'], true) ;?> 
                                <?php if($field_value !='') : ?>              
                                    <div class="rehub_catalog_field rehub_field_<?php echo $count; ?>">
                                        <div class="rehub_catalog_field_title"><?php if ($catalog_field['catalog_icon']) : ?><i class="fa <?php echo $catalog_field['catalog_icon']; ?>"></i><?php endif ;?><?php if ($catalog_field['catalog_fields_title']) : ?><span><?php echo $catalog_field['catalog_fields_title']; ?></span><?php endif ;?></div>
                                        <div class="rehub_catalog_field_value"><?php echo $field_value; ?></div>
                                    </div>
                                <?php endif; ?>
                                <?php $count++; ?>    
                                <?php } ?>                        
                            </div>
                        <?php endif; ?>
                        <?php if($catalog_readmore =='buybutton') : ?>
                            <div class="rehub_catalog_readmore">                                 
                                <?php rehub_create_btn('no');?>                    
                            </div>
                        <?php elseif($catalog_readmore =='read') : ?>
                            <div class="rehub_catalog_readmore">                                 
                                <?php if (vp_metabox('rehub_post_side.read_more_custom')): ?>
                                    <a href="<?php the_permalink();?>" class="btn_more btn_more_custom"><?php echo strip_tags(vp_metabox('rehub_post_side.read_more_custom'));?></a>
                                <?php elseif (rehub_option('rehub_readmore_text') !=''): ?>
                                    <a href="<?php the_permalink();?>" class="btn_more"><?php echo strip_tags(rehub_option('rehub_readmore_text'));?></a>                                                   
                                <?php else: ?>
                                    <a href="<?php the_permalink();?>" class="btn_more"><?php _e('READ MORE  +', 'rehub_framework') ;?></a>
                                <?php endif ?>                                                    
                            </div> 
                        <?php else : ?>                                   
                        <?php endif; ?>                              
                    </div>                       
                </article>                       
            <?php $i++; endwhile; ?>
            <?php else: ?><?php _e('No posts for this criteria.', 'rehub_framework'); ?>
            <?php endif; ?>
            <?php if ($catalog_style =='grid_two' || $catalog_style =='grid_three') : ?></div><?php endif ;?>
            <div class="clearfix"></div>
            <div class="pagination">
                <?php rehub_pagination();?>
            </div>  
            <?php wp_reset_query(); ?>                                   
		</div>	
        <!-- /Main Side -->  
        <!-- Sidebar -->
        <?php get_sidebar(); ?>
        <!-- /Sidebar --> 
    </div>
</div>
<!-- /CONTENT -->     
<!-- FOOTER -->
<?php get_footer(); ?>