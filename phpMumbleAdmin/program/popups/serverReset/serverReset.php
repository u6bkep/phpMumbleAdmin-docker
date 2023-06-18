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

if (! isset($_GET['resetServer'])) {
    // js popup
    $widget->sid = '%d';
    $widget->set('serverName', '%s');
    $widget->set('confirmText', $TEXT['confirm_reset_srv']);
} else {
    if (! ctype_digit($_GET['resetServer'])) {
        $PMA->messageError('illegal_operation');
        throw new PMA_pageException();
    }

    $widget->sid = $_GET['resetServer'];

    // Check current admin rights for the virtual server
    if ($PMA->user->is(PMA_USER_ADMIN)) {
        if (! $PMA->user->checkServerAccess($widget->sid)) {
            $PMA->messageError('illegal_operation');
            throw new PMA_pageException();
        }
    }

    if (is_null($server = $PMA->murmurMeta->getServer($widget->sid))) {
        $PMA->messageError('Murmur_InvalidServerException');
        throw new PMA_pageException();
    }

    $widget->set('serverName', $server->getParameter('registername'));
    $widget->set('confirmText', sprintf($TEXT['confirm_reset_srv'], $widget->sid));
}
