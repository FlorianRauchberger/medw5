<?php
/**
 * Plugin Name: Post Statistics
 * Plugin URI: https://siriusstudios.at/
 * Description: Another amazing plugin.
 * Version: 1.0
 * Author: HTL Super Coder
 */


class PostStat{
    /*
    admin menu fires before the administration menu loads in the admin -> WP sieht nach ob es für das Admin Menü eine Eintrag gibt, der angezeigt werden soll!
    Es wird dann die CB - CallBack function 'pluginSettingMenuEntry' ausgeführt
    Bei der OOP-Variante ist der Name der Callback-function in kombination mit der Objekt-Referenz $this in ein Array zu packen.
    */
    function __construct()

    {

        add_action('admin_menu', array($this, 'pluginSettingMenuEntry'));

        add_action('admin_init', array($this, 'settings'));

        add_filter('the_content', array($this, 'outputStats'));

    }


    function settings(){
        //add_settings_section( string $id, string $title, callable $callback, string $page )
        add_settings_section('psp_first_section', null, null, 'post-stat-settings-page');
        //add_settings_field( string $id, string $title, callable $callback, string $page, string $section = 'default', array $args = array() )
        add_settings_field('psp_location', 'Display Location', array($this, 'locationHTML'), 'post-stat-settings-page', 'psp_first_section');
        //register_setting( string $option_group, string $option_name, array $args = array() )
        register_setting('post_stat_plugin', 'psp_location', array('sanitize_callback' => 'sanitize_text_field', 'default'=>0));
        register_setting('post_stat_plugin', 'psp_location', array('sanitize_callback' => array($this, 'sanitizeLocation'), 'default' => 0));

        //Input Field Headline Text
        add_settings_field('psp_headline', 'Headline Text', array($this, 'headlineHTML'), 'post-stat-settings-page', 'psp_first_section');
        register_setting('post_stat_plugin', 'psp_headline', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Statistic'));

        //Checkbox WordCount
        add_settings_field('psp_wordcount', 'Word Count', array($this, 'checkboxHTML'), 'post-stat-settings-page', 'psp_first_section', array('name'=>'psp_wordcount'));
        register_setting('post_stat_plugin', 'psp_wordcount', array('sanitize_callback' => 'sanitize_text_field', 'default' => 1));

        add_settings_field('psp_charcount', 'Character Count', array($this, 'checkboxHTML'), 'post-stat-settings-page', 'psp_first_section', array('name'=>'psp_charcount'));
        register_setting('post_stat_plugin', 'psp_charcount', array('sanitize_callback' => 'sanitize_text_field', 'default' => 1));

        add_settings_field('psp_readtime', 'Read time', array($this, 'checkboxHTML'), 'post-stat-settings-page', 'psp_first_section',  array('name'=>'psp_readtime'));
        register_setting('post_stat_plugin', 'psp_readtime', array('sanitize_callback' => 'sanitize_text_field', 'default' => 1));

        //Checkbox Paragraph
        add_settings_field('psp_paragraph', 'Paragraph Count', array($this, 'checkboxHTML'), 'post-stat-settings-page', 'psp_first_section', array('name'=>'psp_paragraph'));
        register_setting('post_stat_plugin', 'psp_paragraph', array('sanitize_callback' => 'sanitize_text_field', 'default' => 1));
    }

    function outputStats($content){
        if ((is_main_query() AND is_single()) AND
            // get_option ist ein DB-Zugriff mit SELECT
            get_option('psp_wordcount', '1') OR
            get_option('psp_charcount', '1') OR
            get_option('psp_readtime', '1') OR
            get_option('psp_paragraph', '1')){

            $html='<h3>'.get_option('psp_headline', 'Display Location').'</h3><p>';

            if(get_option('psp_wordcount', '1') || get_option('psp_readtime', '1')){
                $wordCount = str_word_count(strip_tags($content));
            }

            if(get_option('psp_wordcount')){
                $html.= "This page has " . $wordCount." words.<br>";
            }

            if(get_option('psp_charcount'))
                $html.= "This page has " . strlen($content)." characters.<br>";

            if(get_option('psp_readtime')){
                $minutes = round($wordCount/240) . " minute";
                if(round($wordCount/240) > 1){
                    $minutes.= "s";
                }
                $html.= "This page will take " . $minutes . " to read.<br>";
            }

            if(get_option('psp_paragraph')){
                $pattern = "/<p>.*?<\/p>/m";
                $paragraph_count = preg_match_all($pattern,$content);
                $html.= "This page has " . $paragraph_count . " Paragraphs.</p>";
            }

            if(get_option('psp_location', '0'))
                return $content.$html;
            else
                return $html.$content;
        }
        else
            return $content;
    }


    //https://developer.wordpress.org/reference/functions/add_options_page/
    // 1. Arg.: Page Title
    // 2. Arg.: Menüeintrag im Backend unter 'Settings'
    // 3. Arg.: Ist quasi eine Rolle! Ist so!
    // 4. Arg.: Name (Slug name), mit welchem der Eintrag auf Code-Ebene angesprochen werden kann.
    // 5. Arg.: Jene Funktion, die den Inhalt der Steite unter dem Menüeintrag 'Post Stat
    //
    function pluginSettingMenuEntry(){
        add_options_page('Post Stat Settings' , 'Post Stat', 'manage_options', 'post_stat_plugin', array($this, 'pluginSettingHTML'));
    }

    /*
     * Class 'wrap' ist ein vordefinierter Wordpress Container!
     */
    function pluginSettingHTML(){ ?>
        <div class='wrap'><h1>Post Stat Settings</h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('post_stat_plugin');
                do_settings_sections('post-stat-settings-page');
                submit_button();
                ?>
            </form>
        </div>
    <?php }

    function locationHTML(){?>
        <select name="psp_location">
            <option value="0" <?php selected(get_option('psp_location'), '0') ?>>Beginning of post</option>
            <option value="1" <?php selected(get_option('psp_location'), '1') ?>>End of post</option>
        </select>
    <?php }
    function headlineHTML(){?>
        <input name="psp_headline" value="<?php echo get_option("psp_headline")?>">
    <?php }

    function checkboxHTML($args){?>

        <input type="checkbox" name="<?php echo $args['name'] ?>" value='1' <?php checked(get_option($args['name']), 1);?>>

    <?php }

    function sanitizeLocation($inputValue)

    {

        if ($inputValue != '0' && $inputValue != '1') {

            add_settings_error('psp_location', 'psp_location_error', 'Display Location must be either beginning or end.');

            return get_option('psp_location');

        }

        return $inputValue;

    }
}

new PostStat();