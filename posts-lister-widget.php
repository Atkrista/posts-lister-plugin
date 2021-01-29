<?php
/**
 * @package Posts Lister
 */

class Posts_Lister_Widget extends WP_Widget
{
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'posts-lister-widget',
            'description' => 'Display your posts in listview or grid layout',
        );
        //id of widget, name of widget, options    
        parent::__construct('posts_lister_widget', 'Posts Lister Widget', $widget_ops);
    }

    public function widget($args, $instance)
    {
        $layout_type = $instance['layout_type'];
        $num_of_posts = $instance['num_of_posts'];
        echo $args['before_widget'];

        //output content to std out
        echo do_shortcode("[posts_lister layout={$layout_type} num_of_posts={$num_of_posts}]");
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        //form for widget admin
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('layout_type'); ?>">Layout Type</label>
            <select name="<?php echo $this->get_field_name('layout_type'); ?>"
                    id="<?php echo $this->get_field_id('layout_type'); ?>">
                <option name="<?php echo $this->get_field_name('layout_type'); ?>" value="grid">Grid</option>
                <option name=" <?php echo $this->get_field_name('layout_type'); ?>" value="list-view">List View</option>
            </select>
        </p>

        <p>


            <label for="<?php echo $this->get_field_id('num_of_posts'); ?>">Number of Posts</label>
            <input type="number" id="<?php echo $this->get_field_id('num_of_posts'); ?>"
                   name="<?php echo $this->get_field_name('num_of_posts'); ?>"
                   value="<?php echo $instance['num_of_posts'];?>">
        </p>

        <?php
    }

    public function update($new_instance, $old_instance)
    {
        //updates new instance of widget
        $instance = $old_instance;
        $instance['layout_type'] = isset($new_instance['layout_type'])?$new_instance['layout_type']:'';
        $instance['num_of_posts'] = isset($new_instance['num_of_posts'])?$new_instance['num_of_posts']:'';
        return $instance;
    }
}

