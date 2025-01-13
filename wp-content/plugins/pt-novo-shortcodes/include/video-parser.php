<?php
/**
 * Video Url Parser
 *
 * Parses URLs from major cloud video providers. Capable of returning
 * keys from various video embed and link urls to manipulate and
 * access videos in various ways.
 */
class VideoUrlParser {

  public static function identify_service($url) {
    if (preg_match('%youtube|youtu\.be%i', $url)) {
      return 'youtube';
    } elseif (preg_match('%vimeo%i', $url)) {
      return 'vimeo';
    } elseif (preg_match('/^.*\.(mp4|mov)$/i', $url) || preg_match('/^.*\.(mp4|mov)$/i', $url)) {
      return 'mp4';
    }
    return null;
  }

  public static function get_url_id($url) {
    $service = self::identify_service($url);
    if ($service == 'youtube') {
      return self::get_youtube_id($url);
    } elseif ($service == 'vimeo') {
      return self::get_vimeo_id($url);
    }
    return null;
  }

  public static function get_url_embed($url) {
    $service = self::identify_service($url);
    $id = self::get_url_id($url);
    if ($service == 'youtube') {
      return self::get_youtube_embed($id);
    } elseif ($service == 'vimeo') {
      return self::get_vimeo_embed($id);
    } elseif ($service == 'mp4') {
      return $url;
    }
    return null;
  }

  public static function get_youtube_id($url) {
    $youtube_url_keys = array('v', 'vi');
    $key_from_params = self::parse_url_for_params($url, $youtube_url_keys);
    if ($key_from_params) {
      return $key_from_params;
    }

    return self::parse_url_for_last_element($url);
  }

  public static function get_youtube_embed($youtube_video_id, $autoplay = 1) {
    $embed = "https://youtube.com/embed/$youtube_video_id?rel=0&amp;showinfo=0&amp;enablejsapi=1";
    return $embed;
  }

  public static function get_vimeo_id($url) {
    if (preg_match('#(?:https?://)?(?:www.)?(?:player.)?vimeo.com/(?:[a-z]*/)*([0-9]{6,11})[?]?.*#', $url, $m)) {
      return $m[1];
    }
    return false;
  }

  public static function get_vimeo_embed($vimeo_video_id, $autoplay = 1) {
    $embed = "https://player.vimeo.com/video/$vimeo_video_id";
    return $embed;
  }

  public static function get_cover($url) {
    $service = self::identify_service($url);
    if ($service == 'youtube') {
      return 'https://img.youtube.com/vi/' . self::get_youtube_id($url) . '/maxresdefault.jpg';
    } elseif ($service == 'vimeo') {
      $hash = unserialize(file_get_contents('http://vimeo.com/api/v2/video/' . self::get_vimeo_id($url) . '.php'));
      return $hash[0]['thumbnail_large'];
    }
    return null;
  }

  public static function get_background_video($url, $quality = '1080p', $muted = 'muted', $atts = false) {
    $service = self::identify_service($url);
    $video_block_class = '';
    if (isset($atts['background_video_playing_on']) && $atts['background_video_playing_on'] == 'click') {
      $video_block_class = ' disable-on-scroll';
    }

    if(isset($atts['background_video_autoplay']) && $atts['background_video_autoplay'] == 'off') {
      $video_block_class .= ' autoplay-disable';
    }

    if ($service == 'mp4') {
      return '<div class="video-wrap' . esc_attr($video_block_class) . '"><video class="video" playsinline ' . $muted . ' loop src="' . esc_url($url) . '" type="video/mp4" preload="none"></video></div>';
    } elseif ($service == 'youtube') {
      wp_enqueue_script('pt-youtube-video');
      
      return '<div class="video-wrap' . esc_attr($video_block_class) . '"><div class="video' . esc_attr($video_block_class) . '" webkit-playsinline="true" playsinline="true" data-quality="'.esc_attr($quality).'" data-muted="'.($muted ? 'true' : 'false').'" data-type="youtube" data-id="'.self::get_url_id($url).'"></div></div>';
    } elseif ($service == 'vimeo') {
      $vimeo_id = self::get_url_id($url);
      return '<div class="video' . esc_attr($video_block_class) . '"><iframe id="' . uniqid('vimeo-') . '" src="https://player.vimeo.com/video/' . esc_attr($vimeo_id) . '?background=1&autoplay=1&loop=1&' . ($muted ? 'muted=1' : '') . '" frameborder="0" allow="autoplay; fullscreen" allowfullscreen="" data-ready="true"></iframe></div>';
  }
    return null;
  }

