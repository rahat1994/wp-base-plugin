<?php

namespace App\ShortCodes;

use App\Common\LoadAssets;
use App\Traits\CanInteractWithFeedCPT;
use App\PlatformClients\RedditClient;
use App\Interfaces\ShortCodes\ShortcodeInterface;
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ajax Handler Class
 * @since 1.0.0
 */
class FeedShortCode implements ShortcodeInterface
{
    use CanInteractWithFeedCPT;
    public RedditClient $redditClient;
    public LoadAssets $assetsLoader;
    public array $validators;

    public function __construct(RedditClient $redditClient, LoadAssets $assetsLoadiner){
        $this->redditClient = $redditClient;
        $this->assetsLoader = $assetsLoadiner;
    }

    public function boot(){
        add_action('wp_enqueue_scripts', [$this, 'loadFrontendScripts']);
        add_shortcode('wprb-subreddit-feed', array($this,'renderFeedWithJS'));
    }

    public function loadFrontendScripts(){
        $this->assetsLoader->frontend();
    }

    public function renderFeedWithJS(){
        return '<div x-data="loveCounter" >
                    <h1>Show some heart</h1>
                    <div x-text="hearts()" >
                    </div>
                    <div>
                        <button @click="love" >Show 💟</button>
                    </div>
                </div>';
    }
    public function render_subreddit_feed($atts = [], $content = null, $tag = ''){
        
        // normalize attribute keys, lowercase
        $atts = array_change_key_case( (array) $atts, CASE_LOWER );
        
        // override default attributes with user attributes
        $shortcodeAtts = shortcode_atts(
            array(
                'feed' => null,
            ), $atts, $tag
        );

        if ($shortcodeAtts['feed'] == null) {
            return '<div class="wp-base-notice">'.__('Missing feed attribute', 'wp-base-plugin').'</div>';
        }
        
        $rssUrl = $this->getFeedMeta($shortcodeAtts['feed']);

        if(!$rssUrl){
            return '<div class="wp-base-notice">'.__('Invalid URL', 'wp-base-plugin').'</div>';
        }

        
        $feedType = $this->getFeedMeta($shortcodeAtts['feed'], '_wprb_feed_type');
        $shouldBeCached = $this->getFeedMeta($shortcodeAtts['feed'], '_wprb_should_be_cached');

        $feedType = $feedType ? $feedType : 'new';
        $shouldBeCached = $shouldBeCached ? $shouldBeCached : 'true';
        $subRedditName = $this->getSubredditName($rssUrl);

        $subredditData = $this->redditClient->getSubredditHTML($shortcodeAtts['feed'],$subRedditName, $feedType , $shouldBeCached);

        if(!$subredditData){
            return '<div class="wp-base-plugin">'.__('Something went wrong.', 'wp-base-plugin').'</div>';
        }

        $feedData = $subredditData['feedData'];
        $subRedditinfo = $subredditData['subRedditInfo'];

        if(!$feedData){
            return '<div class="wp-base-plugin">'.__('Invalid access token.', 'wp-base-plugin').'</div>';
        }

        if (count($feedData['data']['children']) == 0) {
            return '<div class="wp-base-plugin">'.__('No feed data found', 'wp-base-plugin').'</div>';
        }

        $feedConfig = $this->getFeedConfig($shortcodeAtts['feed']);
        $subredditMetaHtml = $this->getSubredditMetaHTML($subRedditinfo, $feedConfig);
        $links = $this->getFeedLInks($feedData,$feedConfig);

        return '<div class="wprb-subreddit-posts">'.$subredditMetaHtml.$links.'</div>';
    }

    public function getSubredditMetaHTML($subRedditInfo, $feedConfig){
        $title = $subRedditInfo['data']['title'];
        $description = $subRedditInfo['data']['public_description'];

        $titleHTML = '';
        if (isset($feedConfig['title']['show']) && $feedConfig['title']['show']) {
            $tag = isset($feedConfig['title']['tag']) ? $feedConfig['title']['tag'] : 'h2';
            $classes = isset($feedConfig['title']['classes']) ? $feedConfig['title']['classes'] : '';
            $titleHTML = '<' . esc_html($tag) . ' class="' . esc_attr($classes) . '">' . esc_html($title) . '</' . esc_html($tag) . '>';
        }

        $descriptionHTML = '';
        if (isset($feedConfig['description']['show']) && $feedConfig['description']['show']) {
            $tag = isset($feedConfig['description']['tag']) ? $feedConfig['description']['tag'] : 'p';
            $classes = isset($feedConfig['description']['classes']) ? $feedConfig['description']['classes'] : '';
            $descriptionHTML = '<' . esc_html($tag) . ' class="' . esc_attr($classes) . '">' . esc_html($description) . '</' . esc_html($tag) . '>';
        }

        return '<div class="wprb-subreddit-meta">'.$titleHTML.$descriptionHTML.'</div>';
    }

    public function getFeedLInks($feedData, $feedConfig){


        $links = '';

        if (isset($feedConfig['list']['show']) && $feedConfig['list']['show']) {
            $listTag = isset($feedConfig['list']['tag']) ? $feedConfig['list']['tag'] : 'ul';
            $listClasses = isset($feedConfig['list']['classes']) ? $feedConfig['list']['classes'] : '';
            $links = '<' . esc_html($listTag) . ' class="' . esc_attr($listClasses) . '">';
        }

        foreach ($feedData['data']['children'] as $feed) {
            $linkTag = isset($feedConfig['links']['tag']) ? $feedConfig['links']['tag'] : 'a';
            $linkClasses = isset($feedConfig['links']['classes']) ? $feedConfig['links']['classes'] : '';
            $links .= '<li><' . esc_html($linkTag) . ' href="' . esc_html($feed['data']['url']) . '" class="' . esc_attr($linkClasses) . '">' . esc_html($feed['data']['title']) . '</' . esc_html($linkTag) . '></li>';
        }

        if (isset($feedConfig['list']['show']) && $feedConfig['list']['show']) {
            $links .= '</' . esc_html($listTag) . '>';
        }

        return '<div>' . $links . '</div>';
    }



    public function getFeedPostById($id){
        $post = get_post($id);
        return $post;
    }



    public function getRssUrlContent($url){
        error_log($url);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $xmlString = curl_exec($curl);
        curl_close($curl);

        error_log('The xml string: '.$xmlString);
        return $xmlString;
    }

    public function getFeedConfig($feedId){
        $feedConfig = get_post_meta($feedId, '_wprb_feed_config', true);

        if($feedConfig === ''){
            $feedConfig = wprb_feed_default_config();
        }
        return json_decode($feedConfig, true);
    }


}