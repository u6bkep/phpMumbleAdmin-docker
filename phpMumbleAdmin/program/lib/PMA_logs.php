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

class PMA_logs
{
    protected $logs = array();
    protected $stats = array();
    /*
    * Last log day, month, year cache.
    */
    protected $lastDay;
    protected $lastMonth;
    protected $lastYear;
    /*
    * Allow replacement flag
    */
    protected $isAllowReplacement = false;
    /*
    * Allow highlight flag
    */
    protected $isAllowHighlight = false;
    /*
    * Rules tables
    */
    protected $replacementsRules = array();
    protected $highlightsRules = array();
    protected $filtersRules = array();
    /*
    * Search pattern.
    */
    protected $searchPattern = '';

    public function __construct()
    {
        /*
        * Setup logs stats.
        */
        $this->stats['total_of_logs'] = 0;
        $this->stats['total_search_found'] = 0;
        $this->stats['total_filtered_logs'] = 0;
        $this->stats['total_unfiltred_logs'] = 0;
        $this->stats['total_possible_filter_logs'] = 0;
    }

    public function setAllowHighlight($bool)
    {
        $this->isAllowHighlight = (bool)$bool;
    }

    public function setAllowReplacement($bool)
    {
        $this->isAllowReplacement = (bool)$bool;
    }

    public function setSearchPattern($string)
    {
        $this->searchPattern = $string;
    }

    public function getLogs()
    {
        return $this->logs;
    }

    public function getStats()
    {
        return $this->stats;
    }

    public function isAllowHighlight()
    {
        return $this->isAllowHighlight;
    }

    public function isAllowReplacement()
    {
        return $this->isAllowReplacement;
    }

    /*
    * Add a replacement rule
    * @param $method - "str" or "reg_ex"
    * @param $pattern - pattern to search for
    * @param $replacement - replacement string
    */
    public function addReplacementRule($method, $pattern, $replacement)
    {
        $rule = new stdClass();
        $rule->method = $method;
        $rule->pattern = $pattern;
        $rule->replacement = $replacement;
        $this->replacementsRules[] = $rule;
    }

    /*
    * Add a highlight rule
    * @param $css - css class to use
    * @param $pattern - string pattern to search for
    */
    public function addHighlightRule($css, $pattern)
    {
        $rule = new stdClass();
        $rule->css = $css;
        $rule->pattern = $pattern;
        $this->highlightsRules[] = $rule;
    }

    /*
    * Add a filter rule
    * @param $mask - bitmask
    * @param $pattern - pattern to search for
    * @param $active - is active filter ?
    */
    public function addFilterRule($mask, $pattern, $active)
    {
        $rule = new stdClass();
        $rule->mask = $mask;
        $rule->pattern = $pattern;
        $rule->active = (bool)$active;
        $this->filtersRules[] = $rule;
        // Enable custom filters stats
        $this->stats['filter'.$mask] = 0;
    }

    public function getFiltersRules()
    {
        return $this->filtersRules;
    }

    protected function incrementStat($key)
    {
        ++$this->stats[$key];
    }

    protected function applyLogReplacement($text)
    {
        $old = $text;

        foreach ($this->replacementsRules as $rule) {
            if ($rule->method === 'reg_ex') {
                $text = preg_replace($rule->pattern, $rule->replacement, $text);
            } else {
                $text = str_replace($rule->pattern, $rule->replacement, $text);
            }
            // One replacement by log
            if ($text !== $old) {
                break;
            }
        }
        return $text;
    }

    /*
    * Search in log for some text
    *
    * @param $text
    * @return boolean
    */
    protected function searchInLog($text)
    {
        if (false !== stripos($text, $this->searchPattern)) {
            $this->incrementStat('total_search_found');
            return true;
        }
        return false;
    }

    /*
    * Check if the log is filtered
    *
    * @param $text
    * @return boolean
    */
    protected function isfilteredLog($text)
    {
        foreach ($this->filtersRules as $rule) {
            if (false !== stripos($text, $rule->pattern)) {
                $this->incrementStat('total_possible_filter_logs');
                $this->incrementStat('filter'.$rule->mask);
                if ($rule->active) {
                    $this->incrementStat('total_filtered_logs');
                    return true;
                }
                break;
            }
        }
        return false;
    }

    /*
    * Apply highlights rules on the log text
    *
    * @param $text
    * @return string - the new text.
    */
    protected function applyHighlights($text)
    {
        foreach ($this->highlightsRules as $rule) {
            if (false !== stripos($text, $rule->pattern)) {
                $HTML = '<mark class="'.$rule->css.'">'.$rule->pattern.'</mark>';
                $text = str_replace($rule->pattern, $HTML, $text);
                break;
            }
        }
        return $text;
    }

    /*
    * Check if the day has change.
    *
    * @param $timestamp - timestamp of the current log.
    *
    * @return boolean
    */
    protected function isNewDay($timestamp)
    {
        list($day, $month, $year) = explode('/', date('d/m/Y', $timestamp));
        /*
        * Check if current day has changed.
        */
        $isNewDay = (
            $day !== $this->lastDay
            OR $month !== $this->lastMonth
            OR $year !== $this->lastYear
        );
        /*
        * Update log cache.
        */
        $this->lastDay = $day;
        $this->lastMonth = $month;
        $this->lastYear = $year;
        /*
        * Return boolean result.
        */
        return $isNewDay;
    }

    /*
    * Log control sequence.
    * @return object - Return log object if it's not filtered or null.
    */
    protected function logControl($log)
    {
        // Logs string replacement.
        if ($this->isAllowReplacement()) {
            $log->text = $this->applyLogReplacement($log->text);
        }
        // logs search.
        if ($this->searchPattern !== '' && ! $this->searchInLog($log->text)) {
            return;
        }
        if ($this->isfilteredLog($log->text)) {
            return;
        }
        $log->isNewDay = $this->isNewDay($log->timestamp);
        // Html encode
        $log->text = htEnc($log->text);
        // Highlights
        $log->text = $this->applyHighlights($log->text);

        return $log;
    }

    /*
    * Control a log and add it.
    */
    public function addLog(PMA_logEntry $log)
    {
        $this->incrementStat('total_of_logs');
        $log = $this->logControl($log);
        if (is_object($log)) {
            $this->incrementStat('total_unfiltred_logs');
            $this->logs[] = $log;
        }
    }
}
