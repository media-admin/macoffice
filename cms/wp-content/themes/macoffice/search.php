/* <?php
$search=get_search_query();
$all_categories = get_terms( array('taxonomy' => 'category','hide_empty' => true) );
$all_tags = get_terms( array('taxonomy' => 'post_tag','hide_empty' => true) );
//if you have any custom taxonomy
$all_custom_taxonomy = get_terms( array('taxonomy' => 'your-taxonomy-slug','hide_empty' => true) );

$mcat=array();
$mtag=array();
$mcustom_taxonomy=array();

foreach($all_categories as $all){
$par=$all->name;
if (strpos($par, $search) !== false) {
array_push($mcat,$all->term_id);
}
}

foreach($all_tags as $all){
$par=$all->name;
if (strpos($par, $search) !== false) {
array_push($mtag,$all->term_id);
}
}

foreach($all_custom_taxonomy as $all){
$par=$all->name;
if (strpos($par, $search) !== false) {
array_push($mcustom_taxonomy,$all->term_id);
}
}

$matched_posts=array();
$args1= array( 'post_status' => 'publish','posts_per_page' => -1,'tax_query' =>array('relation' => 'OR',array('taxonomy' => 'category','field' => 'term_id','terms' =>$mcat),array('taxonomy' => 'post_tag','field' => 'term_id','terms' =>$mtag),array('taxonomy' => 'custom_taxonomy','field' => 'term_id','terms' =>$mcustom_taxonomy)));

$the_query = new WP_Query( $args1 );
if ( $the_query->have_posts() ) {
while ( $the_query->have_posts() ) {
$the_query->the_post();
array_push($matched_posts,get_the_id());
//echo '<li>' . get_the_id() . '</li>';
}
wp_reset_postdata();
} else {

}

?>
<?php
// now we will do the normal wordpress search
$query2 = new WP_Query( array( 's' => $search,'posts_per_page' => -1 ) );
if ( $query2->have_posts() ) {
while ( $query2->have_posts() ) {
$query2->the_post();
array_push($matched_posts,get_the_id());
}
wp_reset_postdata();
} else {

}
$matched_posts= array_unique($matched_posts);
$matched_posts=array_values(array_filter($matched_posts));
//print_r($matched_posts);
?>

<?php
$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
$query3 = new WP_Query( array( 'post_type'=>'any','post__in' => $matched_posts ,'paged' => $paged) );
if ( $query3->have_posts() ) {
while ( $query3->have_posts() ) {
$query3->the_post();
get_template_part( 'template-parts/content/content', 'excerpt' );
}
twentynineteen_the_posts_navigation();
wp_reset_postdata();
} else {

}
?> */