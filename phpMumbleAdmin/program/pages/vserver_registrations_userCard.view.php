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

if (! defined('PMA_STARTED')) { die('You cannot call this script directly !'); } ?>

<div class="toolbar">
<?php if ($PMA->user->isMinimum(PMA_USER_SUPERUSER_RU)): ?>
    <a href="?mumbleRegistration=unset" class="button right" title="<?= $TEXT['cancel']; ?>">
        <img src="<?= PMA_IMG_CANCEL_22; ?>" alt="" />
    </a>
<?php endif; ?>
</div>

<div id="mumbleRegistration">

    <div class="card">

        <ul class="menu">
            <li>
                <a href="?editMumbleRegistration" onClick="return popup('mumbleRegistrationEditor');"><?= $TEXT['edit_account']; ?></a>
            </li>
<?php if ($page->deleteAccount): ?>
            <li>
                <a href="?delete_account" onClick="return popup('mumbleRegistrationDelete');"><?= $TEXT['delete_acc']; ?></a>
            </li>
<?php endif;
if ($page->deleteAvatar): ?>
            <li>
                <a href="?remove_avatar" onClick="return popup('mumbleRegistrationDeleteAvatar');"><?= $TEXT['delete_avatar']; ?></a>
            </li>
<?php endif; ?>
        </ul>

        <div class="userInfos">
            <table class="config">
                <tr>
                    <th><?= $TEXT['login']; ?></th>
                    <td class="hide"></td>
                </tr>
                <tr>
                    <td colspan="2">
<?php if ($page->userIsOnlineLink): ?>
                        <a href="?tab=channels&amp;userSession=<?= $page->get('statusUrl'); ?>" class="button" title="<?= $TEXT['user_is_online']; ?>">
                            <img src="<?= PMA_IMG_ONLINE_16; ?>" alt="" />
                        </a>
<?php elseif ($page->userIsOnline): ?>
                        <img src="<?= PMA_IMG_ONLINE_16; ?>" class="button" title="<?= $TEXT['online']; ?>" alt="" />
<?php else: ?>
                        <img src="<?= PMA_IMG_OFFLINE_16; ?>" class="button" title="<?= $TEXT['offline']; ?>" alt="" />
<?php endif; ?>
                        <span class="login"><?= $page->get('login'); ?></span>
                    </td>
                </tr>

                <tr>
                    <th>
                        <?= $TEXT['email_addr'], PHP_EOL; ?>
<?php if ($page->certificat !== ''): ?>
                        <span class="tooltip">
                            <img src="<?= PMA_IMG_INFO_16; ?>" alt="" />
                            <span class="desc"><?= $TEXT['cert_email_info']; ?></span>
                        </span>
<?php endif; ?>
                    </th>
                    <td class="hide"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <a href="mailto:<?= $page->get('email'); ?>" title="mailto:<?= $page->get('email'); ?>">
                            <?= $page->get('email'), PHP_EOL; ?>
                        </a>
                    </td>
                </tr>

<?php if ($page->is_set('lastActivity')): ?>
                <tr>
                    <th><?= $TEXT['last_activity']; ?></th>
                    <td class="hide"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span class="tHelp" title="<?= $page->get('lastActivityTitle'); ?>"><?= $page->get('lastActivity'); ?></span>
                    </td>
                </tr>
<?php endif; ?>

<?php if ($page->showCertificatHash): ?>
                <tr>
                    <th><?= $TEXT['cert_hash']; ?></th>
                    <td class="hide"></td>
                </tr>
                <tr>
                    <td colspan="2"><?= $page->certificat; ?></td>
                </tr>
<?php endif; ?>

                <tr>
                    <th><?= $TEXT['comment']; ?></th>
                    <td class="hide"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <iframe src="<?= PMA_FILE_SANDBOX_RELATIVE; ?>" sandbox="">
                            <p>Your browser does not support iframes.</p>
                        </iframe>
                    </td>
                </tr>
            </table>
        </div>

<?php if ($page->showAvatar): ?>
            <div class="avatar">
<?php if ($page->avatar->isEmpty()): ?>
                <div class="text"><?= $TEXT['no_avatar']; ?></div>
<?php else: ?>
                <img src="<?= $page->avatar->getSRC(); ?>" alt="" />
<?php endif; ?>
            </div>
<?php endif; ?>

        <div class="clear"></div>

    </div>
</div>
