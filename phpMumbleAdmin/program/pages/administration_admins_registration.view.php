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
    <div class="right">
        <a href="?adminRegistration=unset" class="button" title="<?= $TEXT['back']; ?>">
            <img src="<?= PMA_IMG_CANCEL_22; ?>" alt="" />
        </a>
    </div>
    <a href="?adminRegistrationEditor" class="button" title="<?= $TEXT['edit_account']; ?>"
        onClick="return popup('adminsRegistrationEditor');">
        <img src="<?= PMA_IMG_EDIT_22; ?>" alt="" />
    </a>
</div>

<div id="admin">

    <aside class="card">
        <p>
            <span class="key"><?= $TEXT['login']; ?>:</span>
            <span class="value"><?= $page->get('admLogin'); ?></span>
        </p>
        <p>
            <span class="key"><?= $TEXT['class']; ?>:</span>
            <mark class="<?= $page->get('admClassName'); ?>"> <?= $page->get('admClassName'); ?> </mark>
        </p>
        <p>
            <span class="key">ID:</span>
            <span class="value"><?= $page->get('admID'); ?></span>
        </p>
        <p>
            <span class="key"><?= $TEXT['registered_date']; ?>:</span>
            <span class="value tHelp" title="<?= $page->get('admCreatedUptime'); ?>"><?= $page->get('admCreatedDate'); ?></span>
        </p>
        <p>
            <span class="key"><?= $TEXT['last_conn']; ?>:</span>
<?php if (isset($page->lastConn)): ?>
            <span class="value tHelp" title="<?= $page->get('lastConnUptime'); ?>"><?= $page->get('lastConnDate'); ?></span>
<?php endif; ?>
        </p>
        <p>
            <span class="key"><?= $TEXT['email_addr']; ?>:</span>
<?php if (isset($page->email)): ?>
                <a href="mailto:<?= $page->get('email'); ?>" title="mailto:<?= $page->get('email'); ?>">
                    <span class="value"><?= $page->get('email'); ?></span>
                </a>
<?php endif; ?>
        </p>
        <p>
            <span class="key"><?= $TEXT['user_name']; ?>:</span>
            <span class="value"><?= $page->get('admName'); ?></span>
        </p>

        <h4 class="key"><?= $TEXT['profile_access']; ?>:</h4>
        <ul>
<?php foreach ($page->profilesAccess as $d): ?>
            <li>
<?php if ($d->selected): ?>
                <mark class="value access"><img src="images/xchat/red_8.png" alt="" /><?= $d->textEnc; ?></mark>
<?php else: ?>
                <span class="value access"><img src="images/xchat/blue_8.png" alt="" /><?= $d->textEnc; ?></span>
<?php endif; ?>
            </li>
<?php endforeach; ?>
        </ul>

    </aside>

<?php if (isset($page->showServersScroll)): ?>

    <form id="adminsServersAccess" method="post" onSubmit="return isFormModified(this);">

        <input type="hidden" name="cmd" value="config_admins" />
        <input type="hidden" name="editAccess" value="<?= $page->get('admID'); ?>" />

        <div class="buttons">
            <input type="reset" value="<?= $TEXT['reset']; ?>" />
            <script type="text/javascript">
                document.write(
                '<input type="button" onClick="uncheck(\'fullAccess\'); checkAllBox(\'serversScroll\');" value="<?= $TEXT['all']; ?>" />'+
                '<input type="button" onClick="uncheck(\'fullAccess\'); uncheckAllBox(\'serversScroll\');" value="<?= $TEXT['none']; ?>" />'+
                '<input type="button" onClick="uncheck(\'fullAccess\'); invertAllChkBox(\'serversScroll\');" value="<?= $TEXT['invert']; ?>" />'
                );
            </script>
            <input type="submit" class="apply" value="<?= $TEXT['apply']; ?>" />
        </div>

        <div class="fullAccess">
            <input type="checkbox" <?= $page->chked('hasFullAccess'); ?>
                id="fullAccess" name="fullAccess" onClick="AdminFullAccessToggle(this);" />
            <label for="fullAccess"><?= $TEXT['enable_full_access']; ?></label>
        </div>

        <ul id="serversScroll" class="scroll">
<?php if (empty($page->serversScroll)): ?>
            <li>Error: Failed to get the servers list.</li>
<?php endif;
foreach ($page->serversScroll as $d): ?>
            <li>
                <input type="checkbox" id="<?= $d->label; ?>" name="<?= $d->id; ?>"
                    onClick="uncheck('fullAccess');" <?= HTML::chked($d->chked); ?> />
                <label for="<?= $d->label; ?>"><?= $d->id; ?># <?= htEnc($d->name); ?></label>
            </li>
<?php endforeach; ?>
        </ul>

    </form>
<?php endif; ?>

    <div class="clear"></div>
</div>
