<?php
/**
 * Class for adding Our Services Section Widget
 *
 * @package Canyon Themes
 * @subpackage Canyon
 * @since 1.0.0
 */
if( !class_exists( 'Education_Way_Services_Widget' ) ){
    
    class Education_Way_Services_Widget extends WP_Widget
    
    {
        private function defaults()
        {
            /*defaults values for fields*/
            $defaults    = array(
                'title'               => esc_html__('Our Services','education-way'),
                'sub_title'           => esc_html__('Check Our All Services','education-way'),
                'features'            => ''
            );
            return $defaults;
        }

        public function __construct()
        
        {
            parent::__construct(
                /*Base ID of your widget*/
                'education_way_service_widget',
                /*Widget name will appear in UI*/
                esc_html__( 'CT: Service Widget', 'education-way' ),
                /*Widget description*/
                array( 'description' => esc_html__( 'CT: Service Widget', 'education-way' ) )
            );
        }
        /**
         * Function to Creating widget front-end. This is where the action happens
         *
         * @access public
         * @since 1.0
         *
         * @param array $args widget setting
         * @param array $instance saved values
         *
         * @return void
         *
         */
        public function widget( $args, $instance )
        {

            if (!empty( $instance ) ) 
            {
                $instance = wp_parse_args( (array ) $instance, $this->defaults ());
                $title        = apply_filters('widget_title', !empty($instance['title']) ? esc_html( $instance['title']): '', $instance, $this->id_base);
                $subtitle     =  esc_html( $instance['sub_title'] );
              
                $features = ( ! empty( $instance['features'] ) ) ? $instance['features'] : array();
               if (isset($features) ) : 
                echo $args['before_widget'];
                ?>
                <!-- Start Service  Section -->
                <section class="services-section p-b-70">
                  <div class="container">
                    <div class="row">
                      <div class="services-option">
                        <div class="section-header text-center">
                          <h2><?php echo $title; ?></h2>
                          <span class="double-border"></span>
                          <p><?php echo $subtitle; ?></p>
                        </div><!-- .section-header -->
                        <div class="row">
                          <?php
                          $post_in = array();

                          if  (count($features) > 0 && is_array($features) )
                          {
                              $post_in[0] = $features['main'];
                                
                              foreach ( $features as $our_services )
                              {
                                    
                                  if( isset( $our_services['page_ids'] ) && !empty( $our_services['page_ids'] ) )
                                  {
                                      
                                     $post_in[] = $our_services['page_ids'];
                                      
                                  }
                              }
                          }
                          
                          if( !empty( $post_in )) :
                              $services_page_args = array(
                                      'post__in'         => $post_in,
                                      'orderby'             => 'post__in',
                                      'posts_per_page'      => count( $post_in ),
                                      'post_type'           => 'page',
                                      'no_found_rows'       => true,
                                      'post_status'         => 'publish'
                              );
                              $services_query = new WP_Query( $services_page_args );

                              /*The Loop*/
                              if ( $services_query->have_posts() ):
                                  $i = 1;
                                  while ( $services_query->have_posts() ):$services_query->the_post();
                                                  
                                      $icon = get_post_meta( get_the_ID(), 'education_way_icon', true );
                                      
                                      ?>
                                      <div class="col-md-4 col-sm-6 col-xs-6">
                                        <div class="services-box">
                                          <div class="services-items">
                                            <i class="pe-7s-<?php echo  $icon; ?>"></i>
                                            <div class="services-content">
                                              <h4><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h4>
                                              <?php  the_excerpt(); ?>
                                            </div>
                                          </div>
                                        </div>
                                      </div><!-- .col-md-4 -->
                        <?php
                                  endwhile;
                              endif;
                              wp_reset_postdata();
                            endif;
                          
                      ?>
                         <?php wp_link_pages(); ?>
                        </div><!-- .row -->
                      </div><!-- .our-services-option -->
                    </div><!-- .row -->
                  </div><!-- .container -->
                </section>
                <!-- End Service  Section -->
                <?php
                endif;
                echo $args['after_widget'];
            }
        }
        /**
         * Function to Updating widget replacing old instances with new
         *
         * @access public
         * @since 1.0
         *
         * @param array $new_instance new arrays value
         * @param array $old_instance old arrays value
         *
         * @return array
         *
         */
        public function update($new_instance, $old_instance)
        {
            $instance              = $old_instance;
            $instance['main']      = absint($new_instance['main']);
            $instance['title']     = ( isset( $new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
            $instance['sub_title'] = ( isset( $new_instance['sub_title'])) ? sanitize_text_field($new_instance['sub_title']) : '';
            
            if (isset($new_instance['features']))
            {
                foreach($new_instance['features'] as $feature)
                {
                  
                  $feature['page_ids'] = absint($feature['page_ids']);
                }
                $instance['features']  = $new_instance['features'];
            }
            
            return $instance;

        }

        public function form($instance)
        {
            $instance   = wp_parse_args( (array ) $instance, $this->defaults() );
            $title      = esc_attr( $instance['title'] );
            $subtitle   = esc_attr( $instance['sub_title'] );
            $features   = ( !empty( $instance['features'] ) ) ? $instance['features'] : array(); 
            ?>
            <span class="ct-education-way-additional">
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                    <?php esc_html_e('Title', 'education-way'); ?>
                </label><br/>
                <input type="text" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title') ); ?>" value="<?php echo $title; ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('sub_title') ); ?>">
                    <?php esc_html_e( 'Sub Title', 'education-way'); ?>
                </label><br/>
                <input type="text" name="<?php echo esc_attr($this->get_field_name('sub_title')); ?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('sub_title')); ?>" value="<?php echo $subtitle; ?>">
            </p>
           <hr>
            <!--updated code-->
            <label><strong><?php _e( 'Select Pages', 'education-way' ); ?>:</strong></label>
            <br/>
            <small><?php _e( 'Add Page and Remove. Please do not forget to add Icon and Excerct  on selected pages.', 'education-way' ); ?></small>
               
             <?php
                 if  ( count( $features ) >=  1 && is_array( $features ) )
                 {
                
                      $selected = $features['main'];

                  }

                  else
                  {
                    $selected = "";
                  }

                $repeater_id   = $this->get_field_id( 'features' ).'-main';
                $repeater_name = $this->get_field_name( 'features'). '[main]';

                $args = array(
                    'selected'          => $selected,
                    'name'              => $repeater_name,
                    'id'                => $repeater_id,
                    'class'             => 'widefat ct-select',
                    'show_oction_none'  => __( 'Select Page', 'education-way'),
                    'oction_none_value' => 0 // string
                );
                wp_dropdown_pages( $args );
              ?>

               <?php
           
            $counter = 0;
           
            if ( count( $features ) > 0 ) 
            {
                foreach( $features as $feature ) 
                {

                    if ( isset( $feature['page_ids'] ) ) 

                    { ?>
                          <div class="ct-education-way-sec">

                            <label><?php _e( 'Select Pages', 'education-way' ); ?>:</label>
          
                              <?php
            
                                $repeater_id     = $this->get_field_id( 'features' ) .'-'. $counter.'-page_ids';
                                $repeater_name   = $this->get_field_name( 'features' ) . '['.$counter.'][page_ids]';

                                $args = array(
                                    'selected'          => $feature['page_ids'],
                                    'name'              => $repeater_name,
                                    'id'                => $repeater_id,
                                    'class'             => 'widefat ct-select',
                                    'show_oction_none'  => __( 'Select Page', 'education-way'),
                                    'oction_none_value' => 0 // string
                                );
                                wp_dropdown_pages( $args );
                                ?>

                                <a class="ct-education-way-remove delete"><?php esc_html_e('Remove Section','education-way') ?></a>
                          </div>
                      <?php
                      $counter++;
                   }
                }
            }

            ?>

         </span>
         <a class="ct-education-way-add button" data-id="education_way_service_widget" id="<?php echo $repeater_id; ?>"><?php _e('Add New Section', 'education-way'); ?></a> 
           
            <?php
        }
    }
}


add_action( 'widgets_init', 'education_way_service_widget' );

function education_way_service_widget() {
    
    register_widget( 'Education_Way_Services_Widget' );
}