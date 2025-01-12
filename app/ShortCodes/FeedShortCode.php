<?php

namespace App\ShortCodes;

use App\Validators\RegexValidator;
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
    public RedditClient $redditClient;
    public array $validators;

    public function __construct(RedditClient $redditClient){
        $this->redditClient = $redditClient;
    }

    public function boot(){
        add_shortcode('wprb-subreddit-feed', array($this,'render_subreddit_feed'));
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
            return '<div class="wprb-subreddit-rss">'.__('Missing feed attribute', 'wprb-subreddit-rss').'</div>';
        }
        
        $rssUrl = $this->getSubredditRssUrl($shortcodeAtts['feed']);

        if(!$rssUrl){
            return '<div class="wprb-subreddit-rss">'.__('Invalid URL', 'wprb-subreddit-rss').'</div>';
        }

        $xml = $this->getRssUrlContent($rssUrl);

        $feedData = $this->redditClient->getFeedData();

        if (count($feedData['data']['children']) == 0) {
            return '<div class="wprb-subreddit-rss">'.__('No feed data found', 'wprb-subreddit-rss').'</div>';
        }

        $links = '<ul>';

        foreach ($feedData['data']['children'] as $feed) {
            
            $links .= '<li><a href="'.esc_html($feed['data']['url']).'">'.esc_html($feed['data']['title']). '</a></li>';

        }
        $links .= '</ul>';
        return '<div>'. $links . '</div>';
    }

    public function getFeedPostById($id){
        $post = get_post($id);
        return $post;
    }

    public function getSubredditRssUrl(int $id, $meta_key = '_wprb_subreddit_url'){

        $metaValue = get_post_meta($id, $meta_key, true);

        // if the meta value is empty return an empty string
        $regexValidator = new RegexValidator();
        $isRedditUrl = $regexValidator->validate($metaValue);

        if ($isRedditUrl) {
            return $metaValue;
        }

        return false;
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


}