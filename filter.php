<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *  Voicethread filtering
 *
 *  This filter will replace any Voicethread link with an embedded player
 *
 * @package    filter
 * @subpackage voicethread
 * @copyright  Marc Català  <mcatala@ioc.cat>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class filter_voicethread extends moodle_text_filter {

    public function filter($text, array $options = array()) {

        if (!is_string($text) or empty($text)) {
            // Non string data can not be filtered anyway.
            return $text;
        }

        $newtext = '';
        $match = '~<a\s[^>]*href="https?://voicethread.com/share/(.*?)/?">[^<]*</a>~is';
        $newtext = preg_replace_callback($match, array($this, 'callback'), $text);

        if (empty($newtext) or $newtext === $text) {
            // Error or not filtered.
            return $text;
        }

        return $newtext;
    }


    /**
     * Replace link with embedded content, if supported.
     *
     * @param array $matches
     * @return string
     */
    private function callback($matches) {

        $videoid = $matches[1];

        $width   = '480';
        $height  = '270';

        $output = <<<OET
<span class="mediaplugin mediaplugin_voicethread">
<iframe width="$width" height="$height" src="https://voicethread.com/app/player/?threadId=$videoid" frameborder="0" allowfullscreen>
</iframe>
</span>
OET;
        return $output;
    }
}
