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

/*
* Display messages to user.
* MEMO: keep controllers in this script, to catch very last messages.
*/

pmaLoadLanguage('messages');
$widget->messagesBox = array();

/*
* Ice errors.
*/
if ($PMA->hasIceError()) {
    if ($PMA->router->getRoute('page') === 'configuration') {
        $data = new PMA_messageObject();
        $data->title = pmaGetText('ice_error');
        $data->text = pmaGetText($PMA->getIceError());
        $widget->messagesBox[] = $data;
    } elseif ($PMA->user->is(PMA_USER_UNAUTH)) {
        $data = new PMA_messageObject();
        $data->text = pmaGetText('ice_error_unauth');
        $widget->messagesBox[] = $data;
    }
}

/*
* User messages.
*/
foreach ($PMA->getMessages() as $message) {
    $message['key'] = pmaGetText($message['key']);
    if (isset($message['sprintf'])) {
        $message['key'] = sprintf($message['key'], $message['sprintf']);
    }
    $data = new PMA_messageObject();
    $data->text = $message['key'];
    $data->type = $message['type'];
    $data->closeButton = ($message['type'] === 'success');
    $widget->messagesBox[] = $data;
}

foreach ($widget->messagesBox as $m): ?>
        <div class="messageBox">
            <div class="inside <?= $m->type; ?>">
<?php if ($m->closeButton): ?>
                <a href="./" class="button" title="<?= $TEXT['close']; ?>"
                    onClick="removeElement(this.parentNode.parentNode); return false;">
                    <img src="<?= PMA_IMG_CANCEL_12; ?>" alt="" />
                </a>
<?php endif;
if (! is_null($m->title)): ?>
                <h3>
                    <strong><?= $m->title; ?></strong>
                </h3>
<?php endif; ?>
                <p><?= $m->text; ?></p>
            </div>
        </div>
<?php endforeach;
