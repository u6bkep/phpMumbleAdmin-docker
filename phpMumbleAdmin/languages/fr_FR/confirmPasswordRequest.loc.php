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

/*
* %1$s = "HTTP hostname or IP"
* %2$s = "Profile name"
* %3$s = "Mumble server name"
* %4$s = "HTTP url"
* %5$d = "delay time"
*/

$TEXT['pw_mail_title'] = 'Requête de génération de mot de passe Mumble';

$TEXT['pw_mail_body'] =
'Serveur HTTP : %1$s<br />
Profile ice : %2$s<br />
Serveur mumble : %3$s<br /><br />

Si vous ne savez pas de quoi il sagit, ou si vous n\'avez simplement pas demandé à générer un mot de passe Mumble,
vous pouvez effacer cet email et rien ne seras fait.<br /><br />

Veuillez confirmer la requête de génération de mot de passe faite pour votre compte mumble en suivant ce lien: <br /><br />

<a href="%4$s">%4$s</a><br /><br />

Ce lien est valide %5$d heure(s) et vous redirigera vers votre nouveau mot de passe.';