  public static function get_player($url) {
    $service = self::identify_service($url);
    $id = self::get_url_id($url);
    if ($service == 'mp4') {
      return '<video controls class="pswp__video" width="1920" height="1080" src="' . esc_url($url) . '" type="video/mp4"></video>';
    } elseif ($service == 'youtube') {
      return '<iframe class="pswp__video" width="1920" height="1080" src="' . esc_url(self::get_youtube_embed($id)) . '" frameborder="0" allowfullscreen=""></iframe>';
    } elseif ($service == 'vimeo') {
      return '<iframe class="pswp__video" width="1920" height="1080" src="' . esc_url(self::get_vimeo_embed($id)) . '" frameborder="0" allowfullscreen=""></iframe>';
    }
    return null;
  }

  public static function get_time_format($seconds) {
    if ($seconds >= 3600) {
      return gmdate("H:i:s", $seconds);
    } else {
      return gmdate("i:s", $seconds);
    }
    return null;
  }

  public static function get_duration($url) {
    $service = self::identify_service($url);
    if ($service == 'youtube') {
      if($duration = get_option('video-daration-'.self::get_youtube_id($url))) {
        
        return $duration;
      } else {
        $json_url = 'https://www.googleapis.com/youtube/v3/videos?id='.self::get_youtube_id($url).'&part=contentDetails&key=AIzaSyC1JfBfoR_fCM2OXU6dNfJjRwfrdCLOPrE';
        if (!function_exists('curl_init') || empty(self::get_youtube_id($url))) {
          return false;
        }

        $ch = curl_init($json_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
          curl_close($ch);
          return false;
        }
        curl_close($ch);

        $array = json_decode($data, true);

        $interval = new \DateInterval($array['items'][0]['contentDetails']['duration']);
        $duration = self::get_time_format(($interval->d * 24 * 60 * 60) + ($interval->h * 60 * 60) + ($interval->i * 60) + $interval->s);

        add_option('video-daration-'.self::get_youtube_id($url), $duration);

        return $duration;
      }
    } elseif ($service == 'vimeo') {
      if($duration = get_option('video-daration-'.self::get_vimeo_id($url))) {
        return $duration;
      } else {
        $json_url = 'http://vimeo.com/api/v2/video/' . self::get_vimeo_id($url) . '.xml';
        if (!function_exists('curl_init') || empty(self::get_vimeo_id($url))) {
          return false;
        }

        $ch = curl_init($json_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
          curl_close($ch);
          return false;
        }
        curl_close($ch);

        $data = new SimpleXmlElement($data, LIBXML_NOCDATA);
        if (!isset($data->video->duration)) {
          return false;
        }

        $seconds = intval($data->video->duration);
        $duration = self::get_time_format($seconds);
        add_option('video-daration-'.self::get_vimeo_id($url), $duration);

        return $duration;
      }
    }
  }

  private static function parse_url_for_params($url, $target_params) {
    parse_str(parse_url($url, PHP_URL_QUERY), $my_array_of_params);
    foreach ($target_params as $target) {
      if (array_key_exists($target, $my_array_of_params)) {
        return $my_array_of_params[$target];
      }
    }
    return null;
  }

  private static function parse_url_for_last_element($url) {
    $url_parts = explode("/", $url);
    $prospect = end($url_parts);
    $prospect_and_params = preg_split("/(\?|\=|\&)/", $prospect);
    if ($prospect_and_params) {
      return $prospect_and_params[0];
    } else {
      return $prospect;
    }
    return $url;
  }
}
?>