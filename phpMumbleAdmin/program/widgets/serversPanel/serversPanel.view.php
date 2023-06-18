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

$widget = $PMA->widgets->getDatas('serversPanel');

if ($widget->displayServersList): ?>

                <form class="dropdown_list" method="GET">

                    <input type="hidden" name="page" value="vserver" />

<?php if ($widget->displayRefreshButton): ?>
                    <a href="?cmd=overview&amp;refreshServerList" title="<?= $TEXT['refresh_srv_cache']; ?>">
                        <img src="<?= PMA_IMG_REFRESH_16; ?>" class="button" alt="" />
                    </a>
<?php endif; ?>

                    <select name="sid" required="required" onChange="submit();">
                        <option value=""><?= $TEXT['select_server']; ?></option>
<?php foreach ($widget->serversList as $o): // Collapse all options by removing EOL ?>
<option class="<?= $o->css; ?>" <?= HTML::disabled($o->disabled); ?> value="<?= $o->id; ?>"><?= $o->text; ?></option><?= ''; ?>
<?php endforeach; ?>

                    </select>
                    <noscript>
                        <input type="submit" value="<?= $TEXT['ok']; ?>" />
                    </noscript>
<?php foreach ($widget->serversListButtons as $button):
if (is_int($button->id)): ?>
                    <a href="?page=vserver&amp;sid=<?= $button->id; ?>">
                        <img src="<?= $button->src; ?>" class="button" alt="" />
                    </a>
<?php else: ?>
                    <img src="<?= PMA_IMG_SPACE_16; ?>" class="button" alt="" />
<?php endif;
endforeach; ?>

                </form>
<?php endif;

if ($widget->displayServerName): ?>
                <h2>
                    <a href="?cmd=config&amp;toggle_infopanel" title="<?= $TEXT['toggle_panel']; ?>" onClick="return toggleInfoPanel(this);">
                        <img src="<?= $widget->infoPanelSrc; ?>" alt="" />
                    </a>
                    <span class="id"><?= $widget->get('serverID'); ?></span>
                    <abbr>#</abbr>
                    <span class="serverName"><?= $widget->get('serverName'); ?></span>
                </h2>
<?php endif;
