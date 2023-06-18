<?php

 /*
 * phpMumbleAdmin (PMA), administration panel for murmur (mumble server daemon).
 * Copyright (C) 2010 - 2016, Dadon David, dev.pma@ipnoz.net
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (! defined('PMA_STARTED')) { die('You cannot call this script directly !'); }

class PMA_logsPMA extends PMA_logs
{
    /*
    * Extends searchs and highlights to the level of PMA logs.
    */
    protected $searchLevelPattern = '';
    protected $highlightsLevelRules = array();

    public function setsearchLevelPattern($string)
    {
        $this->searchLevelPattern = $string;
    }

    /*
    * @return - boolean.
    */
    private function searchForLevel($level)
    {
        if ($this->searchLevelPattern !== '') {
            return (false !== stripos($level, $this->searchLevelPattern));
        }
        // No search set, show log.
        return true;
    }

    private function applyHighlightLevel($level)
    {
        foreach ($this->highlightsLevelRules as $rule) {
            if (false !== stripos($level, $rule->level)) {
                $level = '<span class="'.$rule->css.'">'.$level.'</span>';
                break;
            }
        }
        return $level;
    }

    public function addHighlightLevelRule($css, $level)
    {
        $rule = new stdClass();
        $rule->css = $css;
        $rule->level = $level;
        $this->highlightsLevelRules[] = $rule;
    }

    /*
    * Add custom controls.
    */
    protected function logControl($log)
    {
        // Check for level search.
        if (! $this->searchForLevel($log->level)) {
            return;
        }
        // Apply level highlights
        $log->level = $this->applyHighlightLevel($log->level);
        $log = parent::logControl($log);
        if (is_object($log)) {
            $log->text = $log->level.' - '.$log->ip.' - '.$log->text;
            return $log;
        }
    }
}
